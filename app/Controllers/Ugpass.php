<?php

namespace App\Controllers;

use App\Libraries\Ugpass as UgpassLib;

/**
 * Controller for handling NITA-U UG Pass Authentication and Single Sign-On
 */
class Ugpass extends App_Controller {

    private $ugpass;

    public function __construct() {
        parent::__construct();
        $this->ugpass = new UgpassLib();
        helper('email');
    }

    /**
     * Initiate Sign In with UG Pass
     * Redirects the user to the DAES Authorization endpoint
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->Users_model->login_user_id()) {
            return redirect()->to(get_uri('dashboard/view'));
        }

        // Capture intended destination
        $redirect = $this->request->getGet('redirect');
        if ($redirect) {
            $this->session->set('ugpass_target_redirect', $redirect);
        }

        // Check if UG Pass is configured with credentials from .env
        if (!$this->ugpass->is_configured()) {
            $msg = 'UG Pass authentication credentials have not been configured yet in your .env file. Please configure UGPASS_CLIENT_ID and your Private Key.';
            $this->session->setFlashdata('signin_validation_errors', [$msg]);
            $this->session->setFlashdata('ugpass_error', $msg);
            return redirect()->to(get_uri('signin'));
        }

        try {
            $state = $this->ugpass->generate_state();
            $nonce = $this->ugpass->generate_nonce();

            $this->session->set('ugpass_state', $state);
            $this->session->set('ugpass_nonce', $nonce);

            $authRequest = $this->ugpass->get_authorization_request($state, $nonce);

            return redirect()->to($authRequest['url']);
        } catch (\Exception $e) {
            log_message('error', '[UGPASS] Login initialization error: ' . $e->getMessage());
            $this->session->setFlashdata('signin_validation_errors', ['UG Pass initialization failed: ' . $e->getMessage()]);
            return redirect()->to(get_uri('signin'));
        }
    }

    /**
     * OIDC Callback Handler
     * Handles redirect from DAES Authorization server
     */
    public function callback() {
        // 1. Check for errors returned by DAES
        $error = $this->request->getGet('error');
        $errorDescription = $this->request->getGet('error_description');

        if ($error) {
            log_message('error', "[UGPASS] Authorization returned error: {$error} - {$errorDescription}");
            $displayMsg = $errorDescription ?: "UG Pass authorization was canceled or failed ({$error}).";
            $this->session->setFlashdata('signin_validation_errors', [$displayMsg]);
            $this->session->setFlashdata('ugpass_error', $displayMsg);
            return redirect()->to(get_uri('signin'));
        }

        // 2. Validate State (CSRF protection)
        $returnedState = (string)$this->request->getGet('state');
        $savedState = (string)$this->session->get('ugpass_state');

        if (empty($returnedState) || empty($savedState) || !hash_equals($savedState, $returnedState)) {
            log_message('error', '[UGPASS] CSRF State validation failed.');
            $this->session->setFlashdata('signin_validation_errors', ['UG Pass login session expired or state mismatch. Please try again.']);
            return redirect()->to(get_uri('signin'));
        }

        // 3. Obtain Authorization Code
        $code = (string)$this->request->getGet('code');
        if (empty($code)) {
            log_message('error', '[UGPASS] No authorization code returned in callback.');
            $this->session->setFlashdata('signin_validation_errors', ['No authorization code received from UG Pass.']);
            return redirect()->to(get_uri('signin'));
        }

        // 4. Exchange code for access token and id_token
        $tokenResponse = $this->ugpass->exchange_code_for_token($code);
        if (!$tokenResponse['success']) {
            log_message('error', '[UGPASS] Token exchange error: ' . $tokenResponse['error']);
            $this->session->setFlashdata('signin_validation_errors', ['UG Pass authentication token exchange failed: ' . $tokenResponse['error']]);
            return redirect()->to(get_uri('signin'));
        }

        $tokenData = $tokenResponse['data'];
        $idToken = $tokenData['id_token'];
        $accessToken = isset($tokenData['access_token']) ? $tokenData['access_token'] : null;

        // 5. Parse and verify ID Token
        $savedNonce = (string)$this->session->get('ugpass_nonce');
        try {
            $claims = $this->ugpass->parse_and_verify_id_token($idToken, $savedNonce, $accessToken, false);
        } catch (\Exception $e) {
            log_message('error', '[UGPASS] ID token verification failed: ' . $e->getMessage());
            $this->session->setFlashdata('signin_validation_errors', ['UG Pass identity verification failed: ' . $e->getMessage()]);
            return redirect()->to(get_uri('signin'));
        }

        // 6. Extract user identity claims
        $daesClaims = isset($claims['daes_claims']) && is_array($claims['daes_claims']) ? $claims['daes_claims'] : [];
        $suid = isset($daesClaims['suid']) ? trim($daesClaims['suid']) : (isset($claims['sub']) ? trim($claims['sub']) : '');
        $email = isset($daesClaims['email']) ? trim($daesClaims['email']) : (isset($claims['email']) ? trim($claims['email']) : '');
        $name = isset($daesClaims['name']) ? trim($daesClaims['name']) : (isset($claims['name']) ? trim($claims['name']) : '');
        $phone = isset($daesClaims['phone']) ? trim($daesClaims['phone']) : '';
        $idDocNumber = isset($daesClaims['id_document_number']) ? trim($daesClaims['id_document_number']) : '';
        $loa = isset($daesClaims['loa']) ? trim($daesClaims['loa']) : '';
        $gender = isset($daesClaims['gender']) ? trim($daesClaims['gender']) : '';
        $birthdate = isset($daesClaims['birthdate']) ? trim($daesClaims['birthdate']) : '';

        // 7. Find or Link User (Section 4.2.3 of NITA-U specification)
        $user = $this->find_matching_user($suid, $email, $idDocNumber, $phone);

        if ($user) {
            // Validate user can log in
            if ($user->deleted == 1 || $user->status !== 'active' || (isset($user->disable_login) && $user->disable_login == 1)) {
                log_message('warning', "[UGPASS] User #{$user->id} is inactive or login disabled.");
                $this->session->setFlashdata('signin_validation_errors', ['Your account has been deactivated or disabled. Please contact your administrator.']);
                return redirect()->to(get_uri('signin'));
            }

            // Update user with SUID and LOA if not linked yet
            $updateData = [];
            if (empty($user->ugpass_suid) && !empty($suid)) {
                $updateData['ugpass_suid'] = $suid;
            }
            if (empty($user->ugpass_loa) && !empty($loa)) {
                $updateData['ugpass_loa'] = $loa;
            }
            $updateData['last_online'] = date('Y-m-d H:i:s');

            if (!empty($updateData)) {
                $this->Users_model->ci_save($updateData, $user->id);
            }

            $userId = $user->id;
        } else {
            // No matching user found - Auto-create if permitted
            if ($this->ugpass->should_auto_create_user()) {
                $userId = $this->create_user_from_ugpass($name, $email, $phone, $idDocNumber, $suid, $loa, $gender, $birthdate);
                if (!$userId) {
                    $this->session->setFlashdata('signin_validation_errors', ['Failed to provision a local account for your UG Pass identity.']);
                    return redirect()->to(get_uri('signin'));
                }
            } else {
                log_message('notice', "[UGPASS] No user found for SUID={$suid}, Email={$email}. Auto-registration disabled.");
                $msg = 'Your UG Pass identity was successfully authenticated, but no matching Intranet staff account was found. Please contact the administrator to link your account.';
                $this->session->setFlashdata('signin_validation_errors', [$msg]);
                return redirect()->to(get_uri('signin'));
            }
        }

        // 8. Establish Application Session
        $this->session->set('user_id', $userId);
        $this->session->set('ugpass_id_token', $idToken);
        if ($accessToken) {
            $this->session->set('ugpass_access_token', $accessToken);
        }

        // Clean up temporary auth session variables
        $this->session->remove('ugpass_state');
        $this->session->remove('ugpass_nonce');

        // Trigger after signin hooks
        try {
            app_hooks()->do_action('app_hook_after_signin');
        } catch (\Exception $ex) {
            log_message('error', '[UGPASS] app_hook_after_signin error: ' . $ex->getMessage());
        }

        // 9. Redirect to target
        $targetRedirect = $this->session->get('ugpass_target_redirect');
        $this->session->remove('ugpass_target_redirect');

        if ($targetRedirect) {
            $allowedHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
            $parsedRedirect = parse_url($targetRedirect);
            $redirectHost = isset($parsedRedirect['host']) ? $parsedRedirect['host'] : '';

            if (!$redirectHost || $allowedHost === $redirectHost) {
                return redirect()->to($targetRedirect);
            }
        }

        return redirect()->to(get_uri('dashboard/view'));
    }

    /**
     * Single Sign-Out / Logout
     * Section 4.2.5 of NITA-U Specification
     */
    public function logout() {
        $idToken = $this->session->get('ugpass_id_token');

        // Destroy local session
        try {
            app_hooks()->do_action('app_hook_before_signout');
        } catch (\Exception $ex) {
            log_message('error', '[UGPASS] app_hook_before_signout error: ' . $ex->getMessage());
        }

        $this->session->destroy();

        if ($idToken && $this->ugpass->is_configured()) {
            $logoutUrl = $this->ugpass->get_logout_url($idToken);
            return redirect()->to($logoutUrl);
        }

        return redirect()->to(get_uri('signin'));
    }

    /**
     * Find matching user according to NITA-U Section 4.2.3
     * Matches by:
     * 1. SUID (previously linked)
     * 2. Email
     * 3. NIN / Passport (ssn field)
     * 4. Phone
     */
    private function find_matching_user(string $suid, string $email, string $nin, string $phone) {
        $db = \Config\Database::connect();
        $builder = $db->table($db->prefixTable('users'));

        // 1. Match by SUID
        if (!empty($suid)) {
            $user = $builder->where('ugpass_suid', $suid)->where('deleted', 0)->get()->getRow();
            if ($user) {
                return $user;
            }
        }

        // 2. Match by Email
        if (!empty($email)) {
            $user = $builder->where('email', $email)->where('deleted', 0)->get()->getRow();
            if ($user) {
                return $user;
            }
        }

        // 3. Match by NIN / ID number (stored in ssn column)
        if (!empty($nin)) {
            $user = $builder->where('ssn', $nin)->where('deleted', 0)->get()->getRow();
            if ($user) {
                return $user;
            }
        }

        // 4. Match by Phone
        if (!empty($phone)) {
            // Clean phone digits for comparison
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            if (strlen($cleanPhone) >= 9) {
                $suffix = substr($cleanPhone, -9);
                $sql = "SELECT * FROM " . $db->prefixTable('users') . " WHERE deleted = 0 AND (phone LIKE ? OR alternative_phone LIKE ?) LIMIT 1";
                $query = $db->query($sql, ["%{$suffix}%", "%{$suffix}%"]);
                $user = $query->getRow();
                if ($user) {
                    return $user;
                }
            }
        }

        return null;
    }

    /**
     * Auto-provision a new user record from UG Pass DAES claims
     */
    private function create_user_from_ugpass(string $name, string $email, string $phone, string $nin, string $suid, string $loa, string $gender, string $birthdate): ?int {
        // Parse Name into first_name and last_name
        $trimmedName = trim($name);
        if ($trimmedName) {
            $parts = preg_split('/\s+/', $trimmedName, 2);
            $firstName = $parts[0];
            $lastName = isset($parts[1]) ? $parts[1] : '';
        } else {
            $firstName = 'UGPass';
            $lastName = 'User';
        }

        if (empty($lastName)) {
            $lastName = '.';
        }

        // Fallback email if empty
        if (empty($email)) {
            $cleanSuid = preg_replace('/[^a-zA-Z0-9]/', '', $suid);
            $email = 'ugpass_' . substr($cleanSuid ?: bin2hex(random_bytes(4)), 0, 8) . '@ncs.go.ug';
        }

        // Format DOB if present
        $dob = null;
        if (!empty($birthdate)) {
            $timestamp = strtotime($birthdate);
            if ($timestamp !== false) {
                $dob = date('Y-m-d', $timestamp);
            }
        }

        $userData = [
            'first_name' => clean_data($firstName),
            'last_name' => clean_data($lastName),
            'email' => clean_data($email),
            'phone' => clean_data($phone),
            'ssn' => clean_data($nin), // Store NIN
            'gender' => strtolower($gender) === 'female' ? 'female' : 'male',
            'dob' => $dob,
            'ugpass_suid' => $suid,
            'ugpass_loa' => $loa,
            'user_type' => $this->ugpass->get_default_user_type() ?: 'staff',
            'status' => 'active',
            'is_admin' => 0,
            'role_id' => 0,
            'language' => 'english',
            'created_at' => date('Y-m-d H:i:s'),
            'last_online' => date('Y-m-d H:i:s'),
            'deleted' => 0,
            'disable_login' => 0,
            'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
        ];

        try {
            $saveId = $this->Users_model->ci_save($userData);
            if ($saveId) {
                log_message('info', "[UGPASS] Successfully created new user ID={$saveId} for {$email} (SUID: {$suid})");
                return (int)$saveId;
            }
        } catch (\Exception $ex) {
            log_message('error', '[UGPASS] User provisioning exception: ' . $ex->getMessage());
        }

        return null;
    }
}

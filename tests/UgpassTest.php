<?php

/**
 * NITA-U UG Pass Integration Unit Test Suite
 *
 * Tests all authentication, cryptographic, token exchange, account linking,
 * and error handling scenarios according to NITA-U Specification V1.5.
 */

// Define basic environment if not set
if (!defined('APPPATH')) {
    define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
}
if (!defined('WRITEPATH')) {
    define('WRITEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR);
}

// Require Ugpass library
require_once APPPATH . 'Libraries/Ugpass.php';

use App\Libraries\Ugpass;

class UgpassTest {

    private $passed = 0;
    private $failed = 0;
    private $total = 0;
    private $testKeypair = [];

    public function __construct() {
        $this->generate_test_rsa_keys();
    }

    private function generate_test_rsa_keys() {
        $cnf = 'C:/laragon/bin/php/php-8.3.33-Win32-vs16-x64/extras/ssl/openssl.cnf';
        $config = ['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA];
        if (file_exists($cnf)) {
            $config['config'] = $cnf;
        }

        $res = openssl_pkey_new($config);
        if (!$res) {
            throw new \RuntimeException("Failed to generate test RSA key: " . openssl_error_string());
        }

        openssl_pkey_export($res, $privKey, null, isset($config['config']) ? ['config' => $cnf] : []);
        $details = openssl_pkey_get_details($res);
        $pubKey = $details['key'];

        $this->testKeypair = [
            'private' => $privKey,
            'public' => $pubKey,
            'n' => Ugpass::base64url_encode($details['rsa']['n']),
            'e' => Ugpass::base64url_encode($details['rsa']['e']),
        ];
    }

    private function assert($condition, string $testName, string $failureDetails = '') {
        $this->total++;
        if ($condition) {
            $this->passed++;
            echo "  [PASS] {$testName}\n";
        } else {
            $this->failed++;
            echo "  [FAIL] {$testName}" . ($failureDetails ? " -> {$failureDetails}" : "") . "\n";
        }
    }

    public function runAllTests() {
        echo "====================================================================\n";
        echo "   NITA-U UG Pass Integration Test Suite - Specification V1.5       \n";
        echo "====================================================================\n\n";

        $this->testConfigurationDefaults();
        $this->testIsConfiguredLogic();
        $this->testStateAndNonceGeneration();
        $this->testBase64UrlEncoding();
        $this->testRS256JwtSigningAndVerification();
        $this->testAuthorizationRequestGeneration();
        $this->testClientAssertionCreation();
        $this->testTokenExchangeSuccess();
        $this->testTokenExchangeErrorResponses();
        $this->testTokenExchangeNetworkFailure();
        $this->testIdTokenValidationSuccess();
        $this->testIdTokenExpiredRejection();
        $this->testIdTokenAudienceMismatchRejection();
        $this->testIdTokenNonceMismatchRejection();
        $this->testIdTokenAtHashVerification();
        $this->testRsaJwkToPemConversion();
        $this->testSingleSignOutLogoutUrl();
        $this->testUserAccountLinkingMatchingLogic();
        $this->testCsrfStateValidation();

        echo "\n--------------------------------------------------------------------\n";
        echo "Test Summary: Total: {$this->total} | Passed: {$this->passed} | Failed: {$this->failed}\n";
        echo "====================================================================\n";

        return ($this->failed === 0);
    }

    /**
     * Test 1: Configuration Defaults & Environment Detection
     */
    public function testConfigurationDefaults() {
        echo "[Suite 1: Configuration & Environment]\n";

        // Staging defaults
        $staging = new Ugpass(['environment' => 'staging']);
        $this->assert($staging->get_environment() === 'staging', 'Environment set to staging');
        $this->assert($staging->get_base_url() === Ugpass::STAGING_BASE_URL, 'Staging base URL matches specification');
        $this->assert($staging->get_token_url() === Ugpass::STAGING_BASE_URL . 'api/Authentication/token', 'Staging token URL is correct');
        $this->assert($staging->get_scopes() === Ugpass::DEFAULT_SCOPES, 'Default scopes include openid and profile');

        // Production defaults
        $prod = new Ugpass(['environment' => 'production']);
        $this->assert($prod->get_environment() === 'production', 'Environment set to production');
        $this->assert($prod->get_base_url() === Ugpass::PRODUCTION_BASE_URL, 'Production base URL matches specification');
        $this->assert($prod->get_token_url() === Ugpass::PRODUCTION_BASE_URL . 'api/Authentication/token', 'Production token URL is correct');

        // Custom overrides
        $custom = new Ugpass([
            'client_id' => 'test-custom-client',
            'base_url' => 'https://custom.daes.go.ug/idp',
            'scopes' => 'openid custom:scope'
        ]);
        $this->assert($custom->get_client_id() === 'test-custom-client', 'Custom Client ID loaded');
        $this->assert($custom->get_base_url() === 'https://custom.daes.go.ug/idp/', 'Custom Base URL normalised with trailing slash');
        $this->assert($custom->get_scopes() === 'openid custom:scope', 'Custom scopes loaded');
    }

    /**
     * Test 2: is_configured() validation
     */
    public function testIsConfiguredLogic() {
        echo "\n[Suite 2: is_configured() Validation]\n";

        // Unconfigured
        $unconf = new Ugpass(['client_id' => '', 'private_key' => '']);
        $this->assert($unconf->is_configured() === false, 'Returns false when client_id and key are empty');

        // Placeholder client_id
        $placeholder = new Ugpass(['client_id' => 'change-me', 'private_key' => $this->testKeypair['private']]);
        $this->assert($placeholder->is_configured() === false, 'Returns false when client_id is placeholder (change-me)');

        // Valid client_id but missing private key
        $missingKey = new Ugpass(['client_id' => 'valid-client-id-123', 'private_key' => '']);
        $this->assert($missingKey->is_configured() === false, 'Returns false when private key is empty');

        // Valid client_id but invalid PEM
        $badKey = new Ugpass(['client_id' => 'valid-client-id-123', 'private_key' => 'INVALID_PEM_STRING']);
        $this->assert($badKey->is_configured() === false, 'Returns false when private key is invalid PEM');

        // Fully configured with real RSA key
        $configured = new Ugpass(['client_id' => 'valid-client-id-123', 'private_key' => $this->testKeypair['private']]);
        $this->assert($configured->is_configured() === true, 'Returns true when client_id and valid RSA private key are provided');
    }

    /**
     * Test 3: State and Nonce Generation
     */
    public function testStateAndNonceGeneration() {
        echo "\n[Suite 3: State and Nonce Entropy]\n";

        $ugpass = new Ugpass();
        $state1 = $ugpass->generate_state(32);
        $state2 = $ugpass->generate_state(32);
        $nonce1 = $ugpass->generate_nonce(32);
        $nonce2 = $ugpass->generate_nonce(32);

        $this->assert(strlen($state1) === 32, 'State length is 32 characters');
        $this->assert(ctype_xdigit($state1), 'State is valid hexadecimal');
        $this->assert($state1 !== $state2, 'States are uniquely generated (cryptographic entropy)');

        $this->assert(strlen($nonce1) === 32, 'Nonce length is 32 characters');
        $this->assert(ctype_xdigit($nonce1), 'Nonce is valid hexadecimal');
        $this->assert($nonce1 !== $nonce2, 'Nonces are uniquely generated (cryptographic entropy)');
    }

    /**
     * Test 4: Base64URL Encoding and Decoding
     */
    public function testBase64UrlEncoding() {
        echo "\n[Suite 4: Base64URL (RFC 7515)]\n";

        $testStrings = [
            'Hello World!',
            'UgPass-Authentication-NITA-U',
            "\x00\x01\x02\xfb\xfc\xfd\xfe\xff",
            '{"alg":"RS256","typ":"JWT"}'
        ];

        $allMatch = true;
        foreach ($testStrings as $str) {
            $enc = Ugpass::base64url_encode($str);
            if (strpos($enc, '+') !== false || strpos($enc, '/') !== false || strpos($enc, '=') !== false) {
                $allMatch = false;
                break;
            }
            $dec = Ugpass::base64url_decode($enc);
            if ($dec !== $str) {
                $allMatch = false;
                break;
            }
        }

        $this->assert($allMatch, 'Base64URL encoding does not contain +, /, = and decodes reversibly');
    }

    /**
     * Test 5: RS256 JWT Signing and Cryptographic Verification
     */
    public function testRS256JwtSigningAndVerification() {
        echo "\n[Suite 5: RS256 JWT Signing & Signature Verification]\n";

        $ugpass = new Ugpass([
            'client_id' => 'test-sp-client',
            'private_key' => $this->testKeypair['private'],
            'public_key' => $this->testKeypair['public']
        ]);

        $payload = ['iss' => 'test-sp-client', 'data' => 'sample_jwt_data', 'iat' => time()];
        $jwt = $ugpass->generate_jwt(['typ' => 'JWT'], $payload);

        $parts = explode('.', $jwt);
        $this->assert(count($parts) === 3, 'JWT has 3 dot-separated segments');

        $header = json_decode(Ugpass::base64url_decode($parts[0]), true);
        $this->assert($header['alg'] === 'RS256', 'JWT header specifies alg RS256');

        // Cryptographic verification with public key
        $dataToVerify = $parts[0] . '.' . $parts[1];
        $sig = Ugpass::base64url_decode($parts[2]);
        $pubKey = openssl_pkey_get_public($this->testKeypair['public']);
        $valid = openssl_verify($dataToVerify, $sig, $pubKey, OPENSSL_ALGO_SHA256);
        $this->assert($valid === 1, 'RS256 signature is cryptographically valid');

        // Tampered payload verification
        $tamperedPayload = Ugpass::base64url_encode(json_encode(['iss' => 'hacked']));
        $invalid = openssl_verify($parts[0] . '.' . $tamperedPayload, $sig, $pubKey, OPENSSL_ALGO_SHA256);
        $this->assert($invalid !== 1, 'Tampered JWT payload fails signature verification');
    }

    /**
     * Test 6: Authorization Request Generation (Section 4.2.1)
     */
    public function testAuthorizationRequestGeneration() {
        echo "\n[Suite 6: Authorization Request Generation]\n";

        $ugpass = new Ugpass([
            'client_id' => 'sp-client-789',
            'redirect_uri' => 'https://intranet.ncs.go.ug/ugpass/callback',
            'private_key' => $this->testKeypair['private'],
            'environment' => 'staging'
        ]);

        $state = $ugpass->generate_state();
        $nonce = $ugpass->generate_nonce();

        $req = $ugpass->get_authorization_request($state, $nonce);

        $this->assert(!empty($req['url']), 'Authorization URL is generated');
        $this->assert(!empty($req['request_jwt']), 'Request JWT is generated');

        $parsedUrl = parse_url($req['url']);
        parse_str($parsedUrl['query'], $query);

        $this->assert($query['client_id'] === 'sp-client-789', 'client_id query parameter matches');
        $this->assert($query['redirect_uri'] === 'https://intranet.ncs.go.ug/ugpass/callback', 'redirect_uri matches');
        $this->assert($query['response_type'] === 'code', 'response_type is code');
        $this->assert($query['state'] === $state, 'state query parameter matches generated state');
        $this->assert($query['nonce'] === $nonce, 'nonce query parameter matches generated nonce');
        $this->assert(!empty($query['request']), 'request query parameter contains signed JWT');

        // Decode Request JWT
        $jwtParts = explode('.', $query['request']);
        $jwtPayload = json_decode(Ugpass::base64url_decode($jwtParts[1]), true);
        $this->assert($jwtPayload['iss'] === 'sp-client-789', 'Request JWT iss matches client_id');
        $this->assert($jwtPayload['aud'] === rtrim(Ugpass::STAGING_BASE_URL, '/'), 'Request JWT aud matches DAES base URL');
        $this->assert($jwtPayload['state'] === $state, 'Request JWT state matches');
        $this->assert($jwtPayload['nonce'] === $nonce, 'Request JWT nonce matches');
    }

    /**
     * Test 7: Client Assertion JWT Creation (Section 4.2.2.4 & 4.2.2.5)
     */
    public function testClientAssertionCreation() {
        echo "\n[Suite 7: Client Assertion JWT Creation]\n";

        $ugpass = new Ugpass([
            'client_id' => 'sp-client-789',
            'private_key' => $this->testKeypair['private'],
            'environment' => 'staging'
        ]);

        $assertion = $ugpass->create_client_assertion();
        $parts = explode('.', $assertion);
        $this->assert(count($parts) === 3, 'Client assertion is a valid 3-part JWT');

        $header = json_decode(Ugpass::base64url_decode($parts[0]), true);
        $payload = json_decode(Ugpass::base64url_decode($parts[1]), true);

        $this->assert($header['alg'] === 'RS256', 'Client assertion uses RS256');
        $this->assert($payload['iss'] === 'sp-client-789', 'iss matches client_id');
        $this->assert($payload['sub'] === 'sp-client-789', 'sub matches client_id');
        $this->assert($payload['aud'] === Ugpass::STAGING_BASE_URL . 'api/Authentication/token', 'aud matches Token Endpoint URL');
        $this->assert(isset($payload['exp']) && $payload['exp'] > time(), 'exp is set in the future');
        $this->assert(isset($payload['jti']) && !empty($payload['jti']), 'jti unique token identifier is present');
    }

    /**
     * Test 8: Token Exchange - Successful Flow (Mocked)
     */
    public function testTokenExchangeSuccess() {
        echo "\n[Suite 8: Token Exchange - Success Flow]\n";

        $ugpass = new Ugpass([
            'client_id' => 'sp-client-789',
            'redirect_uri' => 'https://intranet.ncs.go.ug/ugpass/callback',
            'private_key' => $this->testKeypair['private']
        ]);

        $mockIdToken = $ugpass->generate_jwt(
            ['alg' => 'RS256', 'typ' => 'JWT'],
            ['iss' => Ugpass::STAGING_BASE_URL, 'sub' => 'suid-12345', 'aud' => 'sp-client-789', 'exp' => time() + 3600]
        );

        $mockResponseData = [
            'access_token' => 'mock_ugpass_access_token_xyz',
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'scopes' => Ugpass::DEFAULT_SCOPES,
            'id_token' => $mockIdToken
        ];

        $ugpass->set_http_mock(function($method, $url, $headers, $body) use ($mockResponseData) {
            parse_str($body, $params);
            if ($params['grant_type'] === 'authorization_code' && $params['code'] === 'valid_auth_code_abc') {
                return [
                    'success' => true,
                    'status_code' => 200,
                    'body' => json_encode($mockResponseData),
                    'error' => null
                ];
            }
            return ['success' => false, 'status_code' => 400, 'body' => '{"error":"invalid_grant"}', 'error' => 'HTTP 400'];
        });

        $res = $ugpass->exchange_code_for_token('valid_auth_code_abc');
        $this->assert($res['success'] === true, 'Token exchange returns success');
        $this->assert($res['data']['access_token'] === 'mock_ugpass_access_token_xyz', 'access_token received correctly');
        $this->assert($res['data']['id_token'] === $mockIdToken, 'id_token received correctly');
    }

    /**
     * Test 9: Token Exchange - Error Handling (Mocked)
     */
    public function testTokenExchangeErrorResponses() {
        echo "\n[Suite 9: Token Exchange - Error Responses]\n";

        $ugpass = new Ugpass([
            'client_id' => 'sp-client-789',
            'private_key' => $this->testKeypair['private']
        ]);

        // Error: invalid_code
        $ugpass->set_http_mock(function() {
            return [
                'success' => true, // HTTP 200/400 containing error JSON
                'status_code' => 400,
                'body' => json_encode(['error' => 'invalid_code', 'error_description' => 'The authorization code was not valid or expired']),
                'error' => null
            ];
        });

        $res = $ugpass->exchange_code_for_token('expired_code');
        $this->assert($res['success'] === false, 'Returns failure when error returned in token JSON');
        $this->assert(strpos($res['error'], 'invalid_code') !== false, 'Error message mentions invalid_code');
    }

    /**
     * Test 10: Token Exchange - Network Failure Handling
     */
    public function testTokenExchangeNetworkFailure() {
        echo "\n[Suite 10: Token Exchange - Network Failure]\n";

        $ugpass = new Ugpass([
            'client_id' => 'sp-client-789',
            'private_key' => $this->testKeypair['private']
        ]);

        $ugpass->set_http_mock(function() {
            return [
                'success' => false,
                'status_code' => 0,
                'body' => null,
                'error' => 'cURL Network Error: Connection timed out after 30000 milliseconds'
            ];
        });

        $res = $ugpass->exchange_code_for_token('any_code');
        $this->assert($res['success'] === false, 'Returns failure on network timeout');
        $this->assert(strpos($res['error'], 'timed out') !== false, 'Reports network error description');
    }

    /**
     * Test 11: ID Token Validation - Success Flow
     */
    public function testIdTokenValidationSuccess() {
        echo "\n[Suite 11: ID Token Parsing & Validation - Success]\n";

        $ugpass = new Ugpass([
            'client_id' => 'sp-client-789',
            'private_key' => $this->testKeypair['private'],
            'public_key' => $this->testKeypair['public'],
            'environment' => 'staging'
        ]);

        $nonce = 'test_nonce_12345';
        $now = time();

        $claims = [
            'iss' => Ugpass::STAGING_BASE_URL,
            'aud' => 'sp-client-789',
            'sub' => 'c9283b55-6f62-40b2-acf8-bf7482648ff7',
            'iat' => $now,
            'exp' => $now + 3600,
            'nonce' => $nonce,
            'auth_time' => $now,
            'daes_claims' => [
                'suid' => 'c9283b55-6f62-40b2-acf8-bf7482648ff7',
                'name' => 'John Doe',
                'email' => 'john.doe@ncs.go.ug',
                'phone' => '+256701234567',
                'gender' => 'MALE',
                'birthdate' => '1985-05-15',
                'id_document_number' => 'CM85012345ABCD',
                'loa' => 'LOA1'
            ]
        ];

        $idToken = $ugpass->generate_jwt(['alg' => 'RS256', 'typ' => 'JWT'], $claims);

        $parsed = $ugpass->parse_and_verify_id_token($idToken, $nonce, null, true);

        $this->assert($parsed['sub'] === 'c9283b55-6f62-40b2-acf8-bf7482648ff7', 'ID Token sub parsed correctly');
        $this->assert($parsed['daes_claims']['name'] === 'John Doe', 'Name claim extracted');
        $this->assert($parsed['daes_claims']['email'] === 'john.doe@ncs.go.ug', 'Email claim extracted');
        $this->assert($parsed['daes_claims']['id_document_number'] === 'CM85012345ABCD', 'NIN claim extracted');
        $this->assert($parsed['daes_claims']['loa'] === 'LOA1', 'LOA level extracted');
    }

    /**
     * Test 12: ID Token Validation - Expired Token Rejection
     */
    public function testIdTokenExpiredRejection() {
        echo "\n[Suite 12: ID Token Expiration Check]\n";

        $ugpass = new Ugpass([
            'client_id' => 'sp-client-789',
            'private_key' => $this->testKeypair['private'],
            'public_key' => $this->testKeypair['public']
        ]);

        $expiredClaims = [
            'iss' => Ugpass::STAGING_BASE_URL,
            'aud' => 'sp-client-789',
            'sub' => 'suid-expired',
            'iat' => time() - 7200,
            'exp' => time() - 3600 // Expired 1 hour ago
        ];

        $token = $ugpass->generate_jwt(['alg' => 'RS256', 'typ' => 'JWT'], $expiredClaims);

        $threw = false;
        try {
            $ugpass->parse_and_verify_id_token($token, null, null, false);
        } catch (\UnexpectedValueException $e) {
            $threw = true;
        }

        $this->assert($threw, 'Rejects expired ID token with UnexpectedValueException');
    }

    /**
     * Test 13: ID Token Validation - Audience Mismatch Rejection
     */
    public function testIdTokenAudienceMismatchRejection() {
        echo "\n[Suite 13: ID Token Audience Check]\n";

        $ugpass = new Ugpass([
            'client_id' => 'sp-client-789',
            'private_key' => $this->testKeypair['private']
        ]);

        $wrongAudClaims = [
            'iss' => Ugpass::STAGING_BASE_URL,
            'aud' => 'different-unauthorized-client',
            'sub' => 'suid-wrong-aud',
            'exp' => time() + 3600
        ];

        $token = $ugpass->generate_jwt(['alg' => 'RS256', 'typ' => 'JWT'], $wrongAudClaims);

        $threw = false;
        try {
            $ugpass->parse_and_verify_id_token($token, null, null, false);
        } catch (\UnexpectedValueException $e) {
            $threw = true;
        }

        $this->assert($threw, 'Rejects ID token with audience mismatch');
    }

    /**
     * Test 14: ID Token Validation - Nonce Mismatch Rejection (Replay Attack)
     */
    public function testIdTokenNonceMismatchRejection() {
        echo "\n[Suite 14: Replay Mitigation - Nonce Verification]\n";

        $ugpass = new Ugpass([
            'client_id' => 'sp-client-789',
            'private_key' => $this->testKeypair['private']
        ]);

        $tokenClaims = [
            'iss' => Ugpass::STAGING_BASE_URL,
            'aud' => 'sp-client-789',
            'sub' => 'suid-replay',
            'nonce' => 'old_replay_nonce_abc',
            'exp' => time() + 3600
        ];

        $token = $ugpass->generate_jwt(['alg' => 'RS256', 'typ' => 'JWT'], $tokenClaims);

        $threw = false;
        try {
            $ugpass->parse_and_verify_id_token($token, 'expected_fresh_nonce_xyz', null, false);
        } catch (\UnexpectedValueException $e) {
            $threw = true;
        }

        $this->assert($threw, 'Rejects ID token when nonce does not match session nonce');
    }

    /**
     * Test 15: ID Token Validation - at_hash Verification
     */
    public function testIdTokenAtHashVerification() {
        echo "\n[Suite 15: at_hash Access Token Hash Verification]\n";

        $ugpass = new Ugpass([
            'client_id' => 'sp-client-789',
            'private_key' => $this->testKeypair['private']
        ]);

        $accessToken = 'mwACT8EldqdTlbRn0gZCCNfcvmUl+tpNc/sUvDul94C';
        $sha = hash('sha256', $accessToken, true);
        $left128 = substr($sha, 0, 16);
        $expectedAtHash = Ugpass::base64url_encode($left128);

        $claims = [
            'iss' => Ugpass::STAGING_BASE_URL,
            'aud' => 'sp-client-789',
            'sub' => 'suid-hash-test',
            'at_hash' => $expectedAtHash,
            'exp' => time() + 3600
        ];

        $token = $ugpass->generate_jwt(['alg' => 'RS256', 'typ' => 'JWT'], $claims);

        // Verification with matching access_token
        $parsed = $ugpass->parse_and_verify_id_token($token, null, $accessToken, false);
        $this->assert($parsed['at_hash'] === $expectedAtHash, 'at_hash matches SHA-256 leftmost 128 bits');

        // Verification with wrong access_token
        $threw = false;
        try {
            $ugpass->parse_and_verify_id_token($token, null, 'different_access_token', false);
        } catch (\UnexpectedValueException $e) {
            $threw = true;
        }
        $this->assert($threw, 'Rejects ID token when at_hash does not match access token');
    }

    /**
     * Test 16: RSA JWK to PEM Conversion (Section 4.2.6)
     */
    public function testRsaJwkToPemConversion() {
        echo "\n[Suite 16: RSA JWK to PEM Conversion]\n";

        // Convert the test keypair's modulus & exponent to PEM
        $pem = Ugpass::rsa_jwk_to_pem($this->testKeypair['n'], $this->testKeypair['e']);

        $this->assert(strpos($pem, '-----BEGIN PUBLIC KEY-----') !== false, 'Generates valid PEM header');
        $this->assert(strpos($pem, '-----END PUBLIC KEY-----') !== false, 'Generates valid PEM footer');

        $pubKey = openssl_pkey_get_public($pem);
        $this->assert($pubKey !== false, 'OpenSSL successfully loads the derived PEM public key');

        // Verify key details
        $details = openssl_pkey_get_details($pubKey);
        $this->assert($details['bits'] === 2048, 'Public key modulus is 2048 bits');
        $this->assert($details['type'] === OPENSSL_KEYTYPE_RSA, 'Key type is RSA');

        // Verify signature with derived PEM
        $testMsg = 'Test message for JWK signature verification';
        openssl_sign($testMsg, $signature, $this->testKeypair['private'], OPENSSL_ALGO_SHA256);
        $verify = openssl_verify($testMsg, $signature, $pubKey, OPENSSL_ALGO_SHA256);
        $this->assert($verify === 1, 'Derived PEM public key verifies RS256 signature produced by private key');
    }

    /**
     * Test 17: Single Sign-Out / Logout URL (Section 4.2.5)
     */
    public function testSingleSignOutLogoutUrl() {
        echo "\n[Suite 17: Single Sign-Out / OIDC Logout]\n";

        $ugpass = new Ugpass([
            'environment' => 'staging',
            'logout_redirect_uri' => 'https://intranet.ncs.go.ug/signin'
        ]);

        $idTokenHint = 'mock.jwt.token';
        $logoutState = 'logout_csrf_state_456';
        $url = $ugpass->get_logout_url($idTokenHint, null, $logoutState);

        $parsed = parse_url($url);
        parse_str($parsed['query'], $query);

        $this->assert(strpos($url, Ugpass::STAGING_BASE_URL . 'OIDClogout') === 0, 'Logout URL targets DAES OIDClogout endpoint');
        $this->assert($query['id_token_hint'] === $idTokenHint, 'id_token_hint query param present');
        $this->assert($query['post_logout_redirect_uri'] === 'https://intranet.ncs.go.ug/signin', 'post_logout_redirect_uri matches');
        $this->assert($query['state'] === $logoutState, 'state query param present for logout');
    }

    /**
     * Test 18: Account Linking & Resolution Logic (Section 4.2.3)
     */
    public function testUserAccountLinkingMatchingLogic() {
        echo "\n[Suite 18: Account Linking & User Matching]\n";

        // Simulate user records
        $existingUsers = [
            (object)['id' => 1, 'email' => 'admin@ncs.go.ug', 'ugpass_suid' => '', 'ssn' => 'CM8001', 'phone' => '+256700000001', 'deleted' => 0, 'status' => 'active'],
            (object)['id' => 2, 'email' => 'officer@ncs.go.ug', 'ugpass_suid' => 'suid-linked-99', 'ssn' => 'CM8002', 'phone' => '+256700000002', 'deleted' => 0, 'status' => 'active'],
            (object)['id' => 3, 'email' => 'inactive@ncs.go.ug', 'ugpass_suid' => 'suid-inactive', 'ssn' => 'CM8003', 'phone' => '+256700000003', 'deleted' => 0, 'status' => 'inactive'],
        ];

        // Matcher function identical to Controller logic
        $matcher = function($suid, $email, $nin, $phone) use ($existingUsers) {
            // 1. By SUID
            if (!empty($suid)) {
                foreach ($existingUsers as $u) {
                    if ($u->ugpass_suid === $suid && $u->deleted === 0) return $u;
                }
            }
            // 2. By Email
            if (!empty($email)) {
                foreach ($existingUsers as $u) {
                    if (strcasecmp($u->email, $email) === 0 && $u->deleted === 0) return $u;
                }
            }
            // 3. By NIN (ssn)
            if (!empty($nin)) {
                foreach ($existingUsers as $u) {
                    if ($u->ssn === $nin && $u->deleted === 0) return $u;
                }
            }
            // 4. By Phone
            if (!empty($phone)) {
                $suffix = substr(preg_replace('/[^0-9]/', '', $phone), -9);
                foreach ($existingUsers as $u) {
                    if (strpos($u->phone, $suffix) !== false && $u->deleted === 0) return $u;
                }
            }
            return null;
        };

        // Match by SUID
        $match1 = $matcher('suid-linked-99', 'different@email.com', '', '');
        $this->assert($match1 !== null && $match1->id === 2, 'Matches previously linked user by SUID');

        // Match by Email
        $match2 = $matcher('', 'admin@ncs.go.ug', '', '');
        $this->assert($match2 !== null && $match2->id === 1, 'Matches existing user by Email');

        // Match by NIN
        $match3 = $matcher('', '', 'CM8001', '');
        $this->assert($match3 !== null && $match3->id === 1, 'Matches existing user by NIN (ssn)');

        // Match by Phone
        $match4 = $matcher('', '', '', '0700000001');
        $this->assert($match4 !== null && $match4->id === 1, 'Matches existing user by 9-digit phone suffix');

        // Unmatched returns null
        $match5 = $matcher('suid-brand-new', 'newuser@external.com', 'NIN999', '+256799999999');
        $this->assert($match5 === null, 'Returns null when identity is not linked or matched');
    }

    /**
     * Test 19: CSRF State Timing-safe Hash Comparison
     */
    public function testCsrfStateValidation() {
        echo "\n[Suite 19: CSRF State Timing-Safe Validation]\n";

        $state = 'a6c8e54728f3a9e145b2049c6f789012';
        $sameState = 'a6c8e54728f3a9e145b2049c6f789012';
        $mismatchedState = 'b7d9f6583904b0f256c3150d70890123';

        $this->assert(hash_equals($state, $sameState) === true, 'Accepts matching CSRF state via hash_equals');
        $this->assert(hash_equals($state, $mismatchedState) === false, 'Rejects altered CSRF state via hash_equals');
        $this->assert(hash_equals($state, '') === false, 'Rejects empty CSRF state');
    }
}

// Execute tests if executed directly
if (isset($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__)) {
    $test = new UgpassTest();
    $success = $test->runAllTests();
    exit($success ? 0 : 1);
}

<?php

namespace App\Libraries;

/**
 * NITA-U UG Pass (DAES) Integration Library
 *
 * Implements the NITA-U UgPass 3rd Party Integration Token API & OIDC Specification (v1.5).
 * Handles:
 * - OIDC Authorization URL generation with signed Request JWT (RS256)
 * - State & Nonce generation and verification (CSRF and Replay mitigation)
 * - Token Endpoint exchange with Client Assertion JWT (RS256)
 * - ID Token validation, JWKS signature verification, and claims extraction
 * - Single Sign-Out (OIDClogout)
 */
class Ugpass {

    private $client_id;
    private $client_secret;
    private $private_key;
    private $public_key;
    private $environment;
    private $base_url;
    private $token_url;
    private $jwks_url;
    private $logout_url;
    private $redirect_uri;
    private $logout_redirect_uri;
    private $scopes;
    private $auto_create_user;
    private $default_user_type;
    private $verify_ssl;

    /**
     * Optional callable mock for HTTP requests (used in unit tests)
     * Signature: function(string $method, string $url, array $headers, $body, array $options): array
     */
    private $http_mock = null;

    /**
     * Staging and Production defaults according to NITA-U specification
     */
    const STAGING_BASE_URL = 'https://stgapi.ugpass.go.ug/idp/';
    const PRODUCTION_BASE_URL = 'https://api.ugpass.go.ug/idp/';
    const DEFAULT_SCOPES = 'openid urn:idp:digitalid:profile';
    const CLIENT_ASSERTION_TYPE = 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer';

    public function __construct(array $config = []) {
        $this->load_configuration($config);
    }

    /**
     * Load settings from parameters, environment variables (.env), or database settings
     */
    public function load_configuration(array $config = []) {
        // Environment
        $env = isset($config['environment']) ? $config['environment'] : $this->get_env_val('UGPASS_ENVIRONMENT', 'staging');
        $this->environment = strtolower(trim($env));

        // Client ID
        $this->client_id = isset($config['client_id']) ? $config['client_id'] : $this->get_env_val('UGPASS_CLIENT_ID', '');
        $this->client_id = trim($this->client_id);

        // Client Secret (optional / for key decryption)
        $this->client_secret = isset($config['client_secret']) ? $config['client_secret'] : $this->get_env_val('UGPASS_CLIENT_SECRET', '');

        // Base URL
        $default_base = ($this->environment === 'production') ? self::PRODUCTION_BASE_URL : self::STAGING_BASE_URL;
        $baseUrl = isset($config['base_url']) ? $config['base_url'] : $this->get_env_val('UGPASS_BASE_URL', $default_base);
        $this->base_url = rtrim(trim($baseUrl), '/') . '/';

        // Endpoint URLs
        $this->token_url = isset($config['token_url']) ? $config['token_url'] : $this->get_env_val('UGPASS_TOKEN_URL', $this->base_url . 'api/Authentication/token');
        $this->jwks_url = isset($config['jwks_url']) ? $config['jwks_url'] : $this->get_env_val('UGPASS_JWKS_URL', $this->base_url . 'api/Jwks/jwksuri');
        $this->logout_url = isset($config['logout_url']) ? $config['logout_url'] : $this->get_env_val('UGPASS_LOGOUT_URL', $this->base_url . 'OIDClogout');

        // Redirect URIs
        $default_redirect = function_exists('get_uri') ? get_uri('ugpass/callback') : '';
        $this->redirect_uri = isset($config['redirect_uri']) ? $config['redirect_uri'] : $this->get_env_val('UGPASS_REDIRECT_URI', $default_redirect);

        $default_logout_redirect = function_exists('get_uri') ? get_uri('signin') : '';
        $this->logout_redirect_uri = isset($config['logout_redirect_uri']) ? $config['logout_redirect_uri'] : $this->get_env_val('UGPASS_LOGOUT_REDIRECT_URI', $default_logout_redirect);

        // Scopes
        $this->scopes = isset($config['scopes']) ? $config['scopes'] : $this->get_env_val('UGPASS_SCOPES', self::DEFAULT_SCOPES);

        // Auto Create User
        $autoCreate = isset($config['auto_create_user']) ? $config['auto_create_user'] : $this->get_env_val('UGPASS_AUTO_CREATE_USER', 'true');
        $this->auto_create_user = filter_var($autoCreate, FILTER_VALIDATE_BOOLEAN);

        // Default User Type
        $this->default_user_type = isset($config['default_user_type']) ? $config['default_user_type'] : $this->get_env_val('UGPASS_DEFAULT_USER_TYPE', 'staff');

        // SSL verification
        $verifySsl = isset($config['verify_ssl']) ? $config['verify_ssl'] : $this->get_env_val('UGPASS_VERIFY_SSL', 'true');
        $this->verify_ssl = filter_var($verifySsl, FILTER_VALIDATE_BOOLEAN);

        // Private Key loading
        $privateKeyStr = isset($config['private_key']) ? $config['private_key'] : $this->get_env_val('UGPASS_PRIVATE_KEY', '');
        $privateKeyPath = isset($config['private_key_path']) ? $config['private_key_path'] : $this->get_env_val('UGPASS_PRIVATE_KEY_PATH', '');

        if (!$privateKeyStr && $privateKeyPath && file_exists($privateKeyPath)) {
            $privateKeyStr = file_get_contents($privateKeyPath);
        }
        $this->private_key = $privateKeyStr ? trim($privateKeyStr) : '';

        // Public Key loading
        $publicKeyStr = isset($config['public_key']) ? $config['public_key'] : $this->get_env_val('UGPASS_PUBLIC_KEY', '');
        $publicKeyPath = isset($config['public_key_path']) ? $config['public_key_path'] : $this->get_env_val('UGPASS_PUBLIC_KEY_PATH', '');

        if (!$publicKeyStr && $publicKeyPath && file_exists($publicKeyPath)) {
            $publicKeyStr = file_get_contents($publicKeyPath);
        }
        $this->public_key = $publicKeyStr ? trim($publicKeyStr) : '';
    }

    /**
     * Check if UG Pass has been fully configured with credentials from .env
     */
    public function is_configured(): bool {
        if (empty($this->client_id)) {
            return false;
        }

        // Check for placeholder values
        $placeholders = ['change_me', 'change-me', 'your_client_id', 'enter_client_id', 'your-client-id'];
        if (in_array(strtolower($this->client_id), $placeholders)) {
            return false;
        }

        // Validate Private Key
        if (empty($this->private_key)) {
            return false;
        }

        $pkey = @openssl_pkey_get_private($this->private_key, $this->client_secret ?: null);
        if ($pkey === false) {
            return false;
        }

        return true;
    }

    /**
     * Get configured values
     */
    public function get_client_id(): string {
        return $this->client_id;
    }

    public function get_environment(): string {
        return $this->environment;
    }

    public function get_base_url(): string {
        return $this->base_url;
    }

    public function get_token_url(): string {
        return $this->token_url;
    }

    public function get_redirect_uri(): string {
        return $this->redirect_uri;
    }

    public function get_logout_redirect_uri(): string {
        return $this->logout_redirect_uri;
    }

    public function get_scopes(): string {
        return $this->scopes;
    }

    public function should_auto_create_user(): bool {
        return $this->auto_create_user;
    }

    public function get_default_user_type(): string {
        return $this->default_user_type;
    }

    /**
     * Generate cryptographically secure random state (CSRF mitigation)
     */
    public function generate_state(int $length = 32): string {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Generate cryptographically secure random nonce (Replay mitigation)
     */
    public function generate_nonce(int $length = 32): string {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Base64URL Encode (RFC 7515)
     */
    public static function base64url_encode(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Base64URL Decode (RFC 7515)
     */
    public static function base64url_decode(string $data): string {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Generate and sign a JWT using RS256
     */
    public function generate_jwt(array $header, array $payload, string $private_key_pem = ''): string {
        $key = $private_key_pem ?: $this->private_key;
        if (empty($key)) {
            throw new \InvalidArgumentException("Private key is required to generate RS256 JWT.");
        }

        $pkey = openssl_pkey_get_private($key, $this->client_secret ?: null);
        if ($pkey === false) {
            throw new \RuntimeException("Unable to load private key for JWT signing: " . openssl_error_string());
        }

        $header['alg'] = 'RS256';
        if (!isset($header['typ'])) {
            $header['typ'] = 'JWT';
        }

        $encodedHeader = self::base64url_encode(json_encode($header, JSON_UNESCAPED_SLASHES));
        $encodedPayload = self::base64url_encode(json_encode($payload, JSON_UNESCAPED_SLASHES));
        $dataToSign = $encodedHeader . '.' . $encodedPayload;

        $signature = '';
        $success = openssl_sign($dataToSign, $signature, $pkey, OPENSSL_ALGO_SHA256);
        if (!$success) {
            throw new \RuntimeException("Failed to sign JWT with RS256: " . openssl_error_string());
        }

        $encodedSignature = self::base64url_encode($signature);
        return $dataToSign . '.' . $encodedSignature;
    }

    /**
     * Generate the OIDC Authorization URL and Request JWT
     * Section 4.2.1 of NITA-U Specification
     *
     * @param string $state Random string generated by SP (stored in session)
     * @param string $nonce Random string generated by SP (mitigates replay)
     * @param string|null $redirect_uri Optional redirect URI override
     * @param string|null $scopes Optional scopes override
     * @return array ['url' => string, 'request_jwt' => string, 'state' => string, 'nonce' => string]
     */
    public function get_authorization_request(string $state, string $nonce, ?string $redirect_uri = null, ?string $scopes = null): array {
        $redirect = $redirect_uri ?: $this->redirect_uri;
        $scope = $scopes ?: $this->scopes;

        $now = time();
        $jwtPayload = [
            'iss' => $this->client_id,
            'aud' => rtrim($this->base_url, '/'),
            'iat' => $now,
            'exp' => $now + 3600,
            'nbf' => $now,
            'jti' => $this->generate_uuid(),
            'redirect_uri' => $redirect,
            'response_type' => 'code',
            'scope' => $scope,
            'nonce' => $nonce,
            'state' => $state,
        ];

        $jwtHeader = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        $requestJwt = $this->generate_jwt($jwtHeader, $jwtPayload);

        $queryParams = [
            'client_id' => $this->client_id,
            'redirect_uri' => $redirect,
            'response_type' => 'code',
            'scope' => $scope,
            'state' => $state,
            'nonce' => $nonce,
            'request' => $requestJwt
        ];

        $authUrl = rtrim($this->base_url, '/') . '/authorization?' . http_build_query($queryParams);

        return [
            'url' => $authUrl,
            'request_jwt' => $requestJwt,
            'state' => $state,
            'nonce' => $nonce
        ];
    }

    /**
     * Create client_assertion JWT for Token Endpoint Request
     * Section 4.2.2.4 & 4.2.2.5 of NITA-U Specification
     */
    public function create_client_assertion(?string $token_endpoint = null): string {
        $endpoint = $token_endpoint ?: $this->token_url;
        $now = time();

        $payload = [
            'iss' => $this->client_id,
            'sub' => $this->client_id,
            'aud' => $endpoint,
            'iat' => $now,
            'exp' => $now + 3600,
            'nbf' => $now,
            'jti' => $this->generate_uuid(),
        ];

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        return $this->generate_jwt($header, $payload);
    }

    /**
     * Exchange Authorization Code for Access Token and ID Token
     * Section 4.2.2 of NITA-U Specification
     *
     * @param string $code Authorization Code received from DAES
     * @param string|null $redirect_uri Optional redirect_uri override
     * @return array Result array ['success' => bool, 'data' => array, 'error' => string|null]
     */
    public function exchange_code_for_token(string $code, ?string $redirect_uri = null): array {
        $redirect = $redirect_uri ?: $this->redirect_uri;
        $clientAssertion = $this->create_client_assertion();

        $postFields = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect,
            'client_id' => $this->client_id,
            'client_assertion_type' => self::CLIENT_ASSERTION_TYPE,
            'client_assertion' => $clientAssertion,
        ];

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        ];

        $response = $this->execute_http_request('POST', $this->token_url, $headers, http_build_query($postFields));

        if (!$response['success']) {
            return [
                'success' => false,
                'error' => $response['error'],
                'data' => null
            ];
        }

        $body = $response['body'];
        $data = json_decode($body, true);

        if (!is_array($data)) {
            return [
                'success' => false,
                'error' => 'Invalid JSON response received from UG Pass Token endpoint: ' . substr($body, 0, 200),
                'data' => null
            ];
        }

        if (isset($data['error'])) {
            $errDesc = isset($data['error_description']) ? $data['error_description'] : $data['error'];
            return [
                'success' => false,
                'error' => "UG Pass Token Error [{$data['error']}]: {$errDesc}",
                'data' => $data
            ];
        }

        if (!isset($data['id_token'])) {
            return [
                'success' => false,
                'error' => 'No id_token received in UG Pass token response.',
                'data' => $data
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'data' => $data
        ];
    }

    /**
     * Parse and verify ID Token JWT (RS256)
     * Section 4.2.2.8 - 4.2.2.10 & Section 4.2.6 (JWKS Verification)
     *
     * @param string $id_token Encoded JWT
     * @param string|null $expected_nonce Expected nonce from authorization step
     * @param string|null $access_token Optional access token to verify at_hash
     * @param bool $verify_signature Whether to verify cryptographic signature
     * @return array Decoded payload claims
     */
    public function parse_and_verify_id_token(string $id_token, ?string $expected_nonce = null, ?string $access_token = null, bool $verify_signature = true): array {
        $parts = explode('.', $id_token);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException('Malformed ID token structure: expected 3 dot-separated segments.');
        }

        list($headerB64, $payloadB64, $sigB64) = $parts;

        $headerJson = self::base64url_decode($headerB64);
        $payloadJson = self::base64url_decode($payloadB64);

        $header = json_decode($headerJson, true);
        $payload = json_decode($payloadJson, true);

        if (!is_array($header) || !is_array($payload)) {
            throw new \InvalidArgumentException('Failed to decode ID token header or payload as JSON.');
        }

        // 1. Verify Algorithm
        $alg = isset($header['alg']) ? $header['alg'] : '';
        if ($alg !== 'RS256') {
            throw new \UnexpectedValueException("Unsupported ID token algorithm: {$alg}. Expected RS256.");
        }

        // 2. Verify Expiration
        $now = time();
        if (isset($payload['exp']) && ($payload['exp'] < ($now - 60))) { // allow 60s clock skew
            throw new \UnexpectedValueException("ID token has expired (exp: {$payload['exp']}, now: {$now}).");
        }

        // 3. Verify Audience
        if (isset($payload['aud'])) {
            $aud = is_array($payload['aud']) ? $payload['aud'] : [$payload['aud']];
            if (!in_array($this->client_id, $aud)) {
                throw new \UnexpectedValueException("ID token audience mismatch: expected {$this->client_id}.");
            }
        }

        // 4. Verify Issuer
        if (isset($payload['iss'])) {
            $expectedIssuer = rtrim($this->base_url, '/');
            $actualIssuer = rtrim($payload['iss'], '/');
            // DAES might omit or include trailing slash, or contain base url
            if (stripos($actualIssuer, parse_url($expectedIssuer, PHP_URL_HOST)) === false && $actualIssuer !== $expectedIssuer) {
                // Log warning or throw if completely different host
                log_message('warning', "UG Pass ID token issuer '{$actualIssuer}' differs from base URL '{$expectedIssuer}'.");
            }
        }

        // 5. Verify Nonce (Replay attack mitigation)
        if ($expected_nonce !== null) {
            $tokenNonce = isset($payload['nonce']) ? $payload['nonce'] : '';
            if ($tokenNonce !== $expected_nonce) {
                throw new \UnexpectedValueException("ID token nonce mismatch. Possible replay attack.");
            }
        }

        // 6. Verify at_hash (Section 4.2.6.2 & RFC 7636)
        if ($access_token !== null && isset($payload['at_hash'])) {
            $sha256 = hash('sha256', $access_token, true);
            $left128 = substr($sha256, 0, 16);
            $expectedAtHash = self::base64url_encode($left128);
            if ($payload['at_hash'] !== $expectedAtHash) {
                throw new \UnexpectedValueException("ID token at_hash does not match access token hash.");
            }
        }

        // 7. Verify Signature
        if ($verify_signature) {
            $signature = self::base64url_decode($sigB64);
            $dataToVerify = $headerB64 . '.' . $payloadB64;
            $publicKeyPem = $this->resolve_public_key($header);

            if ($publicKeyPem) {
                $pubKey = openssl_pkey_get_public($publicKeyPem);
                if ($pubKey === false) {
                    throw new \RuntimeException('Failed to load public key for ID token verification: ' . openssl_error_string());
                }

                $verifyResult = openssl_verify($dataToVerify, $signature, $pubKey, OPENSSL_ALGO_SHA256);
                if ($verifyResult !== 1) {
                    throw new \UnexpectedValueException('ID token RS256 signature verification failed.');
                }
            } else {
                log_message('warning', 'UG Pass public key or JWKS could not be resolved; signature verification skipped in non-strict mode.');
            }
        }

        return $payload;
    }

    /**
     * Resolve public key for ID token verification using JWKS or local public key
     */
    private function resolve_public_key(array $header): ?string {
        // If a static public key was explicitly configured, check it first
        if (!empty($this->public_key)) {
            return $this->public_key;
        }

        $kid = isset($header['kid']) ? $header['kid'] : null;
        $jwks = $this->get_jwks_keys();

        if ($jwks && isset($jwks['keys']) && is_array($jwks['keys'])) {
            foreach ($jwks['keys'] as $key) {
                if ($kid && isset($key['kid']) && $key['kid'] !== $kid) {
                    continue;
                }

                if (isset($key['kty']) && $key['kty'] === 'RSA' && isset($key['n']) && isset($key['e'])) {
                    return self::rsa_jwk_to_pem($key['n'], $key['e']);
                }
            }
        }

        return null;
    }

    /**
     * Fetch JSON Web Key Set (JWKS) from DAES
     * Section 4.2.6.1 of NITA-U Specification
     */
    public function get_jwks_keys(bool $force_refresh = false): ?array {
        $cacheFile = WRITEPATH . 'cache/ugpass_jwks.json';

        if (!$force_refresh && file_exists($cacheFile) && (time() - filemtime($cacheFile) < 86400)) {
            $cached = @file_get_contents($cacheFile);
            if ($cached) {
                $decoded = json_decode($cached, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        }

        $response = $this->execute_http_request('GET', $this->jwks_url, ['Accept: application/json']);
        if ($response['success']) {
            $data = json_decode($response['body'], true);
            if (is_array($data) && isset($data['keys'])) {
                @file_put_contents($cacheFile, $response['body']);
                return $data;
            }
        }

        return null;
    }

    /**
     * Convert RSA JWK modulus (n) and exponent (e) into standard PEM public key
     * Pure PHP ASN.1 DER SPKI encoding (RFC 5280)
     */
    public static function rsa_jwk_to_pem(string $n_b64, string $e_b64): string {
        $n = self::base64url_decode($n_b64);
        $e = self::base64url_decode($e_b64);

        // Prepend 0x00 if highest bit is set (ensures positive integer in DER)
        if (ord($n[0]) > 0x7f) {
            $n = chr(0x00) . $n;
        }
        if (ord($e[0]) > 0x7f) {
            $e = chr(0x00) . $e;
        }

        $n_der = chr(0x02) . self::der_encode_length(strlen($n)) . $n;
        $e_der = chr(0x02) . self::der_encode_length(strlen($e)) . $e;
        $rsa_pub = chr(0x30) . self::der_encode_length(strlen($n_der . $e_der)) . $n_der . $e_der;

        // AlgorithmIdentifier for rsaEncryption: OID 1.2.840.113549.1.1.1 + NULL
        $algo_id = pack('H*', '300d06092a864886f70d0101010500');
        // BIT STRING with 0 unused bits
        $bit_str = chr(0x03) . self::der_encode_length(strlen($rsa_pub) + 1) . chr(0x00) . $rsa_pub;

        $spki = chr(0x30) . self::der_encode_length(strlen($algo_id . $bit_str)) . $algo_id . $bit_str;

        return "-----BEGIN PUBLIC KEY-----\n" . chunk_split(base64_encode($spki), 64, "\n") . "-----END PUBLIC KEY-----\n";
    }

    /**
     * Helper to encode ASN.1 DER length
     */
    private static function der_encode_length(int $length): string {
        if ($length <= 0x7f) {
            return chr($length);
        }
        $temp = ltrim(pack('N', $length), chr(0));
        return chr(0x80 | strlen($temp)) . $temp;
    }

    /**
     * Build Single Sign-Out / Logout URL
     * Section 4.2.5 of NITA-U Specification
     *
     * @param string|null $id_token_hint The ID token returned during signin
     * @param string|null $post_logout_redirect_uri URL to redirect to after logout
     * @param string|null $state State string for CSRF protection
     * @return string
     */
    public function get_logout_url(?string $id_token_hint = null, ?string $post_logout_redirect_uri = null, ?string $state = null): string {
        $params = [];
        if ($id_token_hint) {
            $params['id_token_hint'] = $id_token_hint;
        }

        $params['post_logout_redirect_uri'] = $post_logout_redirect_uri ?: $this->logout_redirect_uri;

        if ($state) {
            $params['state'] = $state;
        }

        return rtrim($this->logout_url, '/') . '?' . http_build_query($params);
    }

    /**
     * Execute an HTTP Request using cURL or mock handler
     */
    private function execute_http_request(string $method, string $url, array $headers = [], $body = null): array {
        if ($this->http_mock !== null && is_callable($this->http_mock)) {
            return call_user_func($this->http_mock, $method, $url, $headers, $body, [
                'verify_ssl' => $this->verify_ssl
            ]);
        }

        if (!function_exists('curl_init')) {
            return [
                'success' => false,
                'status_code' => 0,
                'body' => null,
                'error' => 'cURL PHP extension is not installed.'
            ];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->verify_ssl ? 2 : 0);

        if (strtoupper($method) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($body !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }
        }

        $responseBody = curl_exec($ch);
        $curlError = curl_error($ch);
        $statusCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($responseBody === false) {
            return [
                'success' => false,
                'status_code' => $statusCode,
                'body' => null,
                'error' => 'cURL Network Error: ' . $curlError
            ];
        }

        return [
            'success' => ($statusCode >= 200 && $statusCode < 400),
            'status_code' => $statusCode,
            'body' => $responseBody,
            'error' => ($statusCode >= 400) ? "HTTP Error Status: {$statusCode}" : null
        ];
    }

    /**
     * Set a mock HTTP handler for automated unit testing
     */
    public function set_http_mock(?callable $mock) {
        $this->http_mock = $mock;
    }

    /**
     * Helper to read value from getenv, $_ENV, or default
     */
    private function get_env_val(string $key, string $default = ''): string {
        $val = getenv($key);
        if ($val !== false && $val !== null && $val !== '') {
            return (string)$val;
        }

        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return (string)$_ENV[$key];
        }

        if (function_exists('env')) {
            $ciVal = env($key);
            if ($ciVal !== null && $ciVal !== '') {
                return (string)$ciVal;
            }
        }

        return $default;
    }

    /**
     * Generate random UUID v4
     */
    private function generate_uuid(): string {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

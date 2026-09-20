<?php
/**
 * Router for PHP's built-in development server.
 *
 * The project's front controller sits in the project root (there is no public/
 * directory), so this script mimics what .htaccess does under Apache: serve
 * real static files directly, block the application internals, and hand every
 * other request to index.php.
 *
 * Usage:
 *   php -S 127.0.0.1:8080 -t . dev-router.php
 *
 * This file is only needed for the built-in server; Apache/nginx use .htaccess.
 */

$root = __DIR__;
$uri  = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$path = realpath($root . DIRECTORY_SEPARATOR . ltrim($uri, '/'));

// Never expose application internals over HTTP
$blocked = ['app', 'system', 'writable', 'install', 'Docs', 'documentation', 'updates'];
$first   = strtok(ltrim($uri, '/'), '/');
if (in_array($first, $blocked, true) || preg_match('/(^|\/)\.(env|git)/i', $uri)) {
    http_response_code(404);
    exit('Not Found');
}

// Serve existing static assets straight from disk
if ($path !== false
    && is_file($path)
    && strpos($path, $root) === 0
    && basename($path) !== 'index.php'
    && !preg_match('/\.(php|sql|md|py|log)$/i', $path)) {
    return false;
}

// Everything else goes through the front controller. CodeIgniter derives its
// base URL from SCRIPT_NAME, so present index.php rather than this router.
$_SERVER['SCRIPT_NAME']     = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = $root . DIRECTORY_SEPARATOR . 'index.php';
$_SERVER['PHP_SELF']        = '/index.php';

require $root . DIRECTORY_SEPARATOR . 'index.php';

<?php

// Get the requested URI without the query string.
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// If the requested URI is not the root and a file exists in the public directory,
// return false to let the built-in server handle the request as a static file.
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

// For all other requests, pass control to the front controller.
// This mimics the behavior of the .htaccess rewrite rule.
$_GET['url'] = ltrim($uri, '/');
require_once __DIR__ . '/public/index.php';

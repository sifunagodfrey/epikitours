<?php
// -------------------------------
// IMPORTANT!! 
// This file is for Customer, Manager & Admin routing
// Detect environment based on server name or IP
// This file MUST be present
// -------------------------------

$isLocalhost = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);

// -------------------------------
// Define base paths
// -------------------------------
if ($isLocalhost) {
    define('BASE_URL', '/epikitours/'); // Used in HTML (CSS, Images, JS, links)
    define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/epikitours/'); // Used in PHP includes
} else {
    define('BASE_URL', '/');
    define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

// -------------------------------
// Helper function: check if a menu link is active
// -------------------------------
function isActive($segment)
{
    $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $segments = explode('/', $uri);
    $lastSegment = end($segments);

    return $lastSegment === $segment ? 'active' : '';
}
?>
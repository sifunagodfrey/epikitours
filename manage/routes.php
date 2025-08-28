<?php
// Detect environment based on server name or IP
$isLocalhost = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);
$isAdminSubdomain = ($_SERVER['SERVER_NAME'] === 'admin.epikitours.com');

// Redirect live site /manage/ to admin.epikitours.com
if (!$isLocalhost && !$isAdminSubdomain) {
    // Check if request is under /manage/
    if (strpos($_SERVER['REQUEST_URI'], '/manage') === 0) {
        $redirectUrl = 'https://admin.epikitours.com' . str_replace('/manage', '', $_SERVER['REQUEST_URI']);
        header("Location: $redirectUrl", true, 301);
        exit;
    }
}

// Define base paths
if ($isLocalhost) {
    // Localhost environment
    define('BASE_URL', '/epikitours/manage/'); // Used in HTML (CSS, Images, JS, links)
    define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/epikitours/manage/'); // Used in PHP includes
} elseif ($isAdminSubdomain) {
    // Access via admin.epikitours.com (no /manage/ in URLs)
    define('BASE_URL', '/');
    define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
} else {
    // Live site via epikitours.com/manage/
    define('BASE_URL', '/manage/');
    define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/manage/');
}

// check if a menu link is active
if (!function_exists('isActive')) {
    function isActive($segment)
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uri);
        $lastSegment = end($segments);

        return $lastSegment === $segment ? 'active' : '';
    }
}

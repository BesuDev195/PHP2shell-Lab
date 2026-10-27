<?php
/**
 * CTC Bank — PHP Built-in Server Router
 * 
 * Handles URL routing, static asset serving, and security filtering
 * for the isolated container deployment.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
$publicDir = __DIR__;
$filePath = realpath($publicDir . $uri);

// Prevent path traversal outside public directory
if ($filePath !== false && !str_starts_with($filePath, realpath($publicDir))) {
    http_response_code(403);
    require $publicDir . '/403.php';
    exit;
}

// Serve existing static assets directly
if ($filePath !== false && is_file($filePath)) {
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $staticExtensions = ['css', 'js', 'svg', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'woff', 'woff2', 'ttf', 'webp'];
    
    if (in_array($extension, $staticExtensions, true)) {
        return false; // Let PHP built-in server handle the file directly
    }

    if ($extension === 'php') {
        require $filePath;
        exit;
    }
}

// Route mapping
$routes = [
    '/'                     => '/index.php',
    '/login'                => '/login.php',
    '/logout'               => '/logout.php',
    '/dashboard'            => '/dashboard.php',
    '/accounts'             => '/accounts.php',
    '/transactions'         => '/transactions.php',
    '/challenge'            => '/challenge.php',
    '/admin'                => '/admin/diagnostics.php',
    '/admin/'               => '/admin/diagnostics.php',
    '/admin/diagnostics'    => '/admin/diagnostics.php',
    '/admin/diagnostics.php'=> '/admin/diagnostics.php',
];

if (isset($routes[$uri])) {
    require $publicDir . $routes[$uri];
    exit;
}

// If file path exists as a directory, check for index.php
if ($filePath !== false && is_dir($filePath) && is_file($filePath . '/index.php')) {
    require $filePath . '/index.php';
    exit;
}

// If no matching route, return 404
http_response_code(404);
require $publicDir . '/404.php';
exit;

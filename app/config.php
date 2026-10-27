<?php
/**
 * CTC Bank — Configuration and Environment Initialization
 * 
 * Commercial & Trust Capital Bank (Fictional Banking Simulation)
 * Part of PHP2Shell: The Diagnostic Gateway CTF Challenge
 */

// Application metadata
define('APP_NAME', 'Techtonic Bank');
define('APP_FULL_NAME', 'Techtonic Bank');
define('APP_TAGLINE', 'Enterprise Treasury & Infrastructure Portal');
define('APP_VERSION', '2.4.1-enterprise');
define('CHALLENGE_NAME', 'PHP2Shell — The Diagnostic Gateway');
define('CHALLENGE_FLAG_PATH', getenv('FLAG_PATH') ?: '/challenge/flag.txt');

// Paths
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Security & Error Handling
// Do not leak stack traces or server internals to standard visitors
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

// Session storage configuration
// In hardened containers with read-only rootfs, sessions must use writable tmpfs
$sessionDir = sys_get_temp_dir() . '/ctc_sessions';
if (!is_dir($sessionDir)) {
    @mkdir($sessionDir, 0700, true);
}
if (is_dir($sessionDir) && is_writable($sessionDir)) {
    session_save_path($sessionDir);
}

// Session security hardening
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', '7200');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

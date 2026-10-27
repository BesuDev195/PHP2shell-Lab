<?php
/**
 * CTC Bank — Authentication and Session Security Module
 * 
 * Provides session-based demo authentication for the banking interface.
 * Implements session regeneration on login, secure cookie handling, and access controls.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

/**
 * Returns available demo user credentials
 * These are synthetic training accounts.
 */
function get_demo_users(): array {
    return [
        'admin' => [
            'username'      => 'admin',
            // Default password: Techtonic@Admin2026! (or CTC@Admin2026!)
            'password_hash' => '$2y$10$vG0q1hH/Jb2e7V2P2j1qkeM039g1.55H2Vw/yV83xRjYnL63eKkue',
            'display_name'  => 'Alexander Vance',
            'role'          => 'Infrastructure Security Administrator',
            'email'         => 'a.vance@techtonic-bank.internal',
            'department'    => 'Enterprise Systems & Core Infrastructure',
            'clearance'     => 'Level 4 (Infrastructure & Diagnostics)',
            'initials'      => 'AV',
            'is_admin'      => true
        ],
        'officer_chen' => [
            'username'      => 'officer_chen',
            // Default password: SecureBank123!
            'password_hash' => '$2y$10$J01O9R.u8qB6O6T8u7AveuLqE08m51P8dY2C8XwV1bQ7M1e4i5Nia',
            'display_name'  => 'Elena Chen',
            'role'          => 'Senior Treasury & Settlement Officer',
            'email'         => 'e.chen@techtonic-bank.internal',
            'department'    => 'Commercial Accounts & Settlement',
            'clearance'     => 'Level 2 (Treasury Operations)',
            'initials'      => 'EC',
            'is_admin'      => false
        ]
    ];
}

/**
 * Attempt authentication with username and password
 */
function attempt_login(string $username, string $password): bool {
    $username = trim($username);
    $users = get_demo_users();

    if (!isset($users[$username])) {
        // Timing mitigation
        password_verify($password, '$2y$10$e8w.x9L2mO1fH6u7Z5a4i.N1vE7rY8pQ0sT2uV3wX4yZ5a6b7c8d9');
        return false;
    }

    $userData = $users[$username];
    
    // Direct password verification
    // Supported credentials:
    // admin / Techtonic@Admin2026! or CTC@Admin2026!
    // officer_chen / SecureBank123!
    $valid = false;
    if ($username === 'admin' && ($password === 'Techtonic@Admin2026!' || $password === 'CTC@Admin2026!' || password_verify($password, $userData['password_hash']))) {
        $valid = true;
    } elseif ($username === 'officer_chen' && ($password === 'SecureBank123!' || password_verify($password, $userData['password_hash']))) {
        $valid = true;
    }

    if ($valid) {
        // Regenerate session ID to prevent session fixation attacks
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        $_SESSION['authenticated'] = true;
        $_SESSION['user_id'] = $userData['username'];
        $_SESSION['user'] = [
            'username'     => $userData['username'],
            'display_name' => $userData['display_name'],
            'role'         => $userData['role'],
            'email'        => $userData['email'],
            'department'   => $userData['department'],
            'clearance'    => $userData['clearance'],
            'initials'     => $userData['initials'],
            'is_admin'     => $userData['is_admin'],
            'login_time'   => time(),
            'login_ip'     => get_client_ip()
        ];

        return true;
    }

    return false;
}

/**
 * Destroy session and log out
 */
function logout(): void {
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }
}

/**
 * Check if the current visitor is authenticated
 */
function is_authenticated(): bool {
    return !empty($_SESSION['authenticated']) && !empty($_SESSION['user']);
}

/**
 * Retrieve current user profile
 */
function current_user(): ?array {
    return is_authenticated() ? $_SESSION['user'] : null;
}

/**
 * Check if current user is an admin
 */
function is_admin(): bool {
    return is_authenticated() && !empty($_SESSION['user']['is_admin']);
}

/**
 * Guard protected pages: redirect unauthenticated visitors to login
 */
function require_auth(): void {
    if (!is_authenticated()) {
        $uri = $_SERVER['REQUEST_URI'] ?? '/dashboard.php';
        header('Location: /login.php?redirect=' . urlencode($uri));
        exit;
    }
}

/**
 * Guard admin-only pages
 */
function require_admin(): void {
    require_auth();
    if (!is_admin()) {
        http_response_code(403);
        require_once PUBLIC_PATH . '/403.php';
        exit;
    }
}

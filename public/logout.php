<?php
/**
 * CTC Bank — Sign Out Handler
 */
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/auth.php';

logout();

// Start fresh session for flash message
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
set_flash('info', 'You have been securely signed out of your CTC Bank session.');

header('Location: /login.php');
exit;

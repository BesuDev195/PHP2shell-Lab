<?php
/**
 * CTC Bank — Portal Entrypoint
 */
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/auth.php';

if (is_authenticated()) {
    header('Location: /dashboard.php');
} else {
    header('Location: /login.php');
}
exit;

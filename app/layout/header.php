<?php
/**
 * CTC Bank — Main Layout Header
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../auth.php';

$user = current_user();
$pageTitle = $pageTitle ?? 'Portal';
$currentPage = $currentPage ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= e($pageTitle) ?> | <?= e(APP_NAME) ?> Commercial &amp; Trust Capital</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
    
    <!-- Application Stylesheets -->
    <link rel="stylesheet" href="/assets/css/bank.css">
    <?php if (isset($extraCss)): ?>
        <?php foreach ($extraCss as $cssFile): ?>
            <link rel="stylesheet" href="<?= e($cssFile) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="<?= !empty($bodyClass) ? e($bodyClass) : '' ?>">

<!-- Global Internal Notice Banner -->
<div class="simulation-banner" role="alert">
    <div class="simulation-banner-inner">
        <span class="simulation-badge">INTERNAL ACCESS</span>
        <span class="simulation-text">
            <strong>Techtonic Bank Enterprise Portal:</strong>
            Authorized personnel only. All customer accounts and ledger transactions are synthetic simulation records.
        </span>
    </div>
</div>

<div class="app-layout">
    <?php if (is_authenticated()): ?>
        <!-- Include Sidebar for Authenticated Views -->
        <?php include __DIR__ . '/sidebar.php'; ?>
    <?php endif; ?>

    <div class="app-main-content">
        <!-- Top Navigation Bar -->
        <header class="app-topbar">
            <div class="topbar-left">
                <?php if (is_authenticated()): ?>
                    <button type="button" class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Toggle navigation menu">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                <?php endif; ?>
                
                <div class="topbar-branding">
                    <span class="topbar-brand-title"><?= e(APP_FULL_NAME) ?></span>
                    <span class="topbar-breadcrumb-sep">/</span>
                    <span class="topbar-breadcrumb-curr"><?= e($pageTitle) ?></span>
                </div>
            </div>

            <div class="topbar-right">
                <div class="system-health-indicator">
                    <span class="health-pulse"></span>
                    <span class="health-label">Gateway: Active</span>
                </div>

                <?php if ($user): ?>
                    <div class="user-profile-menu">
                        <div class="user-avatar" title="<?= e($user['display_name']) ?> (<?= e($user['role']) ?>)">
                            <?= e($user['initials']) ?>
                        </div>
                        <div class="user-details">
                            <span class="user-name"><?= e($user['display_name']) ?></span>
                            <span class="user-badge <?= $user['is_admin'] ? 'badge-admin' : 'badge-officer' ?>">
                                <?= $user['is_admin'] ? 'Administrator' : 'Officer' ?>
                            </span>
                        </div>
                        <a href="/logout.php" class="btn-logout-icon" title="Sign out of banking session" aria-label="Sign out">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="guest-nav">
                        <a href="/login.php" class="btn btn-outline btn-sm">Sign In</a>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Flash messages / Notifications -->
        <?php $flashMessages = get_flash_messages(); ?>
        <?php if (!empty($flashMessages)): ?>
            <div class="flash-messages-container">
                <?php foreach ($flashMessages as $flash): ?>
                    <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible" role="alert">
                        <div class="alert-content">
                            <?= e($flash['message']) ?>
                        </div>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Dismiss message">&times;</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Page Content Starts Here -->
        <main class="page-container" id="mainContent">

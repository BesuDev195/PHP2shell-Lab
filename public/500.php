<?php
/**
 * Techtonic Bank — 500 Internal Server Error Page
 */
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/auth.php';

http_response_code(500);
$pageTitle = '500 Internal Error — Infrastructure Exception';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | <?= e(APP_NAME) ?></title>
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
    <link rel="stylesheet" href="/assets/css/bank.css">
    <style>
        .error-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .error-card {
            background: #ffffff;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-card);
            max-width: 520px;
            width: 100%;
            padding: 40px;
            text-align: center;
        }
        .error-code {
            font-size: 4rem;
            font-weight: 800;
            color: var(--navy-800);
            line-height: 1;
            margin-bottom: 8px;
            letter-spacing: -0.04em;
        }
        .error-badge {
            display: inline-block;
            background: var(--color-danger-bg);
            color: var(--color-danger);
            border: 1px solid var(--color-danger-border);
            font-weight: 700;
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 16px;
        }
        .error-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--navy-900);
            margin-bottom: 12px;
        }
        .error-text {
            color: var(--text-secondary);
            font-size: 0.9375rem;
            line-height: 1.6;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
<div class="simulation-banner">
    <div class="simulation-banner-inner">
        <span class="simulation-badge">INFRASTRUCTURE EXCEPTION</span>
        <span class="simulation-text">Techtonic Bank Internal System Event</span>
    </div>
</div>

<div class="error-container">
    <div class="error-card">
        <div class="error-code">500</div>
        <div class="error-badge">System Exception</div>
        <h1 class="error-title">Internal Server Exception</h1>
        <p class="error-text">
            An internal server exception occurred while processing this transaction. For security reasons, detailed system traces and debug parameters are restricted.
        </p>
        <div style="display: flex; gap: 12px; justify-content: center;">
            <a href="/dashboard.php" class="btn btn-primary">Return to Dashboard</a>
            <a href="/login.php" class="btn btn-outline">Corporate Sign In</a>
        </div>
    </div>
</div>
</body>
</html>

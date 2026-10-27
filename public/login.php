<?php
/**
 * CTC Bank — Professional Corporate Login Page
 * Fictional banking portal authentication simulation.
 */
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/auth.php';

// Redirect if already logged in
if (is_authenticated()) {
    header('Location: /dashboard.php');
    exit;
}

$errorMessage = '';
$usernameVal = '';
$redirectUrl = $_GET['redirect'] ?? '/dashboard.php';

// Sanitize redirect URL to prevent open redirect
if (!str_starts_with($redirectUrl, '/') || str_starts_with($redirectUrl, '//')) {
    $redirectUrl = '/dashboard.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameVal = trim($_POST['username'] ?? '');
    $passwordVal = $_POST['password'] ?? '';

    if (empty($usernameVal) || empty($passwordVal)) {
        $errorMessage = 'Please provide both your corporate username and authentication credentials.';
    } else {
        if (attempt_login($usernameVal, $passwordVal)) {
            set_flash('success', 'Authentication successful. Welcome to Techtonic Bank Enterprise Treasury.');
            header('Location: ' . $redirectUrl);
            exit;
        } else {
            $errorMessage = 'Invalid username or password. Please verify your credentials or contact the Techtonic Bank Helpdesk.';
        }
    }
}

$pageTitle = 'Corporate Sign In';
$bodyClass = 'login-page-body';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | <?= e(APP_NAME) ?> Commercial &amp; Trust Capital</title>
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
    <link rel="stylesheet" href="/assets/css/bank.css">
    <style>
        .login-page-body {
            background: linear-gradient(135deg, var(--navy-950) 0%, var(--navy-900) 50%, var(--navy-850) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }
        .login-card {
            width: 100%;
            max-width: 460px;
            background: #ffffff;
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(180deg, var(--navy-900) 0%, var(--navy-850) 100%);
            padding: 36px 32px 28px;
            text-align: center;
            border-bottom: 3px solid var(--gold-500);
        }
        .login-logo {
            height: 52px;
            width: auto;
            margin-bottom: 12px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }
        .login-title {
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }
        .login-subtitle {
            color: #94a3b8;
            font-size: 0.8125rem;
            margin-top: 4px;
        }
        .login-body {
            padding: 32px;
        }
        .demo-credentials-card {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: 16px;
            margin-top: 24px;
        }
        .demo-cred-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--navy-800);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .demo-cred-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8125rem;
            padding: 6px 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        .demo-cred-row:last-child {
            border-bottom: none;
        }
        .demo-cred-user {
            font-family: var(--font-mono);
            font-weight: 600;
            color: var(--navy-700);
        }
        .btn-fill-cred {
            background: rgba(197, 155, 39, 0.12);
            color: var(--gold-600);
            border: 1px solid var(--gold-border);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-fill-cred:hover {
            background: var(--gold-500);
            color: #060e1a;
        }
        .login-footer-text {
            text-align: center;
            font-size: 0.75rem;
            color: #94a3b8;
            padding: 16px;
        }
    </style>
</head>
<body class="login-page-body">

<!-- Global Security Notice -->
<div class="simulation-banner" role="alert">
    <div class="simulation-banner-inner">
        <span class="simulation-badge">AUTHENTICATION GATEWAY</span>
        <span class="simulation-text">
            <strong>Techtonic Bank Enterprise Portal:</strong> Authorized personnel only. All customer accounts and records are synthetic simulation entries.
        </span>
    </div>
</div>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="/assets/images/logo.svg" alt="Techtonic Bank Logo" class="login-logo">
            <h1 class="login-title">Enterprise Treasury Portal</h1>
            <p class="login-subtitle">Sign in to access corporate accounts and diagnostic infrastructure</p>
        </div>

        <div class="login-body">
            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= e($errorMessage) ?>
                </div>
            <?php endif; ?>

            <form action="/login.php?redirect=<?= urlencode($redirectUrl) ?>" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="usernameInput" class="form-label">Corporate Username <span class="required">*</span></label>
                    <input type="text" name="username" id="usernameInput" class="form-control" 
                           value="<?= e($usernameVal) ?>" required autocomplete="username" 
                           placeholder="e.g. admin or officer_chen">
                </div>

                <div class="form-group">
                    <label for="passwordInput" class="form-label">Password <span class="required">*</span></label>
                    <input type="password" name="password" id="passwordInput" class="form-control" 
                           required autocomplete="current-password" 
                           placeholder="Enter your security password">
                </div>

                <div class="form-group" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-gold btn-block btn-lg" id="btnLoginSubmit">
                        Secure Authentication &rarr;
                    </button>
                </div>
            </form>

            <!-- Demo Credentials Helper -->
            <div class="demo-credentials-card">
                <div class="demo-cred-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    Authorized Lab Demo Credentials
                </div>
                
                <div class="demo-cred-row">
                    <div>
                        <span class="demo-cred-user">admin</span>
                        <span style="color: #64748b; font-size: 0.75rem;">(Infrastructure Administrator)</span>
                    </div>
                    <button type="button" class="btn-fill-cred" onclick="fillCreds('admin', 'Techtonic@Admin2026!')">
                        Auto Fill
                    </button>
                </div>

                <div class="demo-cred-row">
                    <div>
                        <span class="demo-cred-user">officer_chen</span>
                        <span style="color: #64748b; font-size: 0.75rem;">(Treasury Officer)</span>
                    </div>
                    <button type="button" class="btn-fill-cred" onclick="fillCreds('officer_chen', 'SecureBank123!')">
                        Auto Fill
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="login-footer-text">
    &copy; <?= date('Y') ?> Techtonic Bank. Enterprise Security Sandbox. Fictional Training Simulation.
</div>

<script>
function fillCreds(u, p) {
    document.getElementById('usernameInput').value = u;
    document.getElementById('passwordInput').value = p;
    document.getElementById('passwordInput').focus();
}
</script>

</body>
</html>

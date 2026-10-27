<?php
/**
 * Techtonic Bank — Security Assessment Token Validator
 * Route: /challenge.php
 */
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/auth.php';

$pageTitle = 'Assessment Token Verification';
$currentPage = 'challenge';

$flagCheckResult = null;
$submittedFlag = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['flag_submission'])) {
    $submittedFlag = trim($_POST['flag_submission']);
    
    // Read the actual flag from the secure path inside container if present
    $flagPath = CHALLENGE_FLAG_PATH;
    $actualFlag = '';
    if (is_file($flagPath) && is_readable($flagPath)) {
        $actualFlag = trim(file_get_contents($flagPath));
    } else {
        $actualFlag = 'techtonic{c0mm4nd_1nj3ct10n_php2sh3ll_g4t3w4y_3xpl01t}';
    }

    if (!empty($submittedFlag)) {
        if (hash_equals($actualFlag, $submittedFlag) || hash_equals(strtolower($actualFlag), strtolower($submittedFlag))) {
            $flagCheckResult = [
                'status'  => 'success',
                'message' => 'Token Verified Successfully! The submitted security token matches the target lab environment.'
            ];
        } else {
            $flagCheckResult = [
                'status'  => 'error',
                'message' => 'Token Verification Failed: The submitted token is invalid.'
            ];
        }
    } else {
        $flagCheckResult = [
            'status'  => 'error',
            'message' => 'Please enter a token before submitting.'
        ];
    }
}

include __DIR__ . '/../app/layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Assessment Verification</h1>
        <p class="page-subtitle">Submit captured proof tokens from authorized security audits to verify completion</p>
    </div>
    <div>
        <a href="/dashboard.php" class="btn btn-outline btn-sm">
            &larr; Return to Dashboard
        </a>
    </div>
</div>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="card" style="border-top: 4px solid var(--brand-lime);">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                    <line x1="4" y1="22" x2="4" y2="15"></line>
                </svg>
                Proof Token Validator
            </h2>
        </div>
        <div class="card-body">
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 20px; line-height: 1.5;">
                Enter the proof flag captured during authorized testing against the Techtonic Bank sandbox environment:
            </p>

            <?php if ($flagCheckResult): ?>
                <div class="alert alert-<?= e($flagCheckResult['status']) ?>" style="font-size: 0.875rem; margin-bottom: 20px;">
                    <?= e($flagCheckResult['message']) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/challenge.php">
                <div class="form-group">
                    <label for="flagInput" class="form-label">Captured Flag Token <span class="required">*</span></label>
                    <input type="text" 
                           name="flag_submission" 
                           id="flagInput" 
                           class="form-control" 
                           placeholder="techtonic{...}" 
                           value="<?= e($submittedFlag) ?>"
                           required
                           autocomplete="off"
                           autofocus>
                </div>
                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-gold btn-block">
                        Verify Token Submission &rarr;
                    </button>
                </div>
            </form>
        </div>
        <div class="card-footer" style="font-size: 0.75rem; color: var(--text-muted); text-align: center;">
            Authorized Security Assessment Verification Endpoint
        </div>
    </div>
</div>

<?php include __DIR__ . '/../app/layout/footer.php'; ?>

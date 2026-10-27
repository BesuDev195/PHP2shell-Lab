<?php
/**
 * Techtonic Bank — Infrastructure Diagnostics Gateway
 * 
 * Route: /admin/diagnostics.php
 * Administrative utility for testing connectivity to internal microservices.
 */
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/functions.php';
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/demo_data.php';
require_once __DIR__ . '/../../app/diagnostic.php';

require_auth();

$user = current_user();
$diagResult = null;
$targetInput = '';
$diagHistory = get_demo_diagnostics_history();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetInput = $_POST['target'] ?? '';
    
    // Execute diagnostic probe via the diagnostic engine
    $diagResult = execute_diagnostic($targetInput);

    // Prepend the new execution to history for current view
    if ($diagResult['success'] && !empty($targetInput)) {
        array_unshift($diagHistory, [
            'id'          => 'DIAG-' . rand(8850, 9999),
            'timestamp'   => date('Y-m-d H:i:s'),
            'operator'    => e($user['username']) . ' (' . ($user['is_admin'] ? 'Admin' : 'Officer') . ')',
            'target'      => substr($targetInput, 0, 40),
            'tool'        => 'ICMP Echo Probe',
            'status'      => ($diagResult['exit_code'] === 0) ? 'Completed' : 'Probe Warning',
            'latency'     => $diagResult['execution_time'] . ' ms',
            'summary'     => 'Execution completed with exit code ' . $diagResult['exit_code']
        ]);
    }
}

$pageTitle = 'Infrastructure Diagnostics';
$currentPage = 'diagnostics';
$extraCss = ['/assets/css/diagnostics.css'];
$extraJs = ['/assets/js/diagnostics.js'];

include __DIR__ . '/../../app/layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Infrastructure Diagnostics</h1>
        <p class="page-subtitle">Network reachability and latency verification for internal banking infrastructure</p>
    </div>
    <div>
        <span class="badge badge-success"><span class="badge-dot" style="background:#10B981"></span>Diagnostics Engine Online</span>
    </div>
</div>

<!-- Diagnostic Execution Card -->
<div class="card diag-form-card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="4 17 10 11 4 5"></polyline>
                    <line x1="12" y1="19" x2="20" y2="19"></line>
                </svg>
                Network Reachability Probe
            </h2>
            <span class="card-subtitle">Dispatch ICMP echo packets to verify network routing and responsiveness</span>
        </div>
        <span class="badge badge-secondary">ICMP Service</span>
    </div>

    <div class="card-body">
        <form method="POST" action="/admin/diagnostics.php" id="diagForm">
            <div class="form-group">
                <label for="diagTargetInput" class="form-label">
                    Target Hostname or IP Address <span class="required">*</span>
                </label>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 260px;">
                        <input type="text" 
                               name="target" 
                               id="diagTargetInput" 
                               class="form-control" 
                               value="<?= e($targetInput) ?>" 
                               required 
                               placeholder="e.g. 127.0.0.1 or core-gateway.techtonic.local"
                               autocomplete="off"
                               autofocus>
                    </div>
                    <button type="submit" class="btn btn-gold" id="btnRunDiagnostic" style="min-width: 170px;">
                        <span id="runBtnSpinner" style="display: none; width: 14px; height: 14px; border: 2px solid #000; border-top-color: transparent; border-radius: 50%; animation: spin 0.8s linear infinite; margin-right: 6px;"></span>
                        <span id="runBtnText">Run Diagnostic</span>
                    </button>
                </div>

                <!-- Internal Target Presets -->
                <div class="target-presets">
                    <span class="preset-label">Internal Hosts:</span>
                    <button type="button" class="btn-preset" data-target-preset="127.0.0.1">127.0.0.1 (Loopback)</button>
                    <button type="button" class="btn-preset" data-target-preset="core-gateway.techtonic.local">core-gateway.techtonic.local</button>
                    <button type="button" class="btn-preset" data-target-preset="db-cluster.internal">db-cluster.internal</button>
                    <button type="button" class="btn-preset" data-target-preset="auth-vault-01.internal">auth-vault-01.internal</button>
                </div>
                <div class="form-hint">
                    Enter an internal hostname or IP address to verify routing latency across core banking subnets.
                </div>
            </div>
        </form>

        <!-- Probe Results Output Viewer -->
        <div class="probe-output-card" aria-live="polite">
            <div class="probe-output-header">
                <div class="probe-output-title">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="4 17 10 11 4 5"></polyline>
                        <line x1="12" y1="19" x2="20" y2="19"></line>
                    </svg>
                    <span>Probe Telemetry Output</span>
                </div>
                <?php if ($diagResult !== null): ?>
                    <div class="probe-output-meta">
                        <span class="probe-status-pill <?= $diagResult['exit_code'] === 0 ? 'success' : 'warn' ?>">
                            <?= $diagResult['exit_code'] === 0 ? 'Return 0 (OK)' : 'Return ' . e((string)$diagResult['exit_code']) ?>
                        </span>
                        <span class="probe-latency-pill"><?= e((string)$diagResult['execution_time']) ?> ms</span>
                        <button type="button" class="terminal-btn-copy" id="btnCopyTerminal">Copy Output</button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="probe-output-body">
                <?php if ($diagResult !== null): ?>
                    <pre class="probe-output-text" id="terminalOutputContent"><?= e($diagResult['output']) ?></pre>
                <?php else: ?>
                    <div class="probe-output-empty">
                        Specify a target host or address above and dispatch a probe to review response telemetry.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Diagnostic History Table -->
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Diagnostic Telemetry History</h3>
            <span class="card-subtitle">Recent infrastructure probe records and latency benchmarks</span>
        </div>
        <span class="badge badge-secondary"><?= count($diagHistory) ?> Log Entries</span>
    </div>
    <div class="table-wrapper" tabindex="0" role="region" aria-label="Diagnostic History">
        <table class="table">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Timestamp</th>
                    <th>Operator</th>
                    <th>Target</th>
                    <th>Probe Type</th>
                    <th>Latency</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($diagHistory as $entry): ?>
                    <tr>
                        <td style="font-family: var(--font-mono); font-size: 0.8125rem; font-weight: 600;">
                            <?= e($entry['id']) ?>
                        </td>
                        <td style="font-size: 0.8125rem; color: var(--text-secondary); white-space: nowrap;">
                            <?= e($entry['timestamp']) ?>
                        </td>
                        <td style="font-size: 0.8125rem; font-weight: 500;">
                            <?= e($entry['operator']) ?>
                        </td>
                        <td style="font-family: var(--font-mono); font-size: 0.8125rem; color: var(--navy-800);">
                            <?= e($entry['target']) ?>
                        </td>
                        <td style="font-size: 0.8125rem; color: var(--text-muted);">
                            <?= e($entry['tool']) ?>
                        </td>
                        <td style="font-size: 0.8125rem; font-weight: 600; color: var(--color-info);">
                            <?= e($entry['latency']) ?>
                        </td>
                        <td>
                            <span class="badge badge-success">
                                <span class="badge-dot" style="background:#10B981"></span><?= e($entry['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<?php include __DIR__ . '/../../app/layout/footer.php'; ?>

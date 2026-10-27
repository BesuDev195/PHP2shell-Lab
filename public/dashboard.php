<?php
/**
 * CTC Bank — Banking Dashboard
 * Enterprise Treasury Overview
 */
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/demo_data.php';

require_auth();

$user = current_user();
$accounts = get_demo_accounts();
$primaryAccount = $accounts['ACC-4892'];
$transactions = get_demo_transactions();
$recentTransactions = array_slice($transactions, 0, 5);

// Calculate total balance across accounts
$totalAssets = 0.0;
foreach ($accounts as $acc) {
    $totalAssets += $acc['balance'];
}

$pageTitle = 'Treasury Dashboard';
$currentPage = 'dashboard';

include __DIR__ . '/../app/layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Enterprise Treasury Overview</h1>
        <p class="page-subtitle">Welcome back, <strong><?= e($user['display_name']) ?></strong> &bull; <?= e($user['department']) ?></p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        <a href="/accounts.php" class="btn btn-outline btn-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                <line x1="2" y1="10" x2="22" y2="10"></line>
            </svg>
            All Accounts (<?= count($accounts) ?>)
        </a>
        <a href="/admin/diagnostics.php" class="btn btn-gold btn-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="4 17 10 11 4 5"></polyline>
                <line x1="12" y1="19" x2="20" y2="19"></line>
            </svg>
            Infrastructure Diagnostics
        </a>
    </div>
</div>

<!-- Summary Metrics Cards Grid -->
<div class="metrics-grid">
    <div class="metric-card metric-card-gold">
        <div class="metric-label">Total Consolidated Liquidity</div>
        <div class="metric-value metric-value-gold"><?= format_currency($totalAssets) ?></div>
        <div class="metric-sub">
            <span class="metric-trend-up">&uarr; 3.8%</span>
            <span>across 4 corporate accounts</span>
        </div>
    </div>

    <div class="metric-card metric-card-navy">
        <div class="metric-label">Primary Operating Balance</div>
        <div class="metric-value"><?= format_currency($primaryAccount['balance']) ?></div>
        <div class="metric-sub">
            <span>Account: <strong><?= mask_account_number($primaryAccount['account_number']) ?></strong></span>
        </div>
    </div>

    <div class="metric-card metric-card-success">
        <div class="metric-label">30-Day Inbound Settlements</div>
        <div class="metric-value" style="color: var(--color-success);">+$2,620,140.00</div>
        <div class="metric-sub">
            <span class="metric-trend-up">&uarr; 12.4%</span>
            <span>vs previous cycle</span>
        </div>
    </div>

    <div class="metric-card metric-card-info">
        <div class="metric-label">Compliance &amp; Security Index</div>
        <div class="metric-value" style="color: var(--navy-800);">99.4%</div>
        <div class="metric-sub">
            <span style="color: var(--color-success); font-weight: 600;">&bull; SOC2 Type II</span>
            <span>Audit Validated</span>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;" class="dashboard-split">
    <!-- Left Column: Primary Account & Recent Transactions -->
    <div>
        <!-- Primary Operating Account Card -->
        <div class="card" style="border-top: 4px solid var(--navy-800);">
            <div class="card-header">
                <div>
                    <h2 class="card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                        </svg>
                        <?= e($primaryAccount['name']) ?>
                    </h2>
                    <span class="card-subtitle"><?= e($primaryAccount['type']) ?> &bull; <?= e($primaryAccount['tier']) ?></span>
                </div>
                <?= render_status_badge($primaryAccount['status']) ?>
            </div>
            <div class="card-body">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <div style="font-size: 0.8125rem; color: var(--text-muted); text-transform: uppercase;">Current Settled Balance</div>
                        <div style="font-size: 2.1rem; font-weight: 800; color: var(--navy-900); font-variant-numeric: tabular-nums;">
                            <?= format_currency($primaryAccount['balance']) ?>
                        </div>
                        <div style="font-size: 0.8125rem; color: var(--text-secondary); margin-top: 4px;">
                            Available for transfer: <strong><?= format_currency($primaryAccount['available']) ?></strong>
                        </div>
                    </div>
                    <div style="background: var(--bg-surface-alt); padding: 14px 18px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); font-size: 0.8125rem;">
                        <div style="margin-bottom: 6px;">
                            <span style="color: var(--text-muted);">Account Number:</span>
                            <strong style="font-family: var(--font-mono); color: var(--navy-900); margin-left: 6px;"><?= mask_account_number($primaryAccount['account_number']) ?></strong>
                        </div>
                        <div style="margin-bottom: 6px;">
                            <span style="color: var(--text-muted);">Routing (ABA):</span>
                            <span style="font-family: var(--font-mono); color: var(--navy-800); margin-left: 6px;"><?= e($primaryAccount['routing_number']) ?></span>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">IBAN:</span>
                            <span style="font-family: var(--font-mono); color: var(--navy-800); margin-left: 6px;"><?= e($primaryAccount['iban']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Card -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Recent Settled Transactions</h2>
                    <span class="card-subtitle">Showing latest 5 synthetic transactions</span>
                </div>
                <a href="/transactions.php" class="btn btn-outline btn-sm">Full Transaction Ledger &rarr;</a>
            </div>
            <div class="table-wrapper" tabindex="0" role="region" aria-label="Recent Transactions">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th style="text-align: right;">Amount (USD)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentTransactions as $txn): ?>
                            <tr>
                                <td style="font-family: var(--font-mono); font-size: 0.8125rem; font-weight: 600;">
                                    <?= e($txn['ref']) ?>
                                </td>
                                <td style="font-size: 0.8125rem; color: var(--text-secondary); white-space: nowrap;">
                                    <?= e(date('M d, H:i', strtotime($txn['date']))) ?>
                                </td>
                                <td style="font-weight: 500;">
                                    <?= e($txn['description']) ?>
                                </td>
                                <td style="font-size: 0.8125rem; color: var(--text-muted);">
                                    <?= e($txn['category']) ?>
                                </td>
                                <td style="text-align: right;" class="amount-cell <?= $txn['amount'] >= 0 ? 'amount-positive' : 'amount-negative' ?>">
                                    <?= format_currency($txn['amount']) ?>
                                </td>
                                <td>
                                    <?= render_status_badge($txn['status']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Administrative Tools & Security Card -->
    <div>
        <!-- Infrastructure Diagnostics Card -->
        <div class="card" style="border-top: 4px solid var(--brand-lime);">
            <div class="card-header">
                <div>
                    <h3 class="card-title" style="color: var(--navy-900);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="4 17 10 11 4 5"></polyline>
                            <line x1="12" y1="19" x2="20" y2="19"></line>
                        </svg>
                        Infrastructure Diagnostics
                    </h3>
                    <span class="card-subtitle">Internal service latency and network routing probes</span>
                </div>
            </div>
            <div class="card-body">
                <p style="font-size: 0.875rem; color: #475569; margin-bottom: 16px; line-height: 1.5;">
                    The Techtonic Bank operations team maintains internal diagnostic utilities to verify packet reachability and response times across core banking switches, settlement nodes, and database clusters.
                </p>
                <a href="/admin/diagnostics.php" class="btn btn-gold btn-block">
                    Launch Diagnostics Suite &rarr;
                </a>
            </div>
        </div>

        <!-- System & Security Health Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    Security &amp; Perimeter Health
                </h3>
            </div>
            <div class="card-body" style="padding-top: 16px;">
                <div style="display: flex; flex-direction: column; gap: 14px; font-size: 0.84rem;">
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                        <span style="color: var(--text-muted);">Session IP Address</span>
                        <strong style="font-family: var(--font-mono); color: var(--navy-800);"><?= e(get_client_ip()) ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                        <span style="color: var(--text-muted);">Infrastructure Tier</span>
                        <strong style="color: var(--navy-800);"><?= e($user['clearance']) ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                        <span style="color: var(--text-muted);">Diagnostic Gateway</span>
                        <span class="badge badge-success"><span class="badge-dot" style="background:#10B981"></span>Online (/admin)</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                        <span style="color: var(--text-muted);">Security State</span>
                        <span style="color: var(--color-success); font-weight: 600;">Verified (Encrypted Session)</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Perimeter Audit</span>
                        <span style="color: var(--navy-800); font-weight: 600;">Automated Guard Active</span>
                    </div>
                </div>
            </div>
            <div class="card-footer" style="font-size: 0.75rem; color: var(--text-muted);">
                Audit Log ID: <code>SEC-AUD-<?= strtoupper(substr(md5(session_id()), 0, 8)) ?></code>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 900px) {
    .dashboard-split {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php include __DIR__ . '/../app/layout/footer.php'; ?>

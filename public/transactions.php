<?php
/**
 * CTC Bank — Transactions Ledger Page
 * Detailed synthetic transaction ledger with filtering and searching.
 */
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/demo_data.php';

require_auth();

$transactions = get_demo_transactions();

$pageTitle = 'Transactions Ledger';
$currentPage = 'transactions';

// Calculate summary numbers
$inflow = 0.0;
$outflow = 0.0;
$pendingCount = 0;
foreach ($transactions as $t) {
    if ($t['amount'] > 0) {
        $inflow += $t['amount'];
    } else {
        $outflow += abs($t['amount']);
    }
    if ($t['status'] === 'pending') {
        $pendingCount++;
    }
}

include __DIR__ . '/../app/layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Transactions Ledger</h1>
        <p class="page-subtitle">Real-time audit ledger of all corporate settlement and wire transfers</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <button type="button" class="btn btn-outline btn-sm" onclick="window.print()">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Print Ledger
        </button>
    </div>
</div>

<!-- Ledger Quick Stats -->
<div class="metrics-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 24px;">
    <div class="metric-card metric-card-success">
        <div class="metric-label">Cycle Inbound Inflow</div>
        <div class="metric-value" style="color: var(--color-success); font-size: 1.5rem;"><?= format_currency($inflow) ?></div>
        <div class="metric-sub">Commercial deposits &amp; settlements</div>
    </div>

    <div class="metric-card metric-card-navy">
        <div class="metric-label">Cycle Outbound Outflow</div>
        <div class="metric-value" style="font-size: 1.5rem;"><?= format_currency($outflow) ?></div>
        <div class="metric-sub">Wires, invoices, &amp; payroll</div>
    </div>

    <div class="metric-card metric-card-gold">
        <div class="metric-label">Total Transactions</div>
        <div class="metric-value metric-value-gold" style="font-size: 1.5rem;"><?= count($transactions) ?> Records</div>
        <div class="metric-sub"><?= $pendingCount ?> pending verification</div>
    </div>
</div>

<!-- Main Ledger Card -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Settlement Audit Records</h2>
            <span class="card-subtitle">Showing all synthetic corporate transactions</span>
        </div>
    </div>
    <div class="card-body">
        <!-- Search and Filter Toolbar -->
        <div class="filter-toolbar">
            <div class="search-input-group" style="min-width: 300px; flex: 1;">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" class="form-control" placeholder="Search reference, description, account..." 
                       data-table-search="#ledgerTable" id="ledgerSearchInput">
            </div>

            <div class="filter-group">
                <select class="form-control" data-table-filter="#ledgerTable" data-filter-column="5">
                    <option value="all">All Statuses</option>
                    <option value="completed">Completed</option>
                    <option value="cleared">Cleared</option>
                    <option value="pending">Pending</option>
                    <option value="flagged">Flagged</option>
                </select>

                <select class="form-control" data-table-filter="#ledgerTable" data-filter-column="4">
                    <option value="all">All Categories</option>
                    <option value="Wire Transfer">Wire Transfer</option>
                    <option value="Inbound Clearing">Inbound Clearing</option>
                    <option value="Payroll">Payroll</option>
                    <option value="Vendor Operations">Vendor Operations</option>
                    <option value="Deposit">Deposit</option>
                    <option value="Treasury FX">Treasury FX</option>
                </select>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="table-wrapper" tabindex="0" role="region" aria-label="Transactions Ledger">
            <table class="table" id="ledgerTable">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Date &amp; Time</th>
                        <th>Account</th>
                        <th>Description / Beneficiary</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th style="text-align: right;">Amount (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $txn): ?>
                        <tr>
                            <td style="font-family: var(--font-mono); font-size: 0.8125rem; font-weight: 600;">
                                <span style="color: var(--navy-800);"><?= e($txn['ref']) ?></span>
                            </td>
                            <td style="font-size: 0.8125rem; color: var(--text-secondary); white-space: nowrap;">
                                <?= e(format_date($txn['date'])) ?>
                            </td>
                            <td style="font-size: 0.8125rem; color: var(--text-main); font-weight: 500;">
                                <?= e($txn['account']) ?>
                            </td>
                            <td style="font-weight: 500;">
                                <?= e($txn['description']) ?>
                            </td>
                            <td style="font-size: 0.8125rem; color: var(--text-muted);">
                                <?= e($txn['category']) ?>
                            </td>
                            <td>
                                <?= render_status_badge($txn['status']) ?>
                            </td>
                            <td style="text-align: right;" class="amount-cell <?= $txn['amount'] >= 0 ? 'amount-positive' : 'amount-negative' ?>">
                                <?= format_currency($txn['amount']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../app/layout/footer.php'; ?>

<?php
/**
 * CTC Bank — Accounts Portfolio Page
 * Fictional commercial accounts and balance management.
 */
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/demo_data.php';

require_auth();

$accounts = get_demo_accounts();
$transactions = get_demo_transactions();

$pageTitle = 'Accounts Portfolio';
$currentPage = 'accounts';

include __DIR__ . '/../app/layout/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Corporate Accounts Portfolio</h1>
        <p class="page-subtitle">Commercial treasury deposits, liquidity reserves, and escrow facilities</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="/transactions.php" class="btn btn-outline btn-sm">Full Transaction Ledger &rarr;</a>
    </div>
</div>

<!-- Accounts Cards Grid -->
<div class="metrics-grid" style="margin-bottom: 32px;">
    <?php foreach ($accounts as $accId => $acc): ?>
        <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column;">
            <div class="card-header" style="padding: 16px 20px;">
                <div>
                    <h3 style="font-size: 0.9375rem; font-weight: 700; color: var(--navy-900);"><?= e($acc['name']) ?></h3>
                    <span style="font-size: 0.75rem; color: var(--text-muted);"><?= e($acc['type']) ?></span>
                </div>
                <?= render_status_badge($acc['status']) ?>
            </div>
            <div class="card-body" style="padding: 20px; flex: 1;">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Current Settled Balance</div>
                <div style="font-size: 1.625rem; font-weight: 800; color: var(--navy-900); font-variant-numeric: tabular-nums; margin: 4px 0 12px;">
                    <?= format_currency($acc['balance']) ?>
                </div>

                <div style="background: var(--bg-surface-alt); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 12px; font-size: 0.8125rem; display: flex; flex-direction: column; gap: 6px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Account #:</span>
                        <span style="font-family: var(--font-mono); font-weight: 600;"><?= mask_account_number($acc['account_number']) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Routing:</span>
                        <span style="font-family: var(--font-mono);"><?= e($acc['routing_number']) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Available:</span>
                        <span style="font-weight: 600; color: var(--color-success);"><?= format_currency($acc['available']) ?></span>
                    </div>
                </div>
            </div>
            <div class="card-footer" style="padding: 12px 20px; font-size: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
                <span style="color: var(--text-muted);"><?= e($acc['tier']) ?></span>
                <button type="button" class="btn btn-outline btn-sm" onclick="filterByAccount('<?= e($acc['name']) ?>')">
                    Filter Ledger &darr;
                </button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Local Synthetic Transactions Search & Filtering -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Account Activity &amp; Records</h2>
            <span class="card-subtitle">Search and filter synthetic transactions across portfolio accounts</span>
        </div>
    </div>
    <div class="card-body">
        <div class="filter-toolbar">
            <div class="search-input-group" style="min-width: 280px; flex: 1;">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" class="form-control" placeholder="Search by description, reference, or category..." 
                       id="accountTxnSearch" data-table-search="#accountsTxnTable">
            </div>

            <div class="filter-group">
                <select class="form-control" id="accountSelectFilter" data-table-filter="#accountsTxnTable" data-filter-column="2">
                    <option value="all">All Accounts</option>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?= e($acc['name']) ?>"><?= e($acc['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <select class="form-control" data-table-filter="#accountsTxnTable" data-filter-column="5">
                    <option value="all">All Statuses</option>
                    <option value="completed">Completed</option>
                    <option value="cleared">Cleared</option>
                    <option value="pending">Pending</option>
                    <option value="flagged">Flagged</option>
                </select>
            </div>
        </div>

        <div class="table-wrapper" tabindex="0" role="region" aria-label="Account Activity">
            <table class="table" id="accountsTxnTable">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Date &amp; Time</th>
                        <th>Associated Account</th>
                        <th>Description / Counterparty</th>
                        <th>Category</th>
                        <th style="text-align: right;">Amount (USD)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $txn): ?>
                        <tr>
                            <td style="font-family: var(--font-mono); font-size: 0.8125rem; font-weight: 600;">
                                <?= e($txn['ref']) ?>
                            </td>
                            <td style="font-size: 0.8125rem; color: var(--text-secondary); white-space: nowrap;">
                                <?= e(format_date($txn['date'])) ?>
                            </td>
                            <td style="font-size: 0.8125rem; font-weight: 600; color: var(--navy-800);">
                                <?= e($txn['account']) ?>
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

<script>
function filterByAccount(accName) {
    const sel = document.getElementById('accountSelectFilter');
    if (sel) {
        sel.value = accName;
        sel.dispatchEvent(new Event('change'));
        document.getElementById('accountsTxnTable').scrollIntoView({ behavior: 'smooth' });
    }
}
</script>

<?php include __DIR__ . '/../app/layout/footer.php'; ?>

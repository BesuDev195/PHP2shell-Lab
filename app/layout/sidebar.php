<?php
/**
 * CTC Bank — Main Navigation Sidebar
 */
$currentPage = $currentPage ?? '';
$user = current_user();
?>
<aside class="app-sidebar" id="appSidebar" aria-label="Main Navigation">
    <div class="sidebar-header">
        <a href="/dashboard.php" class="sidebar-brand">
            <img src="/assets/images/logo.svg" alt="Techtonic Bank Logo" class="sidebar-logo">
        </a>
    </div>

    <div class="sidebar-section-label">COMMERCIAL BANKING</div>
    <nav class="sidebar-nav">
        <a href="/dashboard.php" class="nav-item <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
            <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <span class="nav-text">Treasury Dashboard</span>
        </a>

        <a href="/accounts.php" class="nav-item <?= $currentPage === 'accounts' ? 'active' : '' ?>">
            <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                <line x1="2" y1="10" x2="22" y2="10"></line>
            </svg>
            <span class="nav-text">Accounts Portfolio</span>
        </a>

        <a href="/transactions.php" class="nav-item <?= $currentPage === 'transactions' ? 'active' : '' ?>">
            <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            <span class="nav-text">Transactions Ledger</span>
        </a>

        <div class="sidebar-section-label">ADMINISTRATION &amp; SECURITY</div>

        <a href="/admin/diagnostics.php" class="nav-item <?= $currentPage === 'diagnostics' ? 'active' : '' ?>">
            <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="4 17 10 11 4 5"></polyline>
                <line x1="12" y1="19" x2="20" y2="19"></line>
            </svg>
            <span class="nav-text">Infrastructure Diagnostics</span>
        </a>
    </nav>

    <!-- Sidebar Security Status Card -->
    <div class="sidebar-footer">
        <div class="sidebar-security-badge">
            <div class="security-status-header">
                <span class="status-indicator-green"></span>
                <span class="security-title">Core Systems Guard</span>
            </div>
            <p class="security-meta">Internal Network v2.4</p>
            <div class="security-metrics">
                <span>Compliance: <strong>99.4%</strong></span>
                <span>Session: <strong>Encrypted</strong></span>
            </div>
        </div>

        <div class="sidebar-user-quick">
            <div class="quick-user-info">
                <span class="quick-user-name"><?= e($user['display_name'] ?? 'Guest') ?></span>
                <span class="quick-user-dept"><?= e($user['department'] ?? 'Public Guest') ?></span>
            </div>
            <a href="/logout.php" class="quick-logout-btn" title="Sign Out">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </a>
        </div>
    </div>
</aside>

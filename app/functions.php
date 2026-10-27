<?php
/**
 * CTC Bank — Helper Functions
 * Output encoding, formatting, and view utilities
 */

/**
 * Escapes text for secure HTML output (prevents XSS)
 */
function e(?string $value): string {
    if ($value === null) {
        return '';
    }
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Formats monetary amounts with currency symbol and accounting style
 */
function format_currency(float $amount, string $symbol = '$'): string {
    $isNegative = $amount < 0;
    $formatted = number_format(abs($amount), 2);
    if ($isNegative) {
        return '-' . $symbol . $formatted;
    }
    return $symbol . $formatted;
}

/**
 * Masks bank account number, exposing only the last 4 digits
 * Example: 849204814892 -> •••• •••• •••• 4892
 */
function mask_account_number(string $account): string {
    $clean = preg_replace('/[^0-9]/', '', $account);
    if (strlen($clean) < 4) {
        return $account;
    }
    $last4 = substr($clean, -4);
    return '•••• •••• •••• ' . $last4;
}

/**
 * Formats dates for enterprise banking logs
 */
function format_date(string $dateStr): string {
    $time = strtotime($dateStr);
    if (!$time) {
        return $dateStr;
    }
    return date('M d, Y H:i:s', $time);
}

/**
 * Store a flash message for the next page load
 */
function set_flash(string $type, string $message): void {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

/**
 * Retrieve and clear flash messages
 */
function get_flash_messages(): array {
    if (!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return [];
    }
    $messages = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $messages;
}

/**
 * Renders HTML badges for transaction / system status
 */
function render_status_badge(string $status): string {
    $statusLower = strtolower(trim($status));
    $map = [
        'completed'  => ['bg' => 'badge-success', 'label' => 'Completed', 'dot' => '#10B981'],
        'cleared'    => ['bg' => 'badge-success', 'label' => 'Cleared', 'dot' => '#10B981'],
        'pending'    => ['bg' => 'badge-warning', 'label' => 'Pending Review', 'dot' => '#F59E0B'],
        'flagged'    => ['bg' => 'badge-danger',  'label' => 'Flagged / Hold', 'dot' => '#EF4444'],
        'operational'=> ['bg' => 'badge-success', 'label' => 'Operational', 'dot' => '#10B981'],
        'active'     => ['bg' => 'badge-success', 'label' => 'Active', 'dot' => '#10B981'],
        'restricted' => ['bg' => 'badge-warning', 'label' => 'Restricted', 'dot' => '#F59E0B'],
    ];

    $info = $map[$statusLower] ?? ['bg' => 'badge-secondary', 'label' => ucfirst($status), 'dot' => '#94A3B8'];
    
    return sprintf(
        '<span class="badge %s"><span class="badge-dot" style="background-color: %s;"></span>%s</span>',
        e($info['bg']),
        e($info['dot']),
        e($info['label'])
    );
}

/**
 * Return client IP for audit log displays
 */
function get_client_ip(): string {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

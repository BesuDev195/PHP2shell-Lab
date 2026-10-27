<?php
/**
 * CTC Bank — Synthetic Demonstration Data
 * Realistic banking accounts, transaction records, and diagnostic logs.
 * ALL DATA IN THIS FILE IS PURELY FICTIONAL FOR CYBERSECURITY TRAINING.
 */

function get_demo_accounts(): array {
    return [
        'ACC-4892' => [
            'id'             => 'ACC-4892',
            'name'           => 'Commercial Operating Primary',
            'account_number' => '849204814892',
            'routing_number' => '021000089',
            'iban'           => 'US89 TTBL 0001 8492 0481 4892',
            'type'           => 'Corporate Checking',
            'currency'       => 'USD',
            'balance'        => 4829150.25,
            'available'      => 4791150.25,
            'status'         => 'active',
            'opened'         => '2021-03-15',
            'tier'           => 'Tier-1 Institutional',
            'manager'        => 'Elena Chen, Senior Treasury Officer'
        ],
        'ACC-7120' => [
            'id'             => 'ACC-7120',
            'name'           => 'Corporate Treasury Reserves',
            'account_number' => '920148197120',
            'routing_number' => '021000089',
            'iban'           => 'US89 TTBL 0002 9201 4819 7120',
            'type'           => 'High-Yield Treasury Reserve',
            'currency'       => 'USD',
            'balance'        => 18450000.00,
            'available'      => 18450000.00,
            'status'         => 'active',
            'opened'         => '2020-08-01',
            'tier'           => 'Tier-1 Institutional',
            'manager'        => 'Elena Chen, Senior Treasury Officer'
        ],
        'ACC-1094' => [
            'id'             => 'ACC-1094',
            'name'           => 'Global Liquidity & Clearing',
            'account_number' => '339185721094',
            'routing_number' => '021000089',
            'iban'           => 'US89 TTBL 0003 3391 8572 1094',
            'type'           => 'Multi-Currency Clearing',
            'currency'       => 'USD',
            'balance'        => 7215840.80,
            'available'      => 7100000.00,
            'status'         => 'active',
            'opened'         => '2022-01-10',
            'tier'           => 'Tier-1 Institutional',
            'manager'        => 'Alexander Vance, Infrastructure Security Admin'
        ],
        'ACC-6311' => [
            'id'             => 'ACC-6311',
            'name'           => 'Capital Expenditure Escrow',
            'account_number' => '551029486311',
            'routing_number' => '021000089',
            'iban'           => 'US89 TTBL 0004 5510 2948 6311',
            'type'           => 'Restricted Escrow Account',
            'currency'       => 'USD',
            'balance'        => 1120400.00,
            'available'      => 850000.00,
            'status'         => 'restricted',
            'opened'         => '2023-06-20',
            'tier'           => 'Restricted Escrow',
            'manager'        => 'Legal & Compliance Division'
        ]
    ];
}

function get_demo_transactions(): array {
    return [
        [
            'ref'         => 'TXN-2026-9481',
            'date'        => '2026-10-27 09:14:22',
            'account'     => 'Commercial Operating Primary',
            'account_id'  => 'ACC-4892',
            'description' => 'SWIFT Wire Out: Meridian Logistics Corp (Invoice #4401)',
            'category'    => 'Wire Transfer',
            'amount'      => -142500.00,
            'status'      => 'completed'
        ],
        [
            'ref'         => 'TXN-2026-9480',
            'date'        => '2026-10-27 08:30:10',
            'account'     => 'Global Liquidity & Clearing',
            'account_id'  => 'ACC-1094',
            'description' => 'ACH Inbound: Federal Reserve Automated Clearing Settlement',
            'category'    => 'Inbound Clearing',
            'amount'      => 850000.00,
            'status'      => 'cleared'
        ],
        [
            'ref'         => 'TXN-2026-9479',
            'date'        => '2026-10-26 18:45:00',
            'account'     => 'Commercial Operating Primary',
            'account_id'  => 'ACC-4892',
            'description' => 'Cloud Infrastructure Service Fee (Dedicated Vault Hosting)',
            'category'    => 'Vendor Operations',
            'amount'      => -18420.50,
            'status'      => 'completed'
        ],
        [
            'ref'         => 'TXN-2026-9478',
            'date'        => '2026-10-26 15:10:44',
            'account'     => 'Corporate Treasury Reserves',
            'account_id'  => 'ACC-7120',
            'description' => 'Treasury Yield Reinvestment Distribution Q4',
            'category'    => 'Dividend & Interest',
            'amount'      => 94210.00,
            'status'      => 'completed'
        ],
        [
            'ref'         => 'TXN-2026-9477',
            'date'        => '2026-10-25 14:00:12',
            'account'     => 'Commercial Operating Primary',
            'account_id'  => 'ACC-4892',
            'description' => 'Semi-Monthly Enterprise Payroll Batch (#PR-89241)',
            'category'    => 'Payroll',
            'amount'      => -685400.00,
            'status'      => 'completed'
        ],
        [
            'ref'         => 'TXN-2026-9476',
            'date'        => '2026-10-25 11:23:55',
            'account'     => 'Capital Expenditure Escrow',
            'account_id'  => 'ACC-6311',
            'description' => 'Hardware Asset Procurement — Core Diagnostic Gateway Server',
            'category'    => 'Capital Expenditure',
            'amount'      => -48900.00,
            'status'      => 'pending'
        ],
        [
            'ref'         => 'TXN-2026-9475',
            'date'        => '2026-10-24 16:15:30',
            'account'     => 'Global Liquidity & Clearing',
            'account_id'  => 'ACC-1094',
            'description' => 'FX Hedging Settlement EUR/USD Currency Desk',
            'category'    => 'Treasury FX',
            'amount'      => 320140.00,
            'status'      => 'completed'
        ],
        [
            'ref'         => 'TXN-2026-9474',
            'date'        => '2026-10-24 10:05:19',
            'account'     => 'Commercial Operating Primary',
            'account_id'  => 'ACC-4892',
            'description' => 'Commercial Client Deposit — Apex Global Trade Ltd',
            'category'    => 'Deposit',
            'amount'      => 1250000.00,
            'status'      => 'cleared'
        ],
        [
            'ref'         => 'TXN-2026-9473',
            'date'        => '2026-10-23 17:40:02',
            'account'     => 'Commercial Operating Primary',
            'account_id'  => 'ACC-4892',
            'description' => 'Compliance Review Audit Fee — ISO-27001 Certification',
            'category'    => 'Compliance & Audit',
            'amount'      => -32000.00,
            'status'      => 'completed'
        ],
        [
            'ref'         => 'TXN-2026-9472',
            'date'        => '2026-10-22 13:50:11',
            'account'     => 'Corporate Treasury Reserves',
            'account_id'  => 'ACC-7120',
            'description' => 'Inter-Account Liquidity Rebalance from Clearing',
            'category'    => 'Internal Transfer',
            'amount'      => 500000.00,
            'status'      => 'completed'
        ],
        [
            'ref'         => 'TXN-2026-9471',
            'date'        => '2026-10-21 09:12:00',
            'account'     => 'Commercial Operating Primary',
            'account_id'  => 'ACC-4892',
            'description' => 'Flagged Transfer: Unverified Beneficiary Wire Hold',
            'category'    => 'Wire Transfer',
            'amount'      => -95000.00,
            'status'      => 'flagged'
        ],
        [
            'ref'         => 'TXN-2026-9470',
            'date'        => '2026-10-20 12:20:45',
            'account'     => 'Global Liquidity & Clearing',
            'account_id'  => 'ACC-1094',
            'description' => 'Overnight Commercial Paper Settlement',
            'category'    => 'Securities Settlement',
            'amount'      => 450000.00,
            'status'      => 'completed'
        ]
    ];
}

function get_demo_diagnostics_history(): array {
    return [
        [
            'id'          => 'DIAG-8841',
            'timestamp'   => '2026-10-27 08:15:42',
            'operator'    => 'a.vance (Admin)',
            'target'      => '127.0.0.1',
            'tool'        => 'ICMP Echo Probe',
            'status'      => 'Operational',
            'latency'     => '0.08 ms',
            'summary'     => '2 packets transmitted, 2 received, 0% packet loss'
        ],
        [
            'id'          => 'DIAG-8840',
            'timestamp'   => '2026-10-26 23:40:11',
            'operator'    => 'sysadmin_ops',
            'target'      => 'core-gateway.techtonic.local',
            'tool'        => 'ICMP Echo Probe',
            'status'      => 'Operational',
            'latency'     => '1.14 ms',
            'summary'     => '2 packets transmitted, 2 received, 0% packet loss'
        ],
        [
            'id'          => 'DIAG-8839',
            'timestamp'   => '2026-10-26 19:12:05',
            'operator'    => 'sysadmin_ops',
            'target'      => 'db-cluster.internal',
            'tool'        => 'ICMP Echo Probe',
            'status'      => 'Operational',
            'latency'     => '0.42 ms',
            'summary'     => '2 packets transmitted, 2 received, 0% packet loss'
        ],
        [
            'id'          => 'DIAG-8838',
            'timestamp'   => '2026-10-25 14:05:29',
            'operator'    => 'a.vance (Admin)',
            'target'      => 'swift-gateway.dmz.techtonic.bank',
            'tool'        => 'ICMP Echo Probe',
            'status'      => 'Operational',
            'latency'     => '3.82 ms',
            'summary'     => '2 packets transmitted, 2 received, 0% packet loss'
        ],
        [
            'id'          => 'DIAG-8837',
            'timestamp'   => '2026-10-24 10:22:18',
            'operator'    => 'e.chen (Officer)',
            'target'      => 'auth-vault-01.internal',
            'tool'        => 'ICMP Echo Probe',
            'status'      => 'Operational',
            'latency'     => '0.29 ms',
            'summary'     => '2 packets transmitted, 2 received, 0% packet loss'
        ]
    ];
}

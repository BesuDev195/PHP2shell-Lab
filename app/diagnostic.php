<?php
/**
 * CTC Bank — Infrastructure Diagnostic Engine
 * 
 * Fictional internal diagnostic service used by the infrastructure team
 * to verify internal reachability and service health.
 * 
 * WARNING: THIS COMPONENT CONTAINS AN INTENTIONAL COMMAND INJECTION
 * VULNERABILITY DESIGNED SPECIFICALLY FOR THE PHP2SHELL CTF LAB.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

/**
 * Execute system diagnostic probe.
 * 
 * @param string $target Hostname, IP address, or diagnostic argument
 * @param string $tool Diagnostic probe type (default: 'ping')
 * @return array Structured execution results
 */
function execute_diagnostic(string $target, string $tool = 'ping'): array {
    $target = trim($target);

    // Basic length sanity check to prevent excessive input
    if (strlen($target) > 500) {
        return [
            'success'        => false,
            'command'        => '',
            'output'         => 'Error: Target expression exceeds maximum allowed length (500 characters).',
            'execution_time' => 0,
            'exit_code'      => 1,
            'timestamp'      => date('Y-m-d H:i:s')
        ];
    }

    if (empty($target)) {
        return [
            'success'        => false,
            'command'        => '',
            'output'         => 'Error: Please specify a diagnostic target hostname or IP address.',
            'execution_time' => 0,
            'exit_code'      => 1,
            'timestamp'      => date('Y-m-d H:i:s')
        ];
    }

    $startTime = microtime(true);

    // =========================================================================
    // INTENTIONAL VULNERABILITY (CTF Challenge: PHP2Shell)
    //
    // Challenge Name: PHP2Shell — The Diagnostic Gateway
    // Vulnerability: CWE-78 (OS Command Injection)
    //
    // CAUTION: The $target parameter is concatenated directly into the shell
    // command string without escapeshellarg(), escapeshellcmd(), or validation.
    // This permits command chaining delimiters (e.g. ;, &&, ||, |, `...`, $(...))
    // to execute arbitrary operating system commands inside the isolated container.
    //
    // Secure Implementation (Remediation):
    //   $safeTarget = escapeshellarg($target);
    //   $cmd = "ping -c 2 {$safeTarget} 2>&1";
    // =========================================================================
    $cmd = "ping -c 2 " . $target . " 2>&1";

    // Execute the shell command and capture all combined output (stdout & stderr)
    $output = shell_exec($cmd);

    $endTime = microtime(true);
    $executionTimeMs = round(($endTime - $startTime) * 1000, 2);

    $hasOutput = ($output !== null && strlen(trim($output)) > 0);

    return [
        'success'        => true,
        'command'        => $cmd,
        'target'         => $target,
        'tool'           => $tool,
        'output'         => $hasOutput ? $output : '[No output returned from diagnostic probe]',
        'execution_time' => $executionTimeMs,
        'exit_code'      => ($output !== null) ? 0 : 1,
        'timestamp'      => date('Y-m-d H:i:s')
    ];
}

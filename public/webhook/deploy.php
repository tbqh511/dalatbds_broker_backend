<?php
/**
 * Deploy webhook — triggered by /deploy slash command
 * Runs git pull + artisan cache rebuild on the server.
 *
 * Usage: GET/POST https://dalatbds.com/webhook/deploy.php?token=<WEBHOOK_SECRET>
 */

// ── Content Negotiation ───────────────────────────────────────────────────────
$isBrowser = isset($_SERVER['HTTP_ACCEPT']) &&
             str_contains($_SERVER['HTTP_ACCEPT'], 'text/html') &&
             !isset($_SERVER['HTTP_X_WEBHOOK_TOKEN']);

if (!$isBrowser) {
    header('Content-Type: application/json');
}

// ── Config ────────────────────────────────────────────────────────────────────
$APP_ROOT  = dirname(dirname(__DIR__)); // /home/qymxlvghhosting/public_html/dalatbds.com

// Read WEBHOOK_SECRET: try server env first, then fall back to Laravel .env file
$SECRET = getenv('WEBHOOK_SECRET') ?: '';
if (empty($SECRET)) {
    $envFile = $APP_ROOT . '/.env';
    if (is_readable($envFile)) {
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            if (str_starts_with($line, 'WEBHOOK_SECRET=')) {
                $SECRET = trim(substr($line, strlen('WEBHOOK_SECRET=')), " \t\"'");
                break;
            }
        }
    }
}
$PHP       = '/usr/local/bin/php';
$GIT       = '/usr/bin/git';

// ── Auth ──────────────────────────────────────────────────────────────────────
$token = $_GET['token'] ?? $_SERVER['HTTP_X_WEBHOOK_TOKEN'] ?? '';

if (empty($SECRET)) {
    http_response_code(500);
    if ($isBrowser) {
        echo renderErrorPage('Configuration Error', 'WEBHOOK_SECRET not configured on server');
    } else {
        echo json_encode(['status' => 0, 'error' => 'WEBHOOK_SECRET not configured on server']);
    }
    exit;
}

if (!hash_equals($SECRET, $token)) {
    http_response_code(403);
    if ($isBrowser) {
        echo renderErrorPage('Unauthorized', 'Invalid or missing webhook token');
    } else {
        echo json_encode(['status' => 0, 'error' => 'Unauthorized']);
    }
    exit;
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function run(string $cmd, string $cwd): array
{
    $output = [];
    $code   = 0;
    exec("cd " . escapeshellarg($cwd) . " && " . $cmd . " 2>&1", $output, $code);
    return ['cmd' => $cmd, 'code' => $code, 'output' => implode("\n", $output)];
}

// ── Steps ─────────────────────────────────────────────────────────────────────
$steps = [];

// 1. git pull
$steps[] = run("$GIT pull origin main", $APP_ROOT);

// 2. Clear caches
foreach (['config:clear', 'cache:clear', 'route:clear', 'view:clear'] as $cmd) {
    $steps[] = run("$PHP artisan $cmd", $APP_ROOT);
}

// 3. Rebuild caches
foreach (['config:cache', 'route:cache', 'view:cache'] as $cmd) {
    $steps[] = run("$PHP artisan $cmd", $APP_ROOT);
}

// ── Response ──────────────────────────────────────────────────────────────────
$failed = array_filter($steps, fn($s) => $s['code'] !== 0);

if (empty($failed)) {
    if ($isBrowser) {
        echo renderSuccessPage($steps);
    } else {
        echo json_encode(['status' => 1, 'message' => 'Deploy completed successfully', 'steps' => $steps]);
    }
} else {
    http_response_code(500);
    if ($isBrowser) {
        echo renderFailurePage($steps, $failed);
    } else {
        echo json_encode(['status' => 0, 'error' => 'One or more steps failed', 'steps' => $steps]);
    }
}

// ── HTML Render Functions ────────────────────────────────────────────────────────
function renderErrorPage(string $title, string $message): string
{
    return <<<'HTML'
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Deploy Error</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
                background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .container {
                background: #1a1a1a;
                border: 1px solid #333;
                border-radius: 12px;
                padding: 40px;
                max-width: 600px;
                width: 100%;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            }
            .header {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 24px;
            }
            .icon { font-size: 32px; }
            .title { font-size: 24px; font-weight: 600; color: #fff; }
            .message {
                color: #999;
                font-size: 16px;
                line-height: 1.5;
            }
            .error-code {
                background: #2a2a2a;
                border-left: 3px solid #ef4444;
                padding: 12px 16px;
                margin-top: 20px;
                border-radius: 6px;
                color: #ef4444;
                font-family: 'SF Mono', Monaco, Inconsolata, monospace;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div class="icon">⚠️</div>
                <div class="title">
    HTML
    . htmlspecialchars($title, ENT_QUOTES) .
    <<<'HTML'
                </div>
            </div>
            <p class="message">
    HTML
    . htmlspecialchars($message, ENT_QUOTES) .
    <<<'HTML'
            </p>
            <div class="error-code">× Error: Check server configuration</div>
        </div>
    </body>
    </html>
    HTML;
}

function renderSuccessPage(array $steps): string
{
    $now = date('Y-m-d H:i:s');
    $stepsHtml = '';

    foreach ($steps as $index => $step) {
        $stepNum = $index + 1;
        $code = $step['code'];
        $cmd = htmlspecialchars($step['cmd'], ENT_QUOTES);
        $output = htmlspecialchars($step['output'], ENT_QUOTES);

        $icon = '✅';
        $statusColor = '#22c55e';

        $stepsHtml .= <<<HTML
        <div class="step">
            <div class="step-header">
                <span class="step-icon">$icon</span>
                <code class="step-cmd">$cmd</code>
                <span class="step-code">[$code]</span>
            </div>
            <pre class="step-output">$output</pre>
        </div>
        HTML;
    }

    return <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Deploy Successful</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
                background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .container {
                background: #1a1a1a;
                border: 1px solid #333;
                border-radius: 12px;
                padding: 40px;
                max-width: 720px;
                width: 100%;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            }
            .header {
                text-align: center;
                margin-bottom: 32px;
            }
            .header-title {
                font-size: 28px;
                font-weight: 600;
                color: #fff;
                margin-bottom: 8px;
            }
            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: rgba(34, 197, 94, 0.1);
                border: 1px solid #22c55e;
                color: #22c55e;
                padding: 8px 16px;
                border-radius: 6px;
                font-size: 14px;
                font-weight: 600;
                margin-bottom: 12px;
            }
            .timestamp {
                color: #666;
                font-size: 13px;
            }
            .section-title {
                font-size: 13px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #999;
                margin-top: 32px;
                margin-bottom: 16px;
                padding-bottom: 8px;
                border-bottom: 1px solid #333;
            }
            .steps {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .step {
                background: #0f0f0f;
                border: 1px solid #333;
                border-radius: 8px;
                padding: 12px;
                overflow: hidden;
            }
            .step-header {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 8px;
                flex-wrap: wrap;
            }
            .step-icon {
                font-size: 16px;
                min-width: 20px;
            }
            .step-cmd {
                font-family: 'SF Mono', Monaco, Inconsolata, monospace;
                font-size: 13px;
                color: #ccc;
                background: rgba(255, 255, 255, 0.05);
                padding: 2px 6px;
                border-radius: 3px;
                flex: 1;
                overflow-x: auto;
                word-break: break-all;
            }
            .step-code {
                font-family: 'SF Mono', Monaco, Inconsolata, monospace;
                font-size: 12px;
                color: #666;
                min-width: 30px;
                text-align: right;
            }
            .step-output {
                font-family: 'SF Mono', Monaco, Inconsolata, monospace;
                font-size: 12px;
                color: #999;
                background: rgba(0, 0, 0, 0.3);
                border-left: 2px solid #333;
                padding: 8px 12px;
                margin: 0;
                overflow-x: auto;
                max-height: 100px;
                line-height: 1.4;
                white-space: pre-wrap;
                word-wrap: break-word;
            }
            .footer {
                text-align: center;
                margin-top: 32px;
                padding-top: 16px;
                border-top: 1px solid #333;
                color: #666;
                font-size: 12px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div class="header-title">🚀 DalatBDS Deploy</div>
                <div class="status-badge">
                    <span>✅</span>
                    <span>Deploy completed successfully</span>
                </div>
                <div class="timestamp">$now</div>
            </div>

            <div class="section-title">Steps ({count($steps)}/\{count($steps)} passed)</div>
            <div class="steps">
                $stepsHtml
            </div>

            <div class="footer">
                All steps executed successfully. Your application is ready to serve.
            </div>
        </div>
    </body>
    </html>
    HTML;
}

function renderFailurePage(array $steps, array $failed): string
{
    $now = date('Y-m-d H:i:s');
    $failedCount = count($failed);
    $totalCount = count($steps);
    $passedCount = $totalCount - $failedCount;
    $stepsHtml = '';

    foreach ($steps as $index => $step) {
        $stepNum = $index + 1;
        $code = $step['code'];
        $cmd = htmlspecialchars($step['cmd'], ENT_QUOTES);
        $output = htmlspecialchars($step['output'], ENT_QUOTES);
        $isFailed = $code !== 0;

        $icon = $isFailed ? '❌' : '✅';
        $statusClass = $isFailed ? 'failed' : 'passed';

        $stepsHtml .= <<<HTML
        <div class="step $statusClass">
            <div class="step-header">
                <span class="step-icon">$icon</span>
                <code class="step-cmd">$cmd</code>
                <span class="step-code">[$code]</span>
            </div>
            <pre class="step-output">$output</pre>
        </div>
        HTML;
    }

    return <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Deploy Failed</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
                background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .container {
                background: #1a1a1a;
                border: 1px solid #333;
                border-radius: 12px;
                padding: 40px;
                max-width: 720px;
                width: 100%;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            }
            .header {
                text-align: center;
                margin-bottom: 32px;
            }
            .header-title {
                font-size: 28px;
                font-weight: 600;
                color: #fff;
                margin-bottom: 8px;
            }
            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: rgba(239, 68, 68, 0.1);
                border: 1px solid #ef4444;
                color: #ef4444;
                padding: 8px 16px;
                border-radius: 6px;
                font-size: 14px;
                font-weight: 600;
                margin-bottom: 12px;
            }
            .timestamp {
                color: #666;
                font-size: 13px;
            }
            .stats {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
                margin: 20px 0;
            }
            .stat-item {
                background: #0f0f0f;
                border: 1px solid #333;
                border-radius: 6px;
                padding: 12px;
                text-align: center;
            }
            .stat-number {
                font-size: 24px;
                font-weight: 600;
                margin-bottom: 4px;
            }
            .stat-passed .stat-number { color: #22c55e; }
            .stat-failed .stat-number { color: #ef4444; }
            .stat-label {
                font-size: 12px;
                color: #666;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .section-title {
                font-size: 13px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #999;
                margin-top: 32px;
                margin-bottom: 16px;
                padding-bottom: 8px;
                border-bottom: 1px solid #333;
            }
            .steps {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .step {
                background: #0f0f0f;
                border: 1px solid #333;
                border-radius: 8px;
                padding: 12px;
                overflow: hidden;
            }
            .step.failed {
                border-color: #ef4444;
                background: rgba(239, 68, 68, 0.05);
            }
            .step-header {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 8px;
                flex-wrap: wrap;
            }
            .step-icon {
                font-size: 16px;
                min-width: 20px;
            }
            .step-cmd {
                font-family: 'SF Mono', Monaco, Inconsolata, monospace;
                font-size: 13px;
                color: #ccc;
                background: rgba(255, 255, 255, 0.05);
                padding: 2px 6px;
                border-radius: 3px;
                flex: 1;
                overflow-x: auto;
                word-break: break-all;
            }
            .step-code {
                font-family: 'SF Mono', Monaco, Inconsolata, monospace;
                font-size: 12px;
                color: #666;
                min-width: 30px;
                text-align: right;
            }
            .step.failed .step-code {
                color: #ef4444;
            }
            .step-output {
                font-family: 'SF Mono', Monaco, Inconsolata, monospace;
                font-size: 12px;
                color: #999;
                background: rgba(0, 0, 0, 0.3);
                border-left: 2px solid #333;
                padding: 8px 12px;
                margin: 0;
                overflow-x: auto;
                max-height: 100px;
                line-height: 1.4;
                white-space: pre-wrap;
                word-wrap: break-word;
            }
            .step.failed .step-output {
                border-left-color: #ef4444;
                background: rgba(239, 68, 68, 0.05);
                color: #fca5a5;
            }
            .footer {
                text-align: center;
                margin-top: 32px;
                padding-top: 16px;
                border-top: 1px solid #333;
                color: #ef4444;
                font-size: 12px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div class="header-title">🚀 DalatBDS Deploy</div>
                <div class="status-badge">
                    <span>❌</span>
                    <span>Deploy failed</span>
                </div>
                <div class="timestamp">$now</div>
            </div>

            <div class="stats">
                <div class="stat-item stat-passed">
                    <div class="stat-number">$passedCount</div>
                    <div class="stat-label">Passed</div>
                </div>
                <div class="stat-item stat-failed">
                    <div class="stat-number">$failedCount</div>
                    <div class="stat-label">Failed</div>
                </div>
            </div>

            <div class="section-title">Steps ({$passedCount}/{$totalCount} passed)</div>
            <div class="steps">
                $stepsHtml
            </div>

            <div class="footer">
                ⚠️ One or more deployment steps failed. Check the output above for details.
            </div>
        </div>
    </body>
    </html>
    HTML;
}

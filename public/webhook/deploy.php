<?php
/**
 * Deploy webhook — triggered by /deploy slash command
 * Runs git pull + artisan cache rebuild on the server.
 *
 * Usage: GET/POST https://dalatbds.com/webhook/deploy.php?token=<WEBHOOK_SECRET>
 */

header('Content-Type: application/json');

// ── Config ────────────────────────────────────────────────────────────────────
$APP_ROOT  = dirname(__DIR__); // /home/qymxlvghhosting/public_html/dalatbds.com

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
    echo json_encode(['status' => 0, 'error' => 'WEBHOOK_SECRET not configured on server']);
    exit;
}

if (!hash_equals($SECRET, $token)) {
    http_response_code(403);
    echo json_encode(['status' => 0, 'error' => 'Unauthorized']);
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
    echo json_encode(['status' => 1, 'message' => 'Deploy completed successfully', 'steps' => $steps]);
} else {
    http_response_code(500);
    echo json_encode(['status' => 0, 'error' => 'One or more steps failed', 'steps' => $steps]);
}

<?php
$appConfig = require __DIR__ . '/../config/app.php';

date_default_timezone_set($appConfig['timezone']);

error_reporting(E_ALL);
ini_set('log_errors', '1');
if (!empty($appConfig['error_log'])) {
    ini_set('error_log', $appConfig['error_log']);
}
ini_set('display_errors', $appConfig['debug'] ? '1' : '0');

if (session_status() === PHP_SESSION_NONE) {
    session_name($appConfig['session_name']);
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/validation.php';
require_once __DIR__ . '/middleware.php';

function config(string $key, $default = null)
{
    global $appConfig;
    return $appConfig[$key] ?? $default;
}

function is_api_request(): bool
{
    return strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false;
}

set_error_handler(static function (int $severity, string $message, string $file, int $line): void {
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(static function (Throwable $exception): void {
    error_log(sprintf(
        '[DynamicTournament] %s in %s:%d',
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine()
    ));

    $debug = config('debug', false) === true;

    if (is_api_request()) {
        api_response([
            'error' => $debug ? $exception->getMessage() : 'Internal server error. Please try again later.',
        ], 500);
    }

    http_response_code(500);
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Server Error</title></head><body style="font-family:Arial;padding:24px">';
    echo '<h2>Something went wrong (500)</h2>';
    echo '<p>Please check configuration and server logs.</p>';
    if ($debug) {
        echo '<pre>' . e($exception->getMessage() . "\n" . $exception->getFile() . ':' . $exception->getLine()) . '</pre>';
    }
    echo '</body></html>';
    exit;
});

function redirect(string $path): void
{
    header('Location: ' . config('base_url') . '/' . ltrim($path, '/'));
    exit;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function login_user(array $user): void
{
    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function generate_csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

<?php
function e($value): string
{
    if ($value === null) {
        return '';
    }

    if (is_bool($value)) {
        $value = $value ? '1' : '0';
    } elseif (!is_scalar($value)) {
        $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '';
    }

    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function flash(string $key, ?string $message = null)
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function api_response(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');

    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        $json = json_encode([
            'error' => 'JSON encoding failed',
            'code' => json_last_error(),
        ]);
        if ($status < 400) {
            http_response_code(500);
        }
    }

    echo $json;
    exit;
}

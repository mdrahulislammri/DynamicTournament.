<?php
function require_auth(): void
{
    $user = current_user();

    if (!$user) {
        if (function_exists('is_api_request') && is_api_request()) {
            api_response(['error' => 'Unauthorized'], 401);
        }

        flash('error', 'Please login first.');
        redirect('auth/login.php');
    }

    $stmt = db()->prepare('SELECT status FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([(int)$user['id']]);
    $status = $stmt->fetchColumn();

    if ($status !== 'active') {
        logout_user();

        if (function_exists('is_api_request') && is_api_request()) {
            api_response(['error' => 'Account suspended'], 403);
        }

        flash('error', 'Your account is suspended.');
        redirect('auth/login.php');
    }
}

function require_role(array $roles): void
{
    require_auth();
    $user = current_user();

    if (!in_array($user['role'], $roles, true)) {
        if (function_exists('is_api_request') && is_api_request()) {
            api_response(['error' => 'Forbidden'], 403);
        }

        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
}

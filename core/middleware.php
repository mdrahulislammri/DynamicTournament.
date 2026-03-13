<?php
function require_auth(): void
{
    if (!current_user()) {
        if (function_exists('is_api_request') && is_api_request()) {
            api_response(['error' => 'Unauthorized'], 401);
        }

        flash('error', 'Please login first.');
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

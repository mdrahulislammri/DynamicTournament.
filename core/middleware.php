<?php
function require_auth(): void
{
    if (!current_user()) {
        flash('error', 'Please login first.');
        redirect('auth/login.php');
    }
}

function require_role(array $roles): void
{
    require_auth();
    $user = current_user();
    if (!in_array($user['role'], $roles, true)) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
}

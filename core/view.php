<?php
require_once __DIR__ . '/functions.php';

function render_header(string $title): void
{
    $user = current_user();
    $base = config('base_url');
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . e($title) . ' | ' . e(config('app_name')) . '</title>';
    echo '<link rel="stylesheet" href="' . $base . '/assets/css/tailwind.css">';
    echo '</head><body class="bg-slate-100 text-slate-900">';
    echo '<nav class="bg-slate-900 text-white px-6 py-4">';
    echo '<div class="max-w-6xl mx-auto flex justify-between">';
    echo '<a href="' . $base . '/index.php" class="font-bold">🎮 Dynamic Tournament</a>';
    echo '<div class="space-x-4">';
    echo '<a href="' . $base . '/tournaments/list.php">Tournaments</a>';
    echo '<a href="' . $base . '/matches/schedule.php">Matches</a>';
    if ($user) {
        if (in_array($user['role'], ['admin', 'organizer'], true)) {
            echo '<a href="' . $base . '/admin/dashboard.php">Admin</a>';
        } else {
            echo '<a href="' . $base . '/player/dashboard.php">Dashboard</a>';
        }
        echo '<a href="' . $base . '/auth/logout.php">Logout</a>';
    } else {
        echo '<a href="' . $base . '/auth/login.php">Login</a><a href="' . $base . '/auth/register.php">Register</a>';
    }
    echo '</div></div></nav><main class="max-w-6xl mx-auto p-6">';
}

function render_footer(): void
{
    $base = config('base_url');
    echo '<footer class="max-w-6xl mx-auto px-6 py-4 text-slate-500">Built for competitive esports communities.</footer>';
    echo '<script src="' . $base . '/assets/js/main.js"></script></body></html>';
}

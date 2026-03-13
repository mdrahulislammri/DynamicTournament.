<?php
require_once __DIR__ . '/../core/view.php';
require_role(['admin', 'organizer']);
$stats = [
    'players' => (int)db()->query("SELECT COUNT(*) FROM users WHERE role='player'")->fetchColumn(),
    'tournaments' => (int)db()->query('SELECT COUNT(*) FROM tournaments')->fetchColumn(),
    'ongoing' => (int)db()->query("SELECT COUNT(*) FROM matches WHERE match_status='live'")->fetchColumn(),
    'completed' => (int)db()->query("SELECT COUNT(*) FROM matches WHERE match_status='completed'")->fetchColumn(),
];
render_header('Admin Dashboard');
?>
<h1 class="text-2xl font-bold">Admin Dashboard</h1>
<div class="grid md:grid-cols-2 gap-4 mt-4">
  <div class="bg-white p-4 rounded shadow">Total Players: <?= $stats['players'] ?></div>
  <div class="bg-white p-4 rounded shadow">Total Tournaments: <?= $stats['tournaments'] ?></div>
  <div class="bg-white p-4 rounded shadow">Ongoing Matches: <?= $stats['ongoing'] ?></div>
  <div class="bg-white p-4 rounded shadow">Completed Matches: <?= $stats['completed'] ?></div>
</div>
<div class="mt-6 space-x-4">
  <a class="btn" href="<?= e(config('base_url')) ?>/admin/tournaments/create.php">Create Tournament</a>
  <a class="btn" href="<?= e(config('base_url')) ?>/admin/matches/create.php">Add Match</a>
  <a class="btn" href="<?= e(config('base_url')) ?>/admin/rooms/add.php">Add Room</a>
</div>
<?php render_footer(); ?>

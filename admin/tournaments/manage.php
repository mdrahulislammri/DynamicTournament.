<?php
require_once __DIR__ . '/../../core/view.php';
require_role(['admin', 'organizer']);
$rows = db()->query('SELECT t.*, g.name AS game_name FROM tournaments t JOIN games g ON g.id=t.game_id ORDER BY t.created_at DESC')->fetchAll();
render_header('Manage Tournaments');
?>
<h1 class="text-2xl font-bold">Manage Tournaments</h1>
<table class="table bg-white mt-4"><thead><tr><th>Title</th><th>Game</th><th>Format</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php foreach($rows as $r): ?>
<tr><td><?= e($r['title']) ?></td><td><?= e($r['game_name']) ?></td><td><?= e($r['format_type']) ?></td><td><?= e($r['status']) ?></td><td><a href="edit.php?id=<?= $r['id'] ?>">Edit</a> | <a data-confirm="Delete tournament?" href="delete.php?id=<?= $r['id'] ?>">Delete</a></td></tr>
<?php endforeach; ?></tbody></table>
<?php render_footer(); ?>

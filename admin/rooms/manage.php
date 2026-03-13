<?php
require_once __DIR__ . '/../../core/view.php';
require_role(['admin', 'organizer']);
$rows=db()->query('SELECT r.*, t.title FROM rooms r JOIN tournaments t ON t.id=r.tournament_id ORDER BY r.room_time DESC')->fetchAll();
render_header('Manage Rooms');?>
<table class="table bg-white"><thead><tr><th>Tournament</th><th>Room ID</th><th>Map</th><th>Time</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['title']) ?></td><td><?= e($r['room_id']) ?></td><td><?= e($r['map_name']) ?></td><td><?= e($r['room_time']) ?></td></tr><?php endforeach; ?></tbody></table>
<?php render_footer(); ?>

<?php
require_once __DIR__ . '/../core/view.php';
$rows=db()->query('SELECT m.*, t.title FROM matches m JOIN tournaments t ON t.id=m.tournament_id ORDER BY m.match_time')->fetchAll();
render_header('Match Schedule');?>
<table class="table bg-white"><thead><tr><th>ID</th><th>Tournament</th><th>Time</th><th>Status</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= $r['id'] ?></td><td><?= e($r['title']) ?></td><td><?= e($r['match_time']) ?></td><td><?= e($r['match_status']) ?></td></tr><?php endforeach; ?></tbody></table>
<?php render_footer(); ?>

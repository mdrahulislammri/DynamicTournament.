<?php
require_once __DIR__ . '/../core/view.php';
$rows=db()->query('SELECT t.*, g.name AS game_name FROM tournaments t JOIN games g ON g.id=t.game_id ORDER BY t.start_date DESC')->fetchAll();
render_header('Tournament List');?>
<h1 class="text-2xl font-bold">Tournaments</h1>
<div class="grid md:grid-cols-2 gap-4 mt-4"><?php foreach($rows as $r): ?><article class="bg-white p-4 rounded shadow"><h3 class="font-semibold"><?= e($r['title']) ?></h3><p><?= e($r['game_name']) ?> | <?= e($r['format_type']) ?></p><p>Status: <?= e($r['status']) ?></p><a href="details.php?id=<?= $r['id'] ?>">View details</a></article><?php endforeach; ?></div>
<?php render_footer(); ?>

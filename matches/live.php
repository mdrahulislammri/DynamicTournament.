<?php
require_once __DIR__ . '/../core/view.php';
$rows=db()->query("SELECT m.*, t.title FROM matches m JOIN tournaments t ON t.id=m.tournament_id WHERE m.match_status='live' ORDER BY m.match_time")->fetchAll();
render_header('Live Matches');?>
<div class="grid md:grid-cols-2 gap-4"><?php foreach($rows as $r): ?><div class="bg-white p-4 rounded shadow"><h3><?= e($r['title']) ?></h3><p>Match #<?= $r['id'] ?> is live.</p></div><?php endforeach; ?></div>
<?php render_footer(); ?>

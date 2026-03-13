<?php
require_once __DIR__ . '/../core/view.php';
$rows=db()->query('SELECT m.id,t.title,mr.score_a,mr.score_b,mr.kills_a,mr.kills_b,m.match_time FROM match_results mr JOIN matches m ON m.id=mr.match_id JOIN tournaments t ON t.id=m.tournament_id ORDER BY m.match_time DESC')->fetchAll();
render_header('Match Results');?>
<table class="table bg-white"><thead><tr><th>Match</th><th>Tournament</th><th>Score</th><th>Kills</th><th>Time</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td>#<?= $r['id'] ?></td><td><?= e($r['title']) ?></td><td><?= $r['score_a'] ?> - <?= $r['score_b'] ?></td><td><?= $r['kills_a'] ?> - <?= $r['kills_b'] ?></td><td><?= e($r['match_time']) ?></td></tr><?php endforeach; ?></tbody></table>
<?php render_footer(); ?>

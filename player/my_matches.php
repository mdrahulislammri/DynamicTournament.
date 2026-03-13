<?php
require_once __DIR__ . '/../core/view.php';
require_role(['player']);
$userId=current_user()['id'];
$sql="SELECT m.id,m.match_time,m.match_status,t.title FROM matches m JOIN tournaments t ON t.id=m.tournament_id JOIN tournament_players tp ON tp.tournament_id=t.id WHERE tp.user_id=? ORDER BY m.match_time";
$stmt=db()->prepare($sql);$stmt->execute([$userId]);$rows=$stmt->fetchAll();
render_header('My Matches');?>
<table class="table bg-white"><thead><tr><th>ID</th><th>Tournament</th><th>Time</th><th>Status</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= $r['id'] ?></td><td><?= e($r['title']) ?></td><td><?= e($r['match_time']) ?></td><td><?= e($r['match_status']) ?></td></tr><?php endforeach; ?></tbody></table>
<?php render_footer(); ?>

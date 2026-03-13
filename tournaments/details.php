<?php
require_once __DIR__ . '/../core/view.php';
$id=(int)($_GET['id']??0);
$stmt=db()->prepare('SELECT t.*, g.name AS game_name FROM tournaments t JOIN games g ON g.id=t.game_id WHERE t.id=?');$stmt->execute([$id]);$t=$stmt->fetch();
if(!$t) exit('Not found');
$matches=db()->prepare('SELECT * FROM matches WHERE tournament_id=? ORDER BY round_number, match_time');$matches->execute([$id]);$matchRows=$matches->fetchAll();
$joined=false;
if(current_user()){
 $st=db()->prepare('SELECT 1 FROM tournament_players WHERE tournament_id=? AND user_id=?');$st->execute([$id,current_user()['id']]);$joined=(bool)$st->fetchColumn();
}
$rooms=[];
if($joined){$st=db()->prepare('SELECT * FROM rooms WHERE tournament_id=? ORDER BY room_time');$st->execute([$id]);$rooms=$st->fetchAll();}
render_header('Tournament Details');?>
<div class="bg-white p-6 rounded shadow"><h1 class="text-2xl font-bold"><?= e($t['title']) ?></h1><p><?= e($t['description']) ?></p><p class="mt-4">Game: <?= e($t['game_name']) ?> | Format: <?= e($t['format_type']) ?></p></div>
<h2 class="font-semibold mt-6">Matches</h2><table class="table bg-white mt-4"><thead><tr><th>#</th><th>Round</th><th>Time</th><th>Status</th></tr></thead><tbody><?php foreach($matchRows as $m): ?><tr><td><?= $m['id'] ?></td><td><?= $m['round_number'] ?></td><td><?= e($m['match_time']) ?></td><td><?= e($m['match_status']) ?></td></tr><?php endforeach; ?></tbody></table>
<h2 class="font-semibold mt-6">Room Details</h2>
<?php if(!$joined): ?><p class="text-slate-500">Join this tournament to unlock room credentials.</p><?php else: ?><table class="table bg-white mt-4"><thead><tr><th>Room ID</th><th>Password</th><th>Map</th><th>Time</th></tr></thead><tbody><?php foreach($rooms as $r): ?><tr><td><?= e($r['room_id']) ?></td><td><?= e($r['room_password']) ?></td><td><?= e($r['map_name']) ?></td><td><?= e($r['room_time']) ?></td></tr><?php endforeach; ?></tbody></table><?php endif; ?>
<p class="mt-4"><a class="btn" href="leaderboard.php?id=<?= $id ?>">View Leaderboard</a> <a class="btn" href="bracket_view.php?id=<?= $id ?>">View Bracket</a></p>
<?php render_footer(); ?>

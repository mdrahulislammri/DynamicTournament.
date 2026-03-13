<?php
require_once __DIR__ . '/../core/view.php';
require_role(['player']);
$user=current_user();
$stmt=db()->prepare('SELECT t.* FROM tournament_players tp JOIN tournaments t ON t.id=tp.tournament_id WHERE tp.user_id=? ORDER BY tp.created_at DESC');
$stmt->execute([$user['id']]);
$joined=$stmt->fetchAll();
render_header('Player Dashboard');?>
<h1 class="text-2xl font-bold">Welcome, <?= e($user['name']) ?></h1>
<p class="mt-4"><a class="btn" href="<?= e(config('base_url')) ?>/player/profile.php">My Profile</a> <a class="btn" href="<?= e(config('base_url')) ?>/player/join.php">Join Tournament</a> <a class="btn" href="<?= e(config('base_url')) ?>/player/my_matches.php">My Matches</a></p>
<h2 class="font-semibold mt-6">Joined Tournaments</h2>
<ul class="bg-white p-4 rounded shadow mt-4"><?php foreach($joined as $j): ?><li><?= e($j['title']) ?> (<?= e($j['status']) ?>)</li><?php endforeach; ?></ul>
<?php render_footer(); ?>

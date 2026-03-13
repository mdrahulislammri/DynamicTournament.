<?php
require_once __DIR__ . '/../core/view.php';
$id=(int)($_GET['id']??0);
render_header('Leaderboard');?>
<h1 class="text-2xl font-bold">Leaderboard</h1>
<table class="table bg-white mt-4"><thead><tr><th>Rank</th><th>Name</th><th>Matches</th><th>Kills</th><th>Wins</th><th>Points</th></tr></thead><tbody id="leaderboardBody"></tbody></table>
<script src="<?= e(config('base_url')) ?>/assets/js/leaderboard.js"></script>
<script>loadLeaderboard(<?= $id ?>);</script>
<?php render_footer(); ?>

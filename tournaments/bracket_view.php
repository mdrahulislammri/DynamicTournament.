<?php
require_once __DIR__ . '/../core/view.php';
$id=(int)($_GET['id']??0);
$stmt=db()->prepare('SELECT m.id,m.round_number, ta.name AS team_a, tb.name AS team_b, w.name AS winner FROM matches m LEFT JOIN teams ta ON ta.id=m.team_a_id LEFT JOIN teams tb ON tb.id=m.team_b_id LEFT JOIN match_results mr ON mr.match_id=m.id LEFT JOIN teams w ON w.id=mr.winner_team_id WHERE m.tournament_id=? ORDER BY m.round_number,m.id');
$stmt->execute([$id]);
$matches=$stmt->fetchAll();
$rounds=[];foreach($matches as $m){$rounds[$m['round_number']][]=$m;}
render_header('Bracket View');?>
<h1 class="text-2xl font-bold">Knockout Bracket</h1>
<div id="bracketContainer" class="grid md:grid-cols-2 gap-4 mt-4"></div>
<script src="<?= e(config('base_url')) ?>/assets/js/bracket.js"></script>
<script>renderBracket('bracketContainer', <?= json_encode(array_values($rounds), JSON_HEX_TAG) ?>);</script>
<?php render_footer(); ?>

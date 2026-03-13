<?php
require_once __DIR__ . '/../core/view.php';
require_role(['player']);
$userId=current_user()['id'];
if(is_post()){
 if(!verify_csrf_token($_POST['csrf_token']??null)) exit('Invalid CSRF');
 $tid=(int)$_POST['tournament_id'];
 db()->prepare('INSERT IGNORE INTO tournament_players (tournament_id, user_id, joined_at, created_at, updated_at) VALUES (?,?,NOW(),NOW(),NOW())')->execute([$tid,$userId]);
 flash('success','Joined successfully');
}
$tournaments=db()->query("SELECT * FROM tournaments WHERE status IN ('upcoming','live') ORDER BY start_date")->fetchAll();
render_header('Join Tournament');?>
<?php if($m=flash('success')): ?><p class="alert alert-success"><?= e($m) ?></p><?php endif; ?>
<form method="post" class="bg-white p-6 rounded shadow">
<input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
<label>Select Tournament</label><select name="tournament_id"><?php foreach($tournaments as $t): ?><option value="<?= $t['id'] ?>"><?= e($t['title']) ?> (<?= e($t['format_type']) ?>)</option><?php endforeach; ?></select>
<button class="mt-4">Join</button>
</form><?php render_footer(); ?>

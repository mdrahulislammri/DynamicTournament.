<?php
require_once __DIR__ . '/../../core/view.php';
require_role(['admin', 'organizer']);
if(is_post()){
 if(!verify_csrf_token($_POST['csrf_token']??null)) exit('Invalid CSRF');
 db()->prepare('INSERT INTO rooms (tournament_id, match_id, room_id, room_password, map_name, room_time, created_at, updated_at) VALUES (?,?,?,?,?,?,NOW(),NOW())')->execute([(int)$_POST['tournament_id'], (int)$_POST['match_id'], trim($_POST['room_id']), trim($_POST['room_password']), trim($_POST['map_name']), $_POST['room_time']]);
 redirect('admin/rooms/manage.php');
}
$tournaments=db()->query('SELECT id,title FROM tournaments')->fetchAll();
$matches=db()->query('SELECT id FROM matches ORDER BY id DESC')->fetchAll();
render_header('Add Room'); ?>
<form method="post" class="bg-white p-6 rounded shadow">
<input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
<label>Tournament</label><select name="tournament_id"><?php foreach($tournaments as $t): ?><option value="<?= $t['id'] ?>"><?= e($t['title']) ?></option><?php endforeach; ?></select>
<label class="mt-4">Match</label><select name="match_id"><?php foreach($matches as $m): ?><option value="<?= $m['id'] ?>">#<?= $m['id'] ?></option><?php endforeach; ?></select>
<label class="mt-4">Room ID</label><input name="room_id" required>
<label class="mt-4">Password</label><input name="room_password" required>
<label class="mt-4">Map</label><input name="map_name" required>
<label class="mt-4">Match Time</label><input type="datetime-local" name="room_time" required>
<button class="mt-4">Save Room</button>
</form><?php render_footer(); ?>

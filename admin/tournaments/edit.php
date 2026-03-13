<?php
require_once __DIR__ . '/../../core/view.php';
require_role(['admin', 'organizer']);
$id=(int)($_GET['id']??0);
$stmt=db()->prepare('SELECT * FROM tournaments WHERE id=?');$stmt->execute([$id]);$row=$stmt->fetch();
if(!$row){http_response_code(404);exit('Not found');}
if(is_post()){
 if(!verify_csrf_token($_POST['csrf_token']??null)) exit('Invalid CSRF');
 $stmt=db()->prepare('UPDATE tournaments SET title=?, description=?, format_type=?, status=?, start_date=?, updated_at=NOW() WHERE id=?');
 $stmt->execute([trim($_POST['title']), trim($_POST['description']), $_POST['format_type'], $_POST['status'], $_POST['start_date'], $id]);
 redirect('admin/tournaments/manage.php');
}
render_header('Edit Tournament');
?>
<form method="post" class="bg-white p-6 rounded shadow">
<input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
<label>Title</label><input name="title" value="<?= e($row['title']) ?>">
<label class="mt-4">Description</label><textarea name="description"><?= e($row['description']) ?></textarea>
<label class="mt-4">Format</label><select name="format_type"><?php foreach(['knockout','league','battle_royale'] as $f): ?><option <?= $row['format_type']===$f?'selected':'' ?>><?= $f ?></option><?php endforeach; ?></select>
<label class="mt-4">Status</label><select name="status"><?php foreach(['upcoming','live','completed'] as $s): ?><option <?= $row['status']===$s?'selected':'' ?>><?= $s ?></option><?php endforeach; ?></select>
<label class="mt-4">Start Date</label><input name="start_date" type="datetime-local" value="<?= date('Y-m-d\TH:i', strtotime($row['start_date'])) ?>">
<button class="mt-4">Update</button>
</form>
<?php render_footer(); ?>

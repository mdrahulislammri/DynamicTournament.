<?php
require_once __DIR__ . '/../../core/view.php';
require_role(['admin', 'organizer']);
if (is_post()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) exit('Invalid CSRF');
    $stmt = db()->prepare('INSERT INTO matches (tournament_id, team_a_id, team_b_id, round_number, match_time, match_status, created_at, updated_at) VALUES (?,?,?,?,?,?,NOW(),NOW())');
    $stmt->execute([(int)$_POST['tournament_id'], (int)$_POST['team_a_id'], (int)$_POST['team_b_id'], (int)($_POST['round_number'] ?? 1), $_POST['match_time'], $_POST['match_status']]);
    redirect('admin/matches/manage.php');
}
$tournaments=db()->query('SELECT id,title FROM tournaments ORDER BY created_at DESC')->fetchAll();
$teams=db()->query('SELECT id,name FROM teams ORDER BY name')->fetchAll();
render_header('Create Match');
?>
<form method="post" class="bg-white p-6 rounded shadow">
<input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
<label>Tournament</label><select name="tournament_id"><?php foreach($tournaments as $t): ?><option value="<?= $t['id'] ?>"><?= e($t['title']) ?></option><?php endforeach; ?></select>
<label class="mt-4">Team A</label><select name="team_a_id"><?php foreach($teams as $t): ?><option value="<?= $t['id'] ?>"><?= e($t['name']) ?></option><?php endforeach; ?></select>
<label class="mt-4">Team B</label><select name="team_b_id"><?php foreach($teams as $t): ?><option value="<?= $t['id'] ?>"><?= e($t['name']) ?></option><?php endforeach; ?></select>
<label class="mt-4">Round</label><input type="number" name="round_number" value="1">
<label class="mt-4">Match Time</label><input type="datetime-local" name="match_time" required>
<label class="mt-4">Status</label><select name="match_status"><option>upcoming</option><option>live</option><option>completed</option></select>
<button class="mt-4">Create Match</button>
</form>
<?php render_footer(); ?>

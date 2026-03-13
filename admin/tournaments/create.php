<?php
require_once __DIR__ . '/../../core/view.php';
require_role(['admin', 'organizer']);
$errors = [];
if (is_post()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) $errors['csrf'] = 'Invalid CSRF token';
    $errors += validate_required($_POST, ['title', 'game_id', 'format_type', 'start_date']);
    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO tournaments (title, description, game_id, format_type, max_players, entry_fee, status, start_date, created_by, created_at, updated_at) VALUES (?,?,?,?,?,?,?, ?, ?, NOW(), NOW())');
        $stmt->execute([
            trim($_POST['title']), trim($_POST['description'] ?? ''), (int)$_POST['game_id'], $_POST['format_type'], (int)($_POST['max_players'] ?? 100), (float)($_POST['entry_fee'] ?? 0), 'upcoming', $_POST['start_date'], current_user()['id']
        ]);
        redirect('admin/tournaments/manage.php');
    }
}
$games = db()->query('SELECT id, name FROM games ORDER BY name')->fetchAll();
render_header('Create Tournament');
?>
<h1 class="text-2xl font-bold">Create Tournament</h1>
<?php foreach ($errors as $e): ?><p class="alert alert-error mt-4"><?= e($e) ?></p><?php endforeach; ?>
<form method="post" class="bg-white p-6 rounded shadow mt-4">
<input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
<label>Title</label><input name="title" required>
<label class="mt-4">Game</label><select name="game_id"><?php foreach($games as $g): ?><option value="<?= $g['id'] ?>"><?= e($g['name']) ?></option><?php endforeach; ?></select>
<label class="mt-4">Format</label><select name="format_type"><option>knockout</option><option>league</option><option>battle_royale</option></select>
<label class="mt-4">Description</label><textarea name="description"></textarea>
<label class="mt-4">Max Players</label><input name="max_players" type="number" value="100">
<label class="mt-4">Entry Fee</label><input name="entry_fee" type="number" step="0.01" value="0">
<label class="mt-4">Start Date</label><input name="start_date" type="datetime-local" required>
<button class="mt-4">Save Tournament</button>
</form>
<?php render_footer(); ?>

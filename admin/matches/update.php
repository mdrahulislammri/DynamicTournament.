<?php
require_once __DIR__ . '/../../core/view.php';
require_role(['admin', 'organizer']);

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM matches WHERE id = ?');
$stmt->execute([$id]);
$match = $stmt->fetch();
if (!$match) {
    exit('Not found');
}

$errors = [];
if (is_post()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid CSRF token.';
    }

    $status = $_POST['match_status'] ?? 'completed';
    $winner = (int)($_POST['winner_team_id'] ?? 0);
    $teamA = (int)$match['team_a_id'];
    $teamB = (int)$match['team_b_id'];

    if (!in_array($status, ['upcoming', 'live', 'completed'], true)) {
        $errors[] = 'Invalid match status.';
    }

    if ($winner !== $teamA && $winner !== $teamB) {
        $errors[] = 'Winner must be Team A or Team B of this match.';
    }

    if (!$errors) {
        db()->prepare('UPDATE matches SET match_status=?, updated_at=NOW() WHERE id=?')->execute([$status, $id]);
        db()->prepare('INSERT INTO match_results (match_id, winner_team_id, score_a, score_b, kills_a, kills_b, placement_a, placement_b, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,NOW(),NOW()) ON DUPLICATE KEY UPDATE winner_team_id=VALUES(winner_team_id), score_a=VALUES(score_a), score_b=VALUES(score_b), kills_a=VALUES(kills_a), kills_b=VALUES(kills_b), placement_a=VALUES(placement_a), placement_b=VALUES(placement_b), updated_at=NOW()')->execute([
            $id,
            $winner,
            (int)($_POST['score_a'] ?? 0),
            (int)($_POST['score_b'] ?? 0),
            (int)($_POST['kills_a'] ?? 0),
            (int)($_POST['kills_b'] ?? 0),
            (int)($_POST['placement_a'] ?? 0),
            (int)($_POST['placement_b'] ?? 0),
        ]);
        redirect('admin/matches/manage.php');
    }
}

render_header('Update Match');
?>
<?php foreach ($errors as $error): ?><p class="alert alert-error mt-4"><?= e($error) ?></p><?php endforeach; ?>
<form method="post" class="bg-white p-6 rounded shadow">
<input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
<label>Status</label><select name="match_status"><option>upcoming</option><option>live</option><option selected>completed</option></select>
<label class="mt-4">Winner Team ID (<?= (int)$match['team_a_id'] ?> or <?= (int)$match['team_b_id'] ?>)</label><input type="number" name="winner_team_id" required>
<div class="grid md:grid-cols-2 gap-4 mt-4"><div><label>Score A</label><input type="number" name="score_a" value="0"></div><div><label>Score B</label><input type="number" name="score_b" value="0"></div><div><label>Kills A</label><input type="number" name="kills_a" value="0"></div><div><label>Kills B</label><input type="number" name="kills_b" value="0"></div><div><label>Placement A</label><input type="number" name="placement_a" value="0"></div><div><label>Placement B</label><input type="number" name="placement_b" value="0"></div></div>
<button class="mt-4">Save Result</button>
</form>
<?php render_footer(); ?>

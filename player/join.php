<?php
require_once __DIR__ . '/../core/view.php';
require_role(['player']);

$userId = (int)current_user()['id'];
$errors = [];

if (is_post()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid CSRF token.';
    }

    $tid = (int)($_POST['tournament_id'] ?? 0);
    if ($tid <= 0) {
        $errors[] = 'Please select a tournament.';
    }

    if (!$errors) {
        $db = db();

        $tournamentStmt = $db->prepare('SELECT id, title, status, max_players FROM tournaments WHERE id = ? LIMIT 1');
        $tournamentStmt->execute([$tid]);
        $tournament = $tournamentStmt->fetch();

        if (!$tournament) {
            $errors[] = 'Tournament not found.';
        } elseif (!in_array($tournament['status'], ['upcoming', 'live'], true)) {
            $errors[] = 'Tournament is not open for joining.';
        } else {
            $countStmt = $db->prepare('SELECT COUNT(*) FROM tournament_players WHERE tournament_id = ?');
            $countStmt->execute([$tid]);
            $joinedCount = (int)$countStmt->fetchColumn();

            if ($joinedCount >= (int)$tournament['max_players']) {
                $errors[] = 'Tournament is already full.';
            } else {
                $joinStmt = $db->prepare('INSERT IGNORE INTO tournament_players (tournament_id, user_id, joined_at, created_at, updated_at) VALUES (?,?,NOW(),NOW(),NOW())');
                $joinStmt->execute([$tid, $userId]);

                if ($joinStmt->rowCount() > 0) {
                    flash('success', 'Joined successfully.');
                } else {
                    flash('success', 'You already joined this tournament.');
                }
                redirect('player/join.php');
            }
        }
    }
}

$tournaments = db()->query("SELECT * FROM tournaments WHERE status IN ('upcoming','live') ORDER BY start_date")->fetchAll();
render_header('Join Tournament');
?>
<?php if ($m = flash('success')): ?><p class="alert alert-success"><?= e($m) ?></p><?php endif; ?>
<?php foreach ($errors as $error): ?><p class="alert alert-error mt-4"><?= e($error) ?></p><?php endforeach; ?>
<form method="post" class="bg-white p-6 rounded shadow">
<input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
<label>Select Tournament</label><select name="tournament_id" required><?php foreach($tournaments as $t): ?><option value="<?= (int)$t['id'] ?>"><?= e($t['title']) ?> (<?= e($t['format_type']) ?>)</option><?php endforeach; ?></select>
<button class="mt-4">Join</button>
</form><?php render_footer(); ?>

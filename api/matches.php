<?php
require_once __DIR__ . '/../core/functions.php';

$tournamentId = (int)($_GET['tournament_id'] ?? 0);
$status = trim((string)($_GET['status'] ?? ''));

$allowedStatus = ['upcoming', 'live', 'completed'];
if ($status !== '' && !in_array($status, $allowedStatus, true)) {
    api_response(['error' => 'Invalid status filter'], 422);
}

if ($tournamentId > 0 && $status !== '') {
    $stmt = db()->prepare('SELECT * FROM matches WHERE tournament_id = ? AND match_status = ? ORDER BY match_time');
    $stmt->execute([$tournamentId, $status]);
    $rows = $stmt->fetchAll();
} elseif ($tournamentId > 0) {
    $stmt = db()->prepare('SELECT * FROM matches WHERE tournament_id = ? ORDER BY match_time');
    $stmt->execute([$tournamentId]);
    $rows = $stmt->fetchAll();
} elseif ($status !== '') {
    $stmt = db()->prepare('SELECT * FROM matches WHERE match_status = ? ORDER BY match_time');
    $stmt->execute([$status]);
    $rows = $stmt->fetchAll();
} else {
    $rows = db()->query('SELECT * FROM matches ORDER BY match_time')->fetchAll();
}

api_response(['rows' => $rows]);

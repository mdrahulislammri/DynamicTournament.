<?php
require_once __DIR__ . '/../core/functions.php';

$tournamentId = (int)($_GET['tournament_id'] ?? 0);
if ($tournamentId <= 0) {
    api_response(['error' => 'tournament_id is required'], 422);
}

$stmt = db()->prepare('SELECT l.*, u.name FROM leaderboard l JOIN users u ON u.id=l.user_id WHERE l.tournament_id=? ORDER BY l.total_points DESC, l.kills DESC');
$stmt->execute([$tournamentId]);
api_response(['rows' => $stmt->fetchAll()]);

<?php
require_once __DIR__ . '/../core/functions.php';

if (request_method() !== 'POST') {
    api_response(['error' => 'Method not allowed'], 405);
}

require_auth();

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    api_response(['error' => 'Invalid CSRF'], 422);
}

$tournamentId = (int)($_POST['tournament_id'] ?? 0);
if ($tournamentId <= 0) {
    api_response(['error' => 'Tournament required'], 422);
}

$db = db();
$userId = (int)current_user()['id'];

$tournamentStmt = $db->prepare('SELECT id, status, max_players FROM tournaments WHERE id = ? LIMIT 1');
$tournamentStmt->execute([$tournamentId]);
$tournament = $tournamentStmt->fetch();

if (!$tournament) {
    api_response(['error' => 'Tournament not found'], 404);
}

if (!in_array($tournament['status'], ['upcoming', 'live'], true)) {
    api_response(['error' => 'Tournament is not open for joining'], 422);
}

$countStmt = $db->prepare('SELECT COUNT(*) FROM tournament_players WHERE tournament_id = ?');
$countStmt->execute([$tournamentId]);
$currentPlayers = (int)$countStmt->fetchColumn();
if ($currentPlayers >= (int)$tournament['max_players']) {
    api_response(['error' => 'Tournament is full'], 422);
}

$joinStmt = $db->prepare('INSERT IGNORE INTO tournament_players (tournament_id,user_id,joined_at,created_at,updated_at) VALUES (?,?,NOW(),NOW(),NOW())');
$joinStmt->execute([$tournamentId, $userId]);

if ($joinStmt->rowCount() === 0) {
    api_response(['message' => 'Already joined']);
}

api_response(['message' => 'Joined tournament']);

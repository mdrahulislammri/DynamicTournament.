<?php
require_once __DIR__ . '/../core/functions.php';

if (request_method() !== 'POST') {
    api_response(['error' => 'Method not allowed'], 405);
}

require_role(['admin', 'organizer']);

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    api_response(['error' => 'Invalid CSRF'], 422);
}

$matchId = (int)($_POST['match_id'] ?? 0);
$winnerId = (int)($_POST['winner_team_id'] ?? 0);
$killsA = max(0, (int)($_POST['kills_a'] ?? 0));
$killsB = max(0, (int)($_POST['kills_b'] ?? 0));
$placementA = max(0, (int)($_POST['placement_a'] ?? 0));
$placementB = max(0, (int)($_POST['placement_b'] ?? 0));

if ($matchId <= 0) {
    api_response(['error' => 'Valid match_id is required'], 422);
}

$db = db();
$matchStmt = $db->prepare('SELECT tournament_id, team_a_id, team_b_id FROM matches WHERE id = ?');
$matchStmt->execute([$matchId]);
$match = $matchStmt->fetch();

if (!$match) {
    api_response(['error' => 'Match not found'], 404);
}

$teamA = (int)$match['team_a_id'];
$teamB = (int)$match['team_b_id'];
if ($winnerId !== $teamA && $winnerId !== $teamB) {
    api_response(['error' => 'winner_team_id must be team_a or team_b'], 422);
}


$existingResultStmt = $db->prepare('SELECT id FROM match_results WHERE match_id = ? LIMIT 1');
$existingResultStmt->execute([$matchId]);
if ($existingResultStmt->fetch()) {
    api_response(['error' => 'Match result already exists. Use admin match update page to edit it.'], 409);
}

$placementPoints = [1 => 12, 2 => 9, 3 => 7];
$pointsA = $killsA + ($placementPoints[$placementA] ?? 0) + ($winnerId === $teamA ? 3 : 0);
$pointsB = $killsB + ($placementPoints[$placementB] ?? 0) + ($winnerId === $teamB ? 3 : 0);

try {
    $db->beginTransaction();

    $db->prepare('UPDATE matches SET match_status = "completed", updated_at = NOW() WHERE id = ?')->execute([$matchId]);
    $db->prepare('INSERT INTO match_results (match_id,winner_team_id,kills_a,kills_b,placement_a,placement_b,created_at,updated_at) VALUES (?,?,?,?,?,?,NOW(),NOW()) ON DUPLICATE KEY UPDATE winner_team_id=VALUES(winner_team_id), kills_a=VALUES(kills_a), kills_b=VALUES(kills_b), placement_a=VALUES(placement_a), placement_b=VALUES(placement_b), updated_at=NOW()')->execute([$matchId, $winnerId, $killsA, $killsB, $placementA, $placementB]);

    $teams = [
        ['team_id' => $teamA, 'kills' => $killsA, 'wins' => $winnerId === $teamA ? 1 : 0, 'points' => $pointsA],
        ['team_id' => $teamB, 'kills' => $killsB, 'wins' => $winnerId === $teamB ? 1 : 0, 'points' => $pointsB],
    ];

    $usersStmt = $db->prepare('SELECT user_id FROM players WHERE team_id = ?');
    $upsertLeaderboard = $db->prepare('INSERT INTO leaderboard (tournament_id,user_id,matches_played,kills,wins,total_points,created_at,updated_at) VALUES (?,?,1,?,?,?,NOW(),NOW()) ON DUPLICATE KEY UPDATE matches_played=matches_played+1, kills=kills+VALUES(kills), wins=wins+VALUES(wins), total_points=total_points+VALUES(total_points), updated_at=NOW()');

    foreach ($teams as $team) {
        $usersStmt->execute([$team['team_id']]);
        foreach ($usersStmt->fetchAll() as $userRow) {
            $upsertLeaderboard->execute([(int)$match['tournament_id'], (int)$userRow['user_id'], $team['kills'], $team['wins'], $team['points']]);
        }
    }

    $db->commit();
} catch (Throwable $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    api_response(['error' => 'Failed to update score'], 500);
}

api_response(['message' => 'Score updated and leaderboard recalculated']);

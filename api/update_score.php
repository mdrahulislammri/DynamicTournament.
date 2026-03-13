<?php
require_once __DIR__ . '/../core/functions.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){api_response(['error'=>'Method not allowed'],405);} 
require_role(['admin','organizer']);
if(!verify_csrf_token($_POST['csrf_token'] ?? null)){api_response(['error'=>'Invalid CSRF'],422);} 
$matchId=(int)($_POST['match_id'] ?? 0);
$winnerId=(int)($_POST['winner_team_id'] ?? 0);
$killsA=(int)($_POST['kills_a'] ?? 0);
$killsB=(int)($_POST['kills_b'] ?? 0);
$placementA=(int)($_POST['placement_a'] ?? 0);
$placementB=(int)($_POST['placement_b'] ?? 0);

$db=db();
$db->prepare('UPDATE matches SET match_status="completed", updated_at=NOW() WHERE id=?')->execute([$matchId]);
$db->prepare('INSERT INTO match_results (match_id,winner_team_id,kills_a,kills_b,placement_a,placement_b,created_at,updated_at) VALUES (?,?,?,?,?,?,NOW(),NOW()) ON DUPLICATE KEY UPDATE winner_team_id=VALUES(winner_team_id), kills_a=VALUES(kills_a), kills_b=VALUES(kills_b), placement_a=VALUES(placement_a), placement_b=VALUES(placement_b), updated_at=NOW()')->execute([$matchId,$winnerId,$killsA,$killsB,$placementA,$placementB]);

$match=$db->prepare('SELECT tournament_id, team_a_id, team_b_id FROM matches WHERE id=?');
$match->execute([$matchId]);
$row=$match->fetch();

$placementPoints=[1=>12,2=>9,3=>7];
$pointsA=$killsA+($placementPoints[$placementA]??0)+($winnerId===(int)$row['team_a_id']?3:0);
$pointsB=$killsB+($placementPoints[$placementB]??0)+($winnerId===(int)$row['team_b_id']?3:0);

$teams=[['team_id'=>(int)$row['team_a_id'],'kills'=>$killsA,'wins'=>$winnerId===(int)$row['team_a_id']?1:0,'points'=>$pointsA],['team_id'=>(int)$row['team_b_id'],'kills'=>$killsB,'wins'=>$winnerId===(int)$row['team_b_id']?1:0,'points'=>$pointsB]];
foreach($teams as $t){
 $users=$db->prepare('SELECT user_id FROM players WHERE team_id=?');$users->execute([$t['team_id']]);
 foreach($users->fetchAll() as $u){
  $db->prepare('INSERT INTO leaderboard (tournament_id,user_id,matches_played,kills,wins,total_points,created_at,updated_at) VALUES (?,?,1,?,?,?,NOW(),NOW()) ON DUPLICATE KEY UPDATE matches_played=matches_played+1, kills=kills+VALUES(kills), wins=wins+VALUES(wins), total_points=total_points+VALUES(total_points), updated_at=NOW()')->execute([(int)$row['tournament_id'],(int)$u['user_id'],$t['kills'],$t['wins'],$t['points']]);
 }
}
api_response(['message'=>'Score updated and leaderboard recalculated']);

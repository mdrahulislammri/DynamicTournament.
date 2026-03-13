<?php
require_once __DIR__ . '/../core/functions.php';
$tournamentId=(int)($_GET['tournament_id'] ?? 0);
if($tournamentId){
 $stmt=db()->prepare('SELECT * FROM matches WHERE tournament_id=? ORDER BY match_time');
 $stmt->execute([$tournamentId]);
 $rows=$stmt->fetchAll();
}else{
 $rows=db()->query('SELECT * FROM matches ORDER BY match_time')->fetchAll();
}
api_response(['rows'=>$rows]);

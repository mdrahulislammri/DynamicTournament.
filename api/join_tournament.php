<?php
require_once __DIR__ . '/../core/functions.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){api_response(['error'=>'Method not allowed'],405);} 
require_auth();
if(!verify_csrf_token($_POST['csrf_token'] ?? null)){api_response(['error'=>'Invalid CSRF'],422);} 
$tournamentId=(int)($_POST['tournament_id'] ?? 0);
if(!$tournamentId){api_response(['error'=>'Tournament required'],422);} 
$db=db();
$db->prepare('INSERT IGNORE INTO tournament_players (tournament_id,user_id,joined_at,created_at,updated_at) VALUES (?,?,NOW(),NOW(),NOW())')->execute([$tournamentId,current_user()['id']]);
api_response(['message'=>'Joined tournament']);

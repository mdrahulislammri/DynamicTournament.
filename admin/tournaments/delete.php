<?php
require_once __DIR__ . '/../../core/functions.php';
require_role(['admin', 'organizer']);
$id=(int)($_GET['id']??0);
$stmt=db()->prepare('DELETE FROM tournaments WHERE id=?');
$stmt->execute([$id]);
redirect('admin/tournaments/manage.php');

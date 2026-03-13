<?php
require_once __DIR__ . '/../../core/functions.php';
require_role(['admin']);
$id=(int)($_GET['id']??0);
db()->prepare("UPDATE users SET status = IF(status='active','banned','active'), updated_at=NOW() WHERE id=? AND role='player'")->execute([$id]);
redirect('admin/players/manage.php');

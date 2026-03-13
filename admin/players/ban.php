<?php
require_once __DIR__ . '/../../core/functions.php';

require_role(['admin']);

if (!is_post() || !verify_csrf_token($_POST['csrf_token'] ?? null)) {
    http_response_code(422);
    exit('Invalid request');
}

$id=(int)($_POST['id']??0);
if ($id > 0) {
    db()->prepare("UPDATE users SET status = IF(status='active','banned','active'), updated_at=NOW() WHERE id=? AND role='player'")->execute([$id]);
}

redirect('admin/players/manage.php');

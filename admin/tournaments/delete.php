<?php
require_once __DIR__ . '/../../core/functions.php';

require_role(['admin', 'organizer']);

if (!is_post() || !verify_csrf_token($_POST['csrf_token'] ?? null)) {
    http_response_code(422);
    exit('Invalid request');
}

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    $stmt = db()->prepare('DELETE FROM tournaments WHERE id = ?');
    $stmt->execute([$id]);
}

redirect('admin/tournaments/manage.php');

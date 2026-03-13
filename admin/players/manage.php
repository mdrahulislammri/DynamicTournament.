<?php
require_once __DIR__ . '/../../core/view.php';
require_role(['admin']);
$players=db()->query("SELECT id,name,email,status FROM users WHERE role='player' ORDER BY created_at DESC")->fetchAll();
render_header('Manage Players');?>
<table class="table bg-white"><thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Action</th></tr></thead><tbody><?php foreach($players as $p): ?><tr><td><?= e($p['name']) ?></td><td><?= e($p['email']) ?></td><td><?= e($p['status']) ?></td><td><a href="ban.php?id=<?= $p['id'] ?>" data-confirm="Toggle player status?">Toggle Ban</a></td></tr><?php endforeach; ?></tbody></table>
<?php render_footer(); ?>

<?php
require_once __DIR__ . '/../core/view.php';
require_role(['player','admin','organizer']);
$user=current_user();
render_header('My Profile');?>
<div class="bg-white p-6 rounded shadow"><h1 class="text-2xl font-bold">Profile</h1><p class="mt-4">Name: <?= e($user['name']) ?></p><p>Email: <?= e($user['email']) ?></p><p>Role: <?= e($user['role']) ?></p></div>
<?php render_footer(); ?>

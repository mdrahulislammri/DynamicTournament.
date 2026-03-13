<?php
require_once __DIR__ . '/../core/view.php';

$errors = [];
if (is_post()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors['csrf'] = 'Invalid CSRF token.';
    }

    $errors += validate_required($_POST, ['email', 'password']);

    if (!$errors) {
        $stmt = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([trim($_POST['email'])]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($_POST['password'], $user['password_hash'])) {
            $errors['auth'] = 'Invalid credentials.';
        } elseif (($user['status'] ?? 'active') !== 'active') {
            $errors['auth'] = 'Your account is suspended. Contact support.';
        } else {
            login_user($user);
            if (in_array($user['role'], ['admin', 'organizer'], true)) {
                redirect('admin/dashboard.php');
            }
            redirect('player/dashboard.php');
        }
    }
}

render_header('Login');
?>
<div class="bg-white p-6 rounded shadow">
  <h1 class="text-2xl font-bold">Login</h1>
  <?php if ($msg = flash('success')): ?><p class="alert alert-success mt-4"><?= e($msg) ?></p><?php endif; ?>
  <?php foreach ($errors as $error): ?><p class="alert alert-error mt-4"><?= e($error) ?></p><?php endforeach; ?>
  <form method="post" class="mt-4">
    <input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
    <label>Email</label><input type="email" name="email" required>
    <label class="mt-4">Password</label><input type="password" name="password" required>
    <button class="mt-4">Login</button>
  </form>
</div>
<?php render_footer(); ?>

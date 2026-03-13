<?php
require_once __DIR__ . '/../core/view.php';

$errors = [];
if (is_post()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors['csrf'] = 'Invalid CSRF token.';
    }

    $errors += validate_required($_POST, ['name', 'email', 'password']);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && !validate_email($email)) {
        $errors['email'] = 'Invalid email.';
    }

    if (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }

    if (!$errors) {
        $stmt = db()->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = 'Email already exists.';
        } else {
            $stmt = db()->prepare('INSERT INTO users (name, email, password_hash, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_BCRYPT), 'player']);
            flash('success', 'Registration successful. Please login.');
            redirect('auth/login.php');
        }
    }
}

render_header('Register');
?>
<div class="bg-white p-6 rounded shadow max-w-6xl">
  <h1 class="text-2xl font-bold">Register</h1>
  <?php if ($msg = flash('error')): ?><p class="alert alert-error"><?= e($msg) ?></p><?php endif; ?>
  <?php foreach ($errors as $error): ?><p class="alert alert-error mt-4"><?= e($error) ?></p><?php endforeach; ?>
  <form method="post" class="mt-4">
    <input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
    <label>Name</label><input name="name" required>
    <label class="mt-4">Email</label><input type="email" name="email" required>
    <label class="mt-4">Password</label><input type="password" name="password" minlength="8" required>
    <button class="mt-4">Create Account</button>
  </form>
</div>
<?php render_footer(); ?>

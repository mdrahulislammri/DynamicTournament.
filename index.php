<?php
require_once __DIR__ . '/core/view.php';
render_header('Home');
?>
<div class="grid md:grid-cols-2 gap-4">
  <section class="bg-white p-6 rounded shadow">
    <p class="text-slate-500">Professional Esports Control Panel</p>
    <h1 class="text-2xl font-bold">Run dynamic tournaments with confidence.</h1>
    <p class="text-slate-700 mt-4">Create and manage Free Fire, PUBG Mobile, Ludo, 8 Ball Pool, and Carrom Pool events with secure access, live match management, private room credentials, and auto-updating leaderboards.</p>
    <div class="mt-4">
      <a class="btn" href="<?= e(config('base_url')) ?>/tournaments/list.php">Explore Tournaments</a>
      <a class="btn" href="<?= e(config('base_url')) ?>/auth/register.php" style="margin-left:.5rem;background:linear-gradient(135deg,#4fd1c5,#40b7ad)">Get Started</a>
    </div>
  </section>
  <section class="bg-white p-6 rounded shadow">
    <h2 class="font-semibold">Platform Highlights</h2>
    <ul>
      <li>✅ Role-based dashboards for Admin/Organizer/Player</li>
      <li>✅ Knockout, League, and Battle Royale support</li>
      <li>✅ Private room visibility for joined participants only</li>
      <li>✅ Match results to leaderboard automation</li>
    </ul>
  </section>
</div>
<?php render_footer(); ?>

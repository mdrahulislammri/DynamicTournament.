<?php
require_once __DIR__ . '/core/view.php';
render_header('Home');
?>
<div class="grid md:grid-cols-2 gap-4">
  <section class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold">Online Esports Tournament Management</h1>
    <p class="text-slate-700 mt-4">Manage Free Fire, PUBG Mobile, Ludo, 8 Ball Pool and Carrom Pool tournaments with automatic bracket and leaderboard updates.</p>
    <a class="btn mt-4" href="<?= e(config('base_url')) ?>/tournaments/list.php">Explore Tournaments</a>
  </section>
  <section class="bg-white p-6 rounded shadow">
    <h2 class="font-semibold">Features</h2>
    <ul>
      <li>Role-based authentication</li>
      <li>Knockout / League / Battle Royale formats</li>
      <li>Room access for joined players only</li>
      <li>Live leaderboard APIs</li>
    </ul>
  </section>
</div>
<?php render_footer(); ?>

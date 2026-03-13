async function loadLeaderboard(tournamentId) {
  const tableBody = document.querySelector('#leaderboardBody');
  if (!tableBody) return;
  const res = await fetch(`../api/leaderboard.php?tournament_id=${tournamentId}`);
  const data = await res.json();
  tableBody.innerHTML = data.rows.map((row, i) => `<tr><td>${i + 1}</td><td>${row.name}</td><td>${row.matches_played}</td><td>${row.kills}</td><td>${row.wins}</td><td>${row.total_points}</td></tr>`).join('');
}

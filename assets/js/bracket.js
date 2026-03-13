function renderBracket(containerId, rounds) {
  const container = document.getElementById(containerId);
  if (!container) return;
  container.innerHTML = rounds
    .map((round, index) => `<div class="bg-white p-4 rounded shadow"><h3>Round ${index + 1}</h3>${round
      .map(match => `<p>${match.team_a} vs ${match.team_b} <strong>${match.winner ? 'Winner: ' + match.winner : ''}</strong></p>`)
      .join('')}</div>`)
    .join('');
}

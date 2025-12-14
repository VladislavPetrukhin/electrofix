<?php
$pageTitle = 'Статистика — ElectroFix';
require_once __DIR__ . '/partials/header.php';
?>

<section class="mb-4">
  <h1 class="h3 mb-2">Статистика сайта</h1>
  <p class="text-secondary">
    Данные загружаются асинхронно и визуализируются с помощью Chart.js.
  </p>
</section>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="card p-3">
      <h2 class="h6 mb-3">Гайды по устройствам</h2>
      <canvas id="guidesChart" height="200"></canvas>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card p-3">
      <h2 class="h6 mb-3">Неисправности по устройствам</h2>
      <canvas id="issuesChart" height="200"></canvas>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
fetch('/api/stats.php')
  .then(r => r.json())
  .then(data => {

    new Chart(document.getElementById('guidesChart'), {
      type: 'bar',
      data: {
        labels: data.guides.labels,
        datasets: [{
          label: 'Количество гайдов',
          data: data.guides.values
        }]
      }
    });

    new Chart(document.getElementById('issuesChart'), {
      type: 'pie',
      data: {
        labels: data.issues.labels,
        datasets: [{
          label: 'Количество неисправностей',
          data: data.issues.values
        }]
      }
    });

  });
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

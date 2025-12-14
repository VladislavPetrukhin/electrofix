<?php
$pageTitle = 'Неисправности — ElectroFix';
require_once __DIR__ . '/partials/header.php';

$issues = [];
try {
  $stmt = db()->query("SELECT i.id, i.symptom, i.severity, d.name AS device_name
                       FROM issues i LEFT JOIN devices d ON d.id=i.device_id
                       ORDER BY i.created_at DESC");
  $issues = $stmt->fetchAll();
} catch (Throwable $e) {
  $issues = [];
}
?>

<section class="mb-4">
  <h1 class="h3 mb-2">Типовые неисправности</h1>
  <p class="text-secondary mb-3">Фильтр работает на клиенте (vanilla JS). Кнопка «Подробнее» — подгружает JSON с сервера (AJAX).</p>

  <div class="hero-search">
    <div class="hero-search-inner">
      <input id="issueSearch" type="text" placeholder="Фильтр по симптому (первый столбец)" aria-label="Фильтр">
      <button type="button" onclick="filterIssues()">Фильтровать</button>
    </div>
  </div>
</section>

<div class="table-responsive">
<table id="issuesTable" class="table table-dark table-hover align-middle">
  <thead>
    <tr>
      <th>Симптом</th>
      <th class="d-none d-md-table-cell">Устройство</th>
      <th>Серьёзность</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php if (!$issues): ?>
      <tr>
        <td colspan="4" class="text-secondary">Пока нет данных. Добавь записи в админке.</td>
      </tr>
    <?php else: ?>
      <?php foreach ($issues as $i): ?>
        <tr>
          <td><?= h($i['symptom']) ?></td>
          <td class="d-none d-md-table-cell text-secondary"><?= h($i['device_name'] ?? '—') ?></td>
          <td><?= h($i['severity'] ?? '—') ?></td>
          <td class="text-end">
            <a class="btn btn-outline-light btn-sm" href="#" data-issue-id="<?= (int)$i['id'] ?>">Подробнее</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
</div>

<!-- Offcanvas details -->
<div class="offcanvas offcanvas-end text-bg-dark border" tabindex="-1" id="issueDetails" aria-labelledby="issueDetailsTitle">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="issueDetailsTitle">Неисправность</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
  </div>
  <div class="offcanvas-body" id="issueDetailsBody">
    <div class="text-secondary">Выбери запись…</div>
  </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

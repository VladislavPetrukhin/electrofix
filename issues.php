<?php
$pageTitle = 'Неисправности — ElectroFix';
$hl = trim((string)($_GET['hl'] ?? ''));
require_once __DIR__ . '/partials/header.php';

$issues = [];
try {
  $stmt = db()->query(
    "SELECT i.id, i.symptom, i.severity, d.name AS device_name
     FROM issues i
     LEFT JOIN devices d ON d.id = i.device_id
     ORDER BY i.created_at DESC"
  );
  $issues = $stmt->fetchAll();
} catch (Throwable $e) {
  $issues = [];
}
?>

<section class="mb-4">
  <h1 class="h3 mb-2">Типовые неисправности</h1>
  <p class="text-secondary mb-3">
    Фильтр — клиентский JS. Кнопка «Подробнее» — AJAX (JSON).
  </p>
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
      <td colspan="4" class="text-secondary">Нет данных.</td>
    </tr>
  <?php else: ?>
    <?php foreach ($issues as $i): ?>
      <tr id="issue-<?= (int)$i['id'] ?>">
        <td><?= highlight($i['symptom'] ?? '', $hl) ?></td>
        <td class="d-none d-md-table-cell text-secondary">
          <?= highlight($i['device_name'] ?? '—', $hl) ?>
        </td>
        <td><?= h($i['severity'] ?? '—') ?></td>
        <td class="text-end">
          <a class="btn btn-outline-light btn-sm"
             href="#"
             data-issue-id="<?= (int)$i['id'] ?>">
            Подробнее
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
  </tbody>
</table>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

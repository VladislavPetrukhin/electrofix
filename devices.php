<?php
$pageTitle = 'Устройства — ElectroFix';
require_once __DIR__ . '/partials/header.php';

$devices = [];
try {
  $stmt = db()->query("SELECT id, name, category, short_desc, thumb_path FROM devices ORDER BY created_at DESC");
  $devices = $stmt->fetchAll();
} catch (Throwable $e) {
  $devices = [];
}
?>

<section class="mb-4">
  <h1 class="h3 mb-2">Каталог устройств</h1>
  <p class="text-secondary mb-0">Кликни по карточке — подробности подгрузятся асинхронно (jQuery + JSON).</p>
</section>

<div id="devicesList" class="row g-4">
  <?php if (!$devices): ?>
    <div class="col-12">
      <div class="alert alert-dark border">
        Пока нет данных. Зайди в <a href="admin/" class="link-warning">админку</a> и добавь устройства.
      </div>
    </div>
  <?php else: ?>
    <?php foreach ($devices as $d): ?>
      <article class="col-md-6 col-lg-4">
        <div class="card p-3 h-100 device-card" role="button" tabindex="0" data-device-id="<?= (int)$d['id'] ?>">
          <?php if (!empty($d['thumb_path'])): ?>
            <img class="img-fluid rounded border mb-3" src="<?= h($d['thumb_path']) ?>" alt="<?= h($d['name']) ?>">
          <?php endif; ?>
          <h2 class="h5 mb-1"><?= h($d['name']) ?></h2>
          <div class="text-secondary small mb-2"><?= h($d['category'] ?? '') ?></div>
          <p class="text-secondary mb-3"><?= h($d['short_desc'] ?? '') ?></p>

          <!-- Lab 3 requirement: JS URL scheme -->
          <a class="link-warning small" href="javascript:openDeviceInfo(<?= (int)$d['id'] ?>)">
            Окно свойств (javascript:)
          </a>
        </div>
      </article>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="deviceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-dark text-light border">
      <div class="modal-header">
        <h5 class="modal-title" id="deviceModalTitle">Устройство</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body" id="deviceModalBody">
        <div class="text-secondary">Загрузка…</div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

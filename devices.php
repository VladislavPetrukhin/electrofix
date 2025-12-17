<?php
$pageTitle = 'Устройства — ElectroFix';
$hl = trim((string)($_GET['hl'] ?? ''));
require_once __DIR__ . '/partials/header.php';

$devices = [];
try {
  $stmt = db()->query(
    "SELECT id, name, category, short_desc, thumb_path
     FROM devices
     ORDER BY created_at DESC"
  );
  $devices = $stmt->fetchAll();
} catch (Throwable $e) {
  $devices = [];
}
?>

<section class="mb-4">
  <h1 class="h3 mb-2">Каталог устройств</h1>
  <p class="text-secondary mb-0">
    Подсветка работает при переходе из поиска.
  </p>
</section>

<div id="devicesList" class="row g-4">
<?php foreach ($devices as $d): ?>
  <article class="col-md-6 col-lg-4">
    <div class="card p-3 h-100 device-card">
      <?php if (!empty($d['thumb_path'])): ?>
        <img class="img-fluid rounded border mb-3"
             src="<?= h($d['thumb_path']) ?>"
             alt="">
      <?php endif; ?>

      <h2 class="h5 mb-1"><?= highlight($d['name'] ?? '', $hl) ?></h2>

      <?php if (!empty($d['category'])): ?>
        <div class="text-secondary small mb-2">
          <?= highlight($d['category'], $hl) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($d['short_desc'])): ?>
        <p class="text-secondary mb-3">
          <?= highlight($d['short_desc'], $hl) ?>
        </p>
      <?php endif; ?>

      <a class="link-warning small"
         href="javascript:openDeviceInfo(<?= (int)$d['id'] ?>)">
        Окно свойств (javascript:)
      </a>
<button
  type="button"
  class="btn btn-sm btn-outline-info device-more mt-2"
  data-device-id="<?= (int)$d['id'] ?>"
>
  Подробнее
</button>

    </div>
  </article>
<?php endforeach; ?>
</div>
<!-- Device Modal -->
<div class="modal fade" id="deviceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deviceModalTitle">Устройство</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="deviceModalBody">
        <!-- заполняется через JS -->
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

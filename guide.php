<?php
require_once __DIR__ . '/server/bootstrap.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$hl = trim((string)($_GET['hl'] ?? ''));

$g = null;
if ($id > 0) {
  $stmt = db()->prepare(
    "SELECT g.*, d.name AS device_name
     FROM guides g
     LEFT JOIN devices d ON d.id = g.device_id
     WHERE g.id = ? LIMIT 1"
  );
  $stmt->execute([$id]);
  $g = $stmt->fetch();
}

$pageTitle = ($g ? $g['title'] : 'Гайд') . ' — ElectroFix';
require_once __DIR__ . '/partials/header.php';
?>

<?php if (!$g): ?>
  <div class="alert alert-danger border">Гайд не найден.</div>
<?php else: ?>
  <article class="card p-4">
    <header class="mb-3">
      <h1 class="h3 mb-1"><?= highlight($g['title'] ?? '', $hl) ?></h1>
      <div class="text-secondary small">
        <?= h($g['device_name'] ?? '—') ?>
        • <?= h(date('d.m.Y', strtotime($g['created_at'] ?? 'now'))) ?>
      </div>
    </header>

    <?php if (!empty($g['cover_path'])): ?>
      <img class="img-fluid rounded border mb-3"
           src="<?= h($g['cover_path']) ?>"
           alt="">
    <?php endif; ?>

    <?php if (!empty($g['summary'])): ?>
      <section class="mb-3">
        <p class="text-secondary"><?= highlight($g['summary'], $hl) ?></p>
      </section>
    <?php endif; ?>

    <?php if (!empty($g['content'])): ?>
      <section>
        <?= nl2br(highlight($g['content'], $hl)) ?>
      </section>
    <?php endif; ?>

    <hr class="border-secondary my-4">
    <a class="btn btn-outline-light btn-sm" href="guides.php">← Назад к списку</a>
  </article>
<?php endif; ?>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

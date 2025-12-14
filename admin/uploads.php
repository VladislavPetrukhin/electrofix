<?php
$pageTitle = 'Админка: загрузки — ElectroFix';
require_once __DIR__ . '/../partials/header.php';
require_login();
$pdo = db();

$uploads = [];
$questions = [];
try {
  $uploads = $pdo->query("SELECT id, original_name, file_path, mime, size, created_at FROM uploads ORDER BY created_at DESC LIMIT 50")->fetchAll();
} catch (Throwable $e) { $uploads = []; }

try {
  $questions = $pdo->query("SELECT id, name, email, device, problem, created_at FROM questions ORDER BY created_at DESC LIMIT 50")->fetchAll();
} catch (Throwable $e) { $questions = []; }
?>
<script>window.EF_BASE_URL = "<?= h(BASE_URL) ?>";</script>

<section class="mb-4 d-flex justify-content-between align-items-end gap-3 flex-wrap">
  <div>
    <h1 class="h3 mb-2">Загрузки и заявки</h1>
    <p class="text-secondary mb-0">Демонстрация <strong>upload</strong> и ввода данных через форму.</p>
  </div>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-light btn-sm" href="index.php">← Админка</a>
  </div>
</section>

<div class="row g-4">
  <section class="col-lg-6">
    <div class="card p-4">
      <h2 class="h5 mb-3">Uploads</h2>
      <div class="table-responsive">
        <table class="table table-dark table-striped align-middle mb-0">
          <thead><tr><th>ID</th><th>Файл</th><th>Mime</th></tr></thead>
          <tbody>
            <?php foreach ($uploads as $u): ?>
              <tr>
                <td class="text-secondary"><?= (int)$u['id'] ?></td>
                <td>
                  <a class="link-warning" href="../<?= h($u['file_path']) ?>" target="_blank"><?= h($u['original_name']) ?></a>
                  <div class="small text-secondary"><?= h($u['file_path']) ?></div>
                </td>
                <td class="small text-secondary"><?= h($u['mime']) ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$uploads): ?><tr><td colspan="3" class="text-secondary">Пусто.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <section class="col-lg-6">
    <div class="card p-4">
      <h2 class="h5 mb-3">Заявки (questions)</h2>
      <div class="table-responsive">
        <table class="table table-dark table-striped align-middle mb-0">
          <thead><tr><th>ID</th><th>Кто</th><th>Устройство</th></tr></thead>
          <tbody>
            <?php foreach ($questions as $q): ?>
              <tr>
                <td class="text-secondary"><?= (int)$q['id'] ?></td>
                <td>
                  <?= h($q['name']) ?>
                  <div class="small text-secondary"><?= h($q['email']) ?></div>
                </td>
                <td>
                  <?= h($q['device']) ?>
                  <div class="small text-secondary"><?= h(mb_substr((string)$q['problem'], 0, 60)) ?><?= mb_strlen((string)$q['problem'])>60?'…':'' ?></div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$questions): ?><tr><td colspan="3" class="text-secondary">Пусто.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

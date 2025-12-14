<?php
$pageTitle = 'Админка: неисправности — ElectroFix';
require_once __DIR__ . '/../partials/header.php';
require_login();
?>
<script>window.EF_BASE_URL = "<?= h(BASE_URL) ?>";</script>
<?php
$pdo = db();
$error = null;
$ok = null;

$devices = $pdo->query("SELECT id, name FROM devices ORDER BY name")->fetchAll();

// save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = (int)($_POST['id'] ?? 0);
  $deviceId = (int)($_POST['device_id'] ?? 0);
  $symptom = trim($_POST['symptom'] ?? '');
  $causes = trim($_POST['causes'] ?? '');
  $solution = trim($_POST['solution'] ?? '');
  $severity = trim($_POST['severity'] ?? 'Средняя');

  if ($symptom === '' || mb_strlen($symptom) < 3) {
    $error = 'Симптом слишком короткий.';
  } else {
    if ($id > 0) {
      $st = $pdo->prepare("UPDATE issues SET device_id=?, symptom=?, causes=?, solution=?, severity=? WHERE id=?");
      $st->execute([$deviceId ?: null, $symptom, $causes, $solution, $severity, $id]);
      $ok = 'Обновлено.';
    } else {
      $st = $pdo->prepare("INSERT INTO issues(device_id, symptom, causes, solution, severity, created_at)
                           VALUES(?,?,?,?,?,NOW())");
      $st->execute([$deviceId ?: null, $symptom, $causes, $solution, $severity]);
      $ok = 'Добавлено.';
    }
  }
}

// edit mode
$edit = null;
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
if ($editId > 0) {
  $st = $pdo->prepare("SELECT * FROM issues WHERE id=?");
  $st->execute([$editId]);
  $edit = $st->fetch();
}

$rows = $pdo->query("SELECT i.id, i.symptom, i.severity, d.name AS device_name
                     FROM issues i LEFT JOIN devices d ON d.id=i.device_id
                     ORDER BY i.created_at DESC")->fetchAll();
?>

<section class="mb-4 d-flex justify-content-between align-items-end gap-3 flex-wrap">
  <div>
    <h1 class="h3 mb-2">Неисправности</h1>
    <p class="text-secondary mb-0">Удаление — асинхронно. Эти записи видны на публичной странице <code>/issues.php</code>.</p>
  </div>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-light btn-sm" href="index.php">← Админка</a>
    <a class="btn btn-outline-light btn-sm" href="../issues.php">Публичная страница</a>
  </div>
</section>

<?php if ($error): ?><div class="alert alert-danger border"><?= h($error) ?></div><?php endif; ?>
<?php if ($ok): ?><div class="alert alert-success border"><?= h($ok) ?></div><?php endif; ?>

<div class="row g-4">
  <section class="col-lg-7">
    <div class="card p-4">
      <h2 class="h5 mb-3"><?= $edit ? 'Редактировать' : 'Добавить' ?></h2>
      <form method="post">
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= (int)$edit['id'] ?>"><?php endif; ?>

        <div class="mb-3">
          <label class="form-label" for="device_id">Устройство</label>
          <select class="form-select" id="device_id" name="device_id">
            <option value="0">— не привязано —</option>
            <?php foreach ($devices as $d): ?>
              <option value="<?= (int)$d['id'] ?>" <?= ($edit && (int)$edit['device_id']===(int)$d['id']) ? 'selected' : '' ?>>
                <?= h($d['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label" for="symptom">Симптом</label>
          <input class="form-control" id="symptom" name="symptom" required value="<?= h($edit['symptom'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label" for="causes">Причины</label>
          <textarea class="form-control" id="causes" name="causes" rows="3"><?= h($edit['causes'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label" for="solution">Решение</label>
          <textarea class="form-control" id="solution" name="solution" rows="3"><?= h($edit['solution'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label" for="severity">Серьёзность</label>
          <select class="form-select" id="severity" name="severity">
            <?php
              $sev = $edit['severity'] ?? 'Средняя';
              $opts = ['Низкая','Средняя','Высокая'];
              foreach ($opts as $o) {
                $sel = ($sev === $o) ? 'selected' : '';
                echo '<option ' . $sel . '>' . h($o) . '</option>';
              }
            ?>
          </select>
        </div>

        <button class="btn btn-warning" type="submit"><?= $edit ? 'Сохранить' : 'Добавить' ?></button>
        <?php if ($edit): ?><a class="btn btn-outline-light" href="issues.php">Сбросить</a><?php endif; ?>
      </form>
    </div>
  </section>

  <aside class="col-lg-5">
    <div class="card p-4">
      <h2 class="h5 mb-3">Список</h2>
      <div class="table-responsive">
        <table id="adminTable" class="table table-dark table-hover align-middle mb-0">
          <thead>
            <tr><th>ID</th><th>Симптом</th><th></th></tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td class="text-secondary"><?= (int)$r['id'] ?></td>
                <td>
                  <?= h($r['symptom']) ?>
                  <div class="small text-secondary"><?= h(($r['device_name'] ?? '—') . ' • ' . ($r['severity'] ?? '—')) ?></div>
                </td>
                <td class="text-end">
                  <a class="btn btn-outline-light btn-sm" href="?edit=<?= (int)$r['id'] ?>">Ред.</a>
                  <button class="btn btn-outline-danger btn-sm" type="button"
                          data-delete="1" data-type="issues" data-id="<?= (int)$r['id'] ?>">
                    Удалить
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?>
              <tr><td colspan="3" class="text-secondary">Пусто.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </aside>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

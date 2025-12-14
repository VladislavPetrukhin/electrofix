<?php
$pageTitle = 'Админка: гайды — ElectroFix';
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
  $title = trim($_POST['title'] ?? '');
  $summary = trim($_POST['summary'] ?? '');
  $content = trim($_POST['content'] ?? '');

  if ($title === '' || mb_strlen($title) < 3) {
    $error = 'Заголовок слишком короткий.';
  } else {
    // upload (optional)
    $up = save_upload('cover', (int)current_user()['id']);
    $coverPath = $up['file_path'] ?? null;

    if ($id > 0) {
      $sql = "UPDATE guides SET device_id=?, title=?, summary=?, content=?";
      $params = [$deviceId ?: null, $title, $summary, $content];
      if ($coverPath) { $sql .= ", cover_path=?"; $params[] = $coverPath; }
      $sql .= " WHERE id=?";
      $params[] = $id;

      $st = $pdo->prepare($sql);
      $st->execute($params);
      $ok = 'Обновлено.';
    } else {
      $st = $pdo->prepare("INSERT INTO guides(device_id, title, summary, content, cover_path, created_at)
                           VALUES(?,?,?,?,?,NOW())");
      $st->execute([$deviceId ?: null, $title, $summary, $content, $coverPath]);
      $ok = 'Добавлено.';
    }
  }
}

// edit mode
$edit = null;
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
if ($editId > 0) {
  $st = $pdo->prepare("SELECT * FROM guides WHERE id=?");
  $st->execute([$editId]);
  $edit = $st->fetch();
}

$rows = $pdo->query("SELECT g.id, g.title, g.created_at, d.name AS device_name
                     FROM guides g LEFT JOIN devices d ON d.id=g.device_id
                     ORDER BY g.created_at DESC")->fetchAll();
?>

<section class="mb-4 d-flex justify-content-between align-items-end gap-3 flex-wrap">
  <div>
    <h1 class="h3 mb-2">Гайды</h1>
    <p class="text-secondary mb-0">Эти записи видны на <code>/guides.php</code> и <code>/guide.php?id=…</code>.</p>
  </div>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-light btn-sm" href="index.php">← Админка</a>
    <a class="btn btn-outline-light btn-sm" href="../guides.php">Публичная страница</a>
  </div>
</section>

<?php if ($error): ?><div class="alert alert-danger border"><?= h($error) ?></div><?php endif; ?>
<?php if ($ok): ?><div class="alert alert-success border"><?= h($ok) ?></div><?php endif; ?>

<div class="row g-4">
  <section class="col-lg-7">
    <div class="card p-4">
      <h2 class="h5 mb-3"><?= $edit ? 'Редактировать' : 'Добавить' ?></h2>
      <form method="post" enctype="multipart/form-data">
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
          <label class="form-label" for="title">Заголовок</label>
          <input class="form-control" id="title" name="title" required value="<?= h($edit['title'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label" for="summary">Коротко</label>
          <textarea class="form-control" id="summary" name="summary" rows="2"><?= h($edit['summary'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label" for="content">Содержимое</label>
          <textarea class="form-control" id="content" name="content" rows="8"><?= h($edit['content'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label" for="cover">Обложка (upload)</label>
          <input class="form-control" id="cover" name="cover" type="file" accept="image/*">
          <?php if ($edit && !empty($edit['cover_path'])): ?>
            <div class="small text-secondary mt-2">Текущая: <a class="link-warning" href="../<?= h($edit['cover_path']) ?>" target="_blank"><?= h($edit['cover_path']) ?></a></div>
          <?php endif; ?>
        </div>

        <button class="btn btn-warning" type="submit"><?= $edit ? 'Сохранить' : 'Добавить' ?></button>
        <?php if ($edit): ?><a class="btn btn-outline-light" href="guides.php">Сбросить</a><?php endif; ?>
      </form>
    </div>
  </section>

  <aside class="col-lg-5">
    <div class="card p-4">
      <h2 class="h5 mb-3">Список</h2>
      <div class="table-responsive">
        <table id="adminTable" class="table table-dark table-hover align-middle mb-0">
          <thead>
            <tr><th>ID</th><th>Заголовок</th><th></th></tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td class="text-secondary"><?= (int)$r['id'] ?></td>
                <td>
                  <?= h($r['title']) ?>
                  <div class="small text-secondary"><?= h(($r['device_name'] ?? '—')) ?></div>
                </td>
                <td class="text-end">
                  <a class="btn btn-outline-light btn-sm" href="?edit=<?= (int)$r['id'] ?>">Ред.</a>
                  <a class="btn btn-outline-light btn-sm" href="../guide.php?id=<?= (int)$r['id'] ?>" target="_blank">Откр.</a>
                  <button class="btn btn-outline-danger btn-sm" type="button"
                          data-delete="1" data-type="guides" data-id="<?= (int)$r['id'] ?>">
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

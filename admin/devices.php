<?php
$pageTitle = 'Админка: устройства — ElectroFix';
require_once __DIR__ . '/../partials/header.php';
require_login();
?>
<script>window.EF_BASE_URL = "<?= h(BASE_URL) ?>";</script>
<?php

$pdo = db();
$error = null;
$ok = null;

// save (create/update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = (int)($_POST['id'] ?? 0);
  $name = trim($_POST['name'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $short = trim($_POST['short_desc'] ?? '');
  $full  = trim($_POST['full_desc'] ?? '');

  if ($name === '' || mb_strlen($name) < 2) {
    $error = 'Название слишком короткое.';
  } else {
    // upload (optional)
    $up = save_upload('thumb', (int)current_user()['id']);
    $thumbPath = $up['file_path'] ?? null;

    if ($id > 0) {
      $sql = "UPDATE devices SET name=?, category=?, short_desc=?, full_desc=?, updated_at=NOW()";
      $params = [$name, $category, $short, $full];
      if ($thumbPath) { $sql .= ", thumb_path=?"; $params[] = $thumbPath; }
      $sql .= " WHERE id=?";
      $params[] = $id;

      $st = $pdo->prepare($sql);
      $st->execute($params);
      $ok = 'Обновлено.';
    } else {
      $st = $pdo->prepare("INSERT INTO devices(name, category, short_desc, full_desc, thumb_path, created_at, updated_at)
                           VALUES(?,?,?,?,?,NOW(),NOW())");
      $st->execute([$name, $category, $short, $full, $thumbPath]);
      $ok = 'Добавлено.';
    }
  }
}

// edit mode
$edit = null;
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
if ($editId > 0) {
  $st = $pdo->prepare("SELECT * FROM devices WHERE id=?");
  $st->execute([$editId]);
  $edit = $st->fetch();
}

$rows = $pdo->query("SELECT id, name, category, created_at FROM devices ORDER BY created_at DESC")->fetchAll();
?>

<section class="mb-4 d-flex justify-content-between align-items-end gap-3 flex-wrap">
  <div>
    <h1 class="h3 mb-2">Устройства</h1>
    <p class="text-secondary mb-0">Удаление — асинхронно (AJAX). Редактирование — обычной формой.</p>
  </div>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-light btn-sm" href="index.php">← Админка</a>
    <a class="btn btn-outline-light btn-sm" href="../devices.php">Публичная страница</a>
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
          <label class="form-label" for="name">Название</label>
          <input class="form-control" id="name" name="name" required value="<?= h($edit['name'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label" for="category">Категория</label>
          <input class="form-control" id="category" name="category" placeholder="Ноутбук/Смартфон/..." value="<?= h($edit['category'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label" for="short_desc">Коротко</label>
          <textarea class="form-control" id="short_desc" name="short_desc" rows="2"><?= h($edit['short_desc'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label" for="full_desc">Подробно</label>
          <textarea class="form-control" id="full_desc" name="full_desc" rows="5"><?= h($edit['full_desc'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label" for="thumb">Картинка (upload)</label>
          <input class="form-control" id="thumb" name="thumb" type="file" accept="image/*">
          <?php if ($edit && !empty($edit['thumb_path'])): ?>
            <div class="small text-secondary mt-2">Текущая: <a class="link-warning" href="../<?= h($edit['thumb_path']) ?>" target="_blank"><?= h($edit['thumb_path']) ?></a></div>
          <?php endif; ?>
        </div>

        <button class="btn btn-warning" type="submit"><?= $edit ? 'Сохранить' : 'Добавить' ?></button>
        <?php if ($edit): ?><a class="btn btn-outline-light" href="devices.php">Сбросить</a><?php endif; ?>
      </form>
    </div>
  </section>

  <aside class="col-lg-5">
    <div class="card p-4">
      <h2 class="h5 mb-3">Список</h2>
      <div class="table-responsive">
        <table id="adminTable" class="table table-dark table-hover align-middle mb-0">
          <thead>
            <tr><th>ID</th><th>Название</th><th></th></tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td class="text-secondary"><?= (int)$r['id'] ?></td>
                <td><?= h($r['name']) ?><div class="small text-secondary"><?= h($r['category'] ?? '') ?></div></td>
                <td class="text-end">
                  <a class="btn btn-outline-light btn-sm" href="?edit=<?= (int)$r['id'] ?>">Ред.</a>
                  <button class="btn btn-outline-danger btn-sm" type="button"
                          data-delete="1" data-type="devices" data-id="<?= (int)$r['id'] ?>">
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

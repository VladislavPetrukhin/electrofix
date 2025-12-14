<?php
$pageTitle = 'Админка — ElectroFix';
require_once __DIR__ . '/../partials/header.php';
require_login();
?>
<script>window.EF_BASE_URL = "<?= h(BASE_URL) ?>";</script>

<section class="mb-4">
  <h1 class="h3 mb-2">Админка</h1>
  <p class="text-secondary mb-0">CRUD + авторизация. Удаление можно делать асинхронно (кнопки в таблицах).</p>
</section>

<section class="row g-4">
  <article class="col-md-4">
    <div class="card p-4 h-100">
      <h2 class="h5">Устройства</h2>
      <p class="text-secondary">Добавляй и редактируй каталог.</p>
      <a class="btn btn-outline-light btn-sm" href="devices.php">Открыть</a>
    </div>
  </article>
  <article class="col-md-4">
    <div class="card p-4 h-100">
      <h2 class="h5">Неисправности</h2>
      <p class="text-secondary">Симптомы, причины, решения.</p>
      <a class="btn btn-outline-light btn-sm" href="issues.php">Открыть</a>
    </div>
  </article>
  <article class="col-md-4">
    <div class="card p-4 h-100">
      <h2 class="h5">Гайды</h2>
      <p class="text-secondary">Полные инструкции.</p>
      <a class="btn btn-outline-light btn-sm" href="guides.php">Открыть</a>
    </div>
  </article>
</section>

<div class="mt-4">
  <a class="btn btn-warning btn-sm" href="uploads.php">Загрузки</a>
  <a class="btn btn-outline-light btn-sm" href="logout.php">Выйти</a>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

<?php
$pageTitle = 'Главная — ElectroFix';
require_once __DIR__ . '/partials/header.php';
?>
<section class="hero p-4 p-md-5 mb-4">
  <div class="row g-4 align-items-center">
    <div class="col-lg-7">
      <h1 class="display-6 mb-2">ElectroFix</h1>
      <p class="lead mb-3">Мини‑справочник по диагностике и ремонту электроники (учебная версия для лабораторных работ).</p>

      <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-warning" href="devices.php">Каталог устройств</a>
        <a class="btn btn-outline-light" href="guides.php">Гайды</a>
        <a class="btn btn-outline-light" href="issues.php">Типовые неисправности</a>
      </div>

      <hr class="border-secondary my-4">


    </div>

    <aside class="col-lg-5">
      <div class="card p-3">
        <h2 class="h5">Мини‑демо видео</h2>

<video
  controls
  width="100%"
  preload="none"
>
  <source src="media/cleaning-demo.mp4" type="video/mp4">
</video>

      </div>
    </aside>
  </div>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

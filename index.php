<?php
$pageTitle = 'Главная — ElectroFix';
require_once __DIR__ . '/partials/header.php';
?>

<section class="hero hero-index p-4 p-lg-5 mb-5">
  <div class="row align-items-center g-5">

    <!-- LEFT -->
    <div class="col-lg-7">
      <h1 class="hero-title mb-4">ElectroFix</h1>

      <p class="hero-subtitle mb-4">
        Мини-справочник по диагностике и ремонту электроники (учебная версия для лабораторных работ).
      </p>

      <div class="hero-actions d-flex flex-wrap gap-3">
        <a href="devices.php" class="btn btn-warning btn-lg">
          Каталог устройств
        </a>
        <a href="guides.php" class="btn btn-outline-light btn-lg">
          Гайды
        </a>
        <a href="issues.php" class="btn btn-outline-light btn-lg">
          Типовые неисправности
        </a>
      </div>

      <hr class="border-secondary mt-4 mb-0">
    </div>

    <!-- RIGHT -->
    <div class="col-lg-5">
      <div class="video-card">
        <h5 class="mb-3">Мини-демо видео</h5>
        <video controls preload="none">
          <source src="media/cleaning-demo.mp4" type="video/mp4">
        </video>
      </div>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

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

      <article>
        <h2 class="h5">Безопасность</h2>
        <p class="text-secondary mb-2">Перед ремонтом: обесточь устройство, разряди конденсаторы, не греь батареи до “лавы”.</p>
        <audio controls preload="none">
          <source src="media/safety-reminder.mp3" type="audio/mpeg">
          <source src="media/safety-reminder.wav" type="audio/wav">
        </audio>
      </article>
    </div>

    <aside class="col-lg-5">
      <div class="card p-3">
        <h2 class="h5">Мини‑демо видео</h2>
        <p class="text-secondary mb-2">Пустышка на 2 секунды — чтобы тег <code>video</code> был не “мертвым”.</p>
        <video controls width="100%" preload="none">
          <source src="media/cleaning-demo.mp4" type="video/mp4">
        </video>
      </div>
    </aside>
  </div>
</section>

<section class="row g-4">
  <article class="col-md-4">
    <div class="card p-4 h-100">
      <h3 class="h5">HTML5 структура</h3>
      <p class="text-secondary mb-0">На страницах используются <code>header</code>, <code>nav</code>, <code>section</code>, <code>article</code>, <code>aside</code>, <code>footer</code>.</p>
    </div>
  </article>
  <article class="col-md-4">
    <div class="card p-4 h-100">
      <h3 class="h5">Bootstrap</h3>
      <p class="text-secondary mb-0">Сетка, карточки, кнопки, модальные окна — всё через Bootstrap.</p>
    </div>
  </article>
  <article class="col-md-4">
    <div class="card p-4 h-100">
      <h3 class="h5">AJAX + JSON</h3>
      <p class="text-secondary mb-0">Подгрузка подробностей устройств/гайдов идёт асинхронно через API (формат JSON).</p>
    </div>
  </article>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

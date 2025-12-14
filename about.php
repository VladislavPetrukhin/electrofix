<?php
$pageTitle = 'Связь — ElectroFix';
require_once __DIR__ . '/partials/header.php';
?>

<section class="mb-4">
  <h1 class="h3 mb-2">Связь</h1>
  <p class="text-secondary mb-0">Форма: проверка данных на клиенте + запись в БД через AJAX + генерация страницы‑результата.</p>
</section>

<div class="row g-4">
  <section class="col-lg-7">
    <article class="card p-4">
      <h2 class="h5 mb-3">Задать вопрос</h2>

      <!-- Lab 3 requirement: handler attribute -->
      <form id="support-form" onsubmit="return handleSupportForm(event)">
        <div class="mb-3">
          <label class="form-label" for="name">Имя</label>
          <input class="form-control" id="name" name="name" required minlength="2" placeholder="Например: Коля">
        </div>

        <div class="mb-3">
          <label class="form-label" for="email">E-mail</label>
          <input class="form-control" id="email" name="email" type="email" required placeholder="you@example.com">
        </div>

        <div class="mb-3">
          <label class="form-label" for="device">Устройство</label>
          <select class="form-select" id="device" name="device" required>
            <option value="">— выбери —</option>
            <option>Ноутбук</option>
            <option>Смартфон</option>
            <option>ПК</option>
            <option>Другое</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label" for="problem">Описание проблемы</label>
          <textarea class="form-control" id="problem" name="problem" rows="5" required minlength="10"
            placeholder="Что произошло, когда, что уже пробовал(а)…"></textarea>
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="agree" required>
          <label class="form-check-label" for="agree">
            Согласен(на) на обработку данных (учебный проект)
          </label>
        </div>

        <button class="btn btn-warning" type="submit">Отправить</button>
      </form>

      <p class="small text-secondary mt-3 mb-0">
        Если JS отключён, форма не отправится (в этой лабе специально проверяем клиентскую интерактивность).
      </p>
    </article>
  </section>

  <aside class="col-lg-5">
    <div class="card p-4">
      <h2 class="h5">Контакты</h2>
      <p class="text-secondary mb-2">Это учебный сайт, поэтому вместо “реального сервиса” — демо.</p>
      <ul class="mb-0">
        <li>Раздел админки: <a class="link-warning" href="admin/">/admin/</a></li>
        <li>API JSON: <code>/api/</code></li>
      </ul>
    </div>
  </aside>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

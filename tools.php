<?php
$pageTitle = 'Инструменты — ElectroFix';
require_once __DIR__ . '/partials/header.php';
?>
<section class="mb-4">
  <h1 class="h3 mb-2">Инструменты и расходники</h1>
  <p class="text-secondary mb-0">Страница статическая, но оформлена через Bootstrap и общий CSS.</p>
</section>

<section class="row g-4">
  <article class="col-lg-7">
    <div class="card p-4 h-100">
      <h2 class="h5">Мини‑набор</h2>
      <ul class="mb-0">
        <li><strong>Мультиметр</strong> — проверка питания, диодов, сопротивлений.</li>
        <li><strong>Паяльник / станция</strong> — аккуратный монтаж и демонтаж.</li>
        <li><strong>Флюс + оплётка</strong> — чистые площадки без “соплей”.</li>
        <li><strong>Изопропил</strong> — отмыть следы флюса и жирных пальцев.</li>
      </ul>
    </div>
  </article>

  <aside class="col-lg-5">
    <div class="card p-4 h-100">
      <h2 class="h5">Таблица (для тега table)</h2>
      <div class="table-responsive">
        <table class="table table-dark table-striped align-middle mb-0">
          <thead>
            <tr>
              <th>Инструмент</th>
              <th>Для чего</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Термопаста</td><td>Снижение температуры (но не магия)</td></tr>
            <tr><td>Пинцет</td><td>Мелкие компоненты</td></tr>
            <tr><td>Фен</td><td>Снятие разъёмов/экранирования (осторожно!)</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </aside>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

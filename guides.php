<?php
$pageTitle = 'Гайды — ElectroFix';
require_once __DIR__ . '/partials/header.php';

$guides = [];
try {
  $stmt = db()->query("SELECT id, title, summary, created_at FROM guides ORDER BY created_at DESC");
  $guides = $stmt->fetchAll();
} catch (Throwable $e) {
  $guides = [];
}
?>

<section class="mb-4">
  <h1 class="h3 mb-2">Гайды</h1>
  <p class="text-secondary mb-0">
    Слева — список (кратко). По клику справа подгружается превью через
    <code>fetch()</code> и JSON.
  </p>
</section>

<input
  type="text"
  id="guideSearch"
  class="form-control mb-3"
  placeholder="Поиск гайдов..."
>

<div class="row g-4">
  <aside class="col-lg-4">
    <div class="card p-3">
      <h2 class="h5">Список</h2>

      <div id="guidesList" class="list-group list-group-flush">
        <?php if (!$guides): ?>
          <div class="text-secondary">Пока пусто. Добавь гайды в админке.</div>
        <?php else: ?>
          <?php foreach ($guides as $g): ?>
            <a href="#"
               class="list-group-item list-group-item-action bg-dark text-light border-secondary"
               data-guide-id="<?= (int)$g['id'] ?>">
              <div class="fw-semibold"><?= h($g['title']) ?></div>
              <div class="small text-secondary"><?= h($g['summary'] ?? '') ?></div>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </aside>

  <section class="col-lg-8">
    <article class="card p-4" id="guidePreview">
      <h2 class="h5">Превью</h2>
      <p class="text-secondary mb-0">
        Выбери гайд слева — здесь появится краткое содержимое.
      </p>
    </article>
  </section>
</div>

<script>
$(function () {

  $('#guideSearch').on('input', function () {
    const q = $(this).val().trim();

    $.getJSON('/api/guides.php', { q }, function (items) {
      const $list = $('#guidesList');
      $list.empty();

      if (!items || items.length === 0) {
        $list.append('<div class="text-secondary">Ничего не найдено</div>');
        return;
      }

      items.forEach(g => {
        $list.append(`
          <a href="#"
             class="list-group-item list-group-item-action bg-dark text-light border-secondary"
             data-guide-id="${g.id}">
            <div class="fw-semibold">${g.title}</div>
            <div class="small text-secondary">${g.summary ?? ''}</div>
          </a>
        `);
      });
    });
  });

});
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

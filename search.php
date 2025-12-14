<?php
declare(strict_types=1);
$pageTitle = 'Поиск — ElectroFix';
require_once __DIR__ . '/partials/header.php';

$q = trim((string)($_GET['q'] ?? ''));
$results = [];
$error = null;

if ($q !== '') {

  // 1. Проверка через регулярное выражение (PHP)
  if (!preg_match('/^[\p{L}\p{N}\s\-]{2,50}$/u', $q)) {
    $error = 'Поисковый запрос содержит недопустимые символы.';
  } else {

    $pdo = db();
    $re  = preg_quote($q);

    /* ================= ГАЙДЫ ================= */
    $st = $pdo->prepare(
      "SELECT id, title
       FROM guides
       WHERE title REGEXP :re
          OR summary REGEXP :re
          OR content REGEXP :re"
    );
    $st->execute(['re' => $re]);
    foreach ($st->fetchAll() as $r) {
      $results[] = [
        'type'  => 'Гайд',
        'title' => $r['title'],
        'url'   => "/guide.php?id={$r['id']}&hl=" . urlencode($q)
      ];
    }

    /* ============== НЕИСПРАВНОСТИ ============== */
    $st = $pdo->prepare(
      "SELECT id, symptom
       FROM issues
       WHERE symptom REGEXP :re"
    );
    $st->execute(['re' => $re]);
    foreach ($st->fetchAll() as $r) {
      $results[] = [
        'type'  => 'Неисправность',
        'title' => $r['symptom'],
        'url'   => "/issues.php?hl=" . urlencode($q) . "#issue-{$r['id']}"
      ];
    }

    /* ================= УСТРОЙСТВА ================= */
    $st = $pdo->prepare(
      "SELECT id, name
       FROM devices
       WHERE name REGEXP :re
          OR category REGEXP :re
          OR short_desc REGEXP :re"
    );
    $st->execute(['re' => $re]);
    foreach ($st->fetchAll() as $r) {
      $results[] = [
        'type'  => 'Устройство',
        'title' => $r['name'],
        'url'   => "/devices.php?hl=" . urlencode($q)
      ];
    }
  }
}
?>

<h1 class="h3 mb-3">Поиск по сайту</h1>

<?php if ($error): ?>
  <div class="alert alert-danger"><?= h($error) ?></div>

<?php elseif ($q === ''): ?>
  <p class="text-secondary">Введите поисковый запрос.</p>

<?php elseif (!$results): ?>
  <p class="text-secondary">
    По запросу <strong><?= h($q) ?></strong> ничего не найдено.
  </p>

<?php else: ?>
  <p class="text-secondary">
    Найдено результатов: <?= count($results) ?>
  </p>

  <ul class="list-group">
    <?php foreach ($results as $r): ?>
      <li class="list-group-item bg-dark text-light border-secondary">
        <div class="small text-secondary"><?= h($r['type']) ?></div>
        <a class="link-warning" href="<?= h($r['url']) ?>">
          <?= h($r['title']) ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

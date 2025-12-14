<?php
require_once __DIR__ . '/../server/bootstrap.php';

$msg = null;
$err = null;

try {
  $pdo = db();

  // minimal check: tables exist?
  $pdo->query("SELECT 1 FROM roles LIMIT 1");
  $pdo->query("SELECT 1 FROM users LIMIT 1");

  $count = (int)$pdo->query("SELECT COUNT(*) AS c FROM users")->fetch()['c'];
  if ($count > 0) {
    $msg = "Пользователи уже есть. Setup ничего не делал.";
  } else {
    // ensure role
    $pdo->exec("INSERT IGNORE INTO roles(name) VALUES ('admin')");
    $roleId = (int)($pdo->query("SELECT id FROM roles WHERE name='admin' LIMIT 1")->fetch()['id'] ?? 0);

    $pass = 'admin123';
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $st = $pdo->prepare("INSERT INTO users(role_id, username, pass_hash, created_at) VALUES(?,?,?,NOW())");
    $st->execute([$roleId, 'admin', $hash]);

    $msg = "Готово! Создан пользователь admin / admin123. После входа можно удалить setup.php.";
  }
} catch (Throwable $e) {
  $err = "Не удалось создать пользователя. Проверь БД и импорт schema.sql. Текст: " . $e->getMessage();
}

$pageTitle = 'Setup — ElectroFix';
require_once __DIR__ . '/../partials/header.php';
?>

<article class="card p-4">
  <h1 class="h4 mb-3">Setup</h1>

  <?php if ($err): ?>
    <div class="alert alert-danger border"><?= h($err) ?></div>
  <?php else: ?>
    <div class="alert alert-success border"><?= h($msg ?? '') ?></div>
    <a class="btn btn-warning btn-sm" href="login.php">Перейти к входу</a>
  <?php endif; ?>

  <hr class="border-secondary my-4">
  <p class="text-secondary small mb-0">
    Если таблиц нет — импортируй <code>data/schema.sql</code> в phpMyAdmin.
  </p>
</article>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

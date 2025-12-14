<?php
require_once __DIR__ . '/../server/bootstrap.php';

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = (string)($_POST['password'] ?? '');

  if ($username === '' || $password === '') {
    $error = 'Введите логин и пароль.';
  } else if (login($username, $password)) {
    header('Location: ' . BASE_URL . '/admin/');
    exit;
  } else {
    $error = 'Неверный логин или пароль.';
  }
}

$pageTitle = 'Вход — ElectroFix';
require_once __DIR__ . '/../partials/header.php';
?>

<section class="row justify-content-center">
  <div class="col-md-7 col-lg-5">
    <article class="card p-4">
      <h1 class="h4 mb-3">Вход в админку</h1>

      <?php if ($error): ?>
        <div class="alert alert-danger border"><?= h($error) ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-3">
          <label class="form-label" for="username">Логин</label>
          <input class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
          <label class="form-label" for="password">Пароль</label>
          <input class="form-control" id="password" name="password" type="password" required>
        </div>
        <button class="btn btn-warning" type="submit">Войти</button>
      </form>

      <hr class="border-secondary my-4">
      <p class="text-secondary small mb-0">
        Первый запуск? Открой <a class="link-warning" href="setup.php">setup.php</a> (создаст пользователя <b>admin</b>).
      </p>
    </article>
  </div>
</section>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

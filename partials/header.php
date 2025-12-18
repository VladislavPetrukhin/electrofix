<?php
declare(strict_types=1);
require_once __DIR__ . '/../server/bootstrap.php';
$pageTitle = $pageTitle ?? 'ElectroFix';
?>
<!doctype html>
<html lang="ru" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="ElectroFix — учебный мини-сайт по диагностике и ремонту электроники.">
  <title><?= h($pageTitle) ?></title>
<link rel="icon" href="/favicon.ico" sizes="any">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- jQuery (для лабы) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Styles -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body class="bg-dark text-light d-flex flex-column min-vh-100">
<header class="site-header py-3">
  <div class="container d-flex align-items-center justify-content-between">
    <div>
      <a class="text-decoration-none" href="<?= BASE_URL ?>/index.php">
        <span class="brand">ElectroFix</span>
      </a>
      <span class="ms-2 text-secondary">учебный проект</span>
    </div>
<form action="<?= BASE_URL ?>/search.php" method="get" class="d-inline-flex ms-3">
  <input
    type="text"
    name="q"
    class="form-control form-control-sm"
    placeholder="Поиск по сайту"
    required
  >
</form>

    <nav class="d-none d-md-block">
      <a href="<?= BASE_URL ?>/devices.php">Устройства</a>
      <a href="<?= BASE_URL ?>/issues.php">Неисправности</a>
      <a href="<?= BASE_URL ?>/guides.php">Гайды</a>
      <a href="<?= BASE_URL ?>/tools.php">Инструменты</a>
      <a href="<?= BASE_URL ?>/about.php">Связь</a>
	<a href="<?= BASE_URL ?>/stats.php">Статистика</a>
      <a href="<?= BASE_URL ?>/admin/"><?= is_logged_in() ? 'Админка' : 'Вход' ?></a>
    </nav>
  </div>
</header>

<main class="container my-4 flex-grow-1">
<div id="toastArea" aria-live="polite" aria-atomic="true"></div>

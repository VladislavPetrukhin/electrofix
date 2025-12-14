<?php
require_once __DIR__ . '/../server/bootstrap.php';
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  json_out(['error' => 'Method not allowed'], 405);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$q  = trim((string)($_GET['q'] ?? ''));

if ($id > 0) {
  // Полная инфа по одному гайду
  $st = $pdo->prepare(
    "SELECT g.*, d.name AS device_name
     FROM guides g
     LEFT JOIN devices d ON d.id = g.device_id
     WHERE g.id = ?"
  );
  $st->execute([$id]);
  $row = $st->fetch();
  if (!$row) json_out(['error' => 'Not found'], 404);
  json_out($row);
}

// Список (с поиском)
if ($q !== '') {
  $st = $pdo->prepare(
    "SELECT id, title, summary, created_at
     FROM guides
     WHERE title LIKE :q OR summary LIKE :q
     ORDER BY created_at DESC"
  );
  $st->execute(['q' => "%{$q}%"]);
  $rows = $st->fetchAll();
} else {
  $rows = $pdo->query(
    "SELECT id, title, summary, created_at
     FROM guides
     ORDER BY created_at DESC"
  )->fetchAll();
}

json_out($rows);

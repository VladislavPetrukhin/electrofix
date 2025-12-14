<?php
require_once __DIR__ . '/../server/bootstrap.php';

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id > 0) {
    $st = $pdo->prepare("SELECT id, name, category, short_desc, full_desc, thumb_path, created_at FROM devices WHERE id=?");
    $st->execute([$id]);
    $d = $st->fetch();
    if (!$d) json_out(['error' => 'Not found'], 404);

    // add small extras
    $st2 = $pdo->prepare("SELECT id, symptom, severity FROM issues WHERE device_id=? ORDER BY created_at DESC LIMIT 10");
    $st2->execute([$id]);
    $d['issues'] = $st2->fetchAll();

    json_out($d);
  } else {
    $rows = $pdo->query("SELECT id, name, category, short_desc, thumb_path FROM devices ORDER BY created_at DESC")->fetchAll();
    json_out($rows);
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = input_json();
  $action = $data['action'] ?? '';

  if ($action === 'delete') {
    require_login();
    $id = (int)($data['id'] ?? 0);
    if ($id <= 0) json_out(['error' => 'Bad id'], 400);

    $st = $pdo->prepare("DELETE FROM devices WHERE id=?");
    $st->execute([$id]);
    json_out(['ok' => true]);
  }

  json_out(['error' => 'Unknown action'], 400);
}

json_out(['error' => 'Method not allowed'], 405);

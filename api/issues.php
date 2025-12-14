<?php
require_once __DIR__ . '/../server/bootstrap.php';
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id > 0) {
    $st = $pdo->prepare("SELECT i.*, d.name AS device_name
                         FROM issues i LEFT JOIN devices d ON d.id=i.device_id
                         WHERE i.id=?");
    $st->execute([$id]);
    $row = $st->fetch();
    if (!$row) json_out(['error' => 'Not found'], 404);
    json_out($row);
  } else {
    $rows = $pdo->query("SELECT i.id, i.symptom, i.severity, d.name AS device_name
                         FROM issues i LEFT JOIN devices d ON d.id=i.device_id
                         ORDER BY i.created_at DESC")->fetchAll();
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
    $st = $pdo->prepare("DELETE FROM issues WHERE id=?");
    $st->execute([$id]);
    json_out(['ok' => true]);
  }

  json_out(['error' => 'Unknown action'], 400);
}

json_out(['error' => 'Method not allowed'], 405);

<?php
require_once __DIR__ . '/../server/bootstrap.php';
require_post();

$data = input_json();
$name = trim((string)($data['name'] ?? ''));
$email = trim((string)($data['email'] ?? ''));
$device = trim((string)($data['device'] ?? ''));
$problem = trim((string)($data['problem'] ?? ''));

// regex requirement: validate server-side too
if (!is_name($name)) json_out(['error' => 'Некорректное имя (2-50 символов).'], 400);
if (!is_email($email)) json_out(['error' => 'Некорректный e-mail.'], 400);
if ($device === '') json_out(['error' => 'Не выбрано устройство.'], 400);
if (mb_strlen($problem) < 10) json_out(['error' => 'Слишком короткое описание.'], 400);

$pdo = db();
$st = $pdo->prepare("INSERT INTO questions(name, email, device, problem, created_at) VALUES(?,?,?,?,NOW())");
$st->execute([$name, $email, $device, $problem]);

$id = (int)$pdo->lastInsertId();
$ticket = 'Q' . date('Ymd') . '-' . $id;

json_out(['ok' => true, 'id' => $id, 'ticket' => $ticket]);

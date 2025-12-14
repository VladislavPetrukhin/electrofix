<?php
declare(strict_types=1);

function h(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function json_out($data, int $code = 200): void {
  http_response_code($code);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;
}

function require_method(string $m): void {
  if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== strtoupper($m)) {
    json_out(['error' => 'Method not allowed'], 405);
  }
}

function require_post(): void { require_method('POST'); }

function input_json(): array {
  $raw = file_get_contents('php://input') ?: '';
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}

function is_email(string $email): bool {
  // regex requirement: simple but explicit
  return (bool)preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email);
}

function is_name(string $name): bool {
  return (bool)preg_match('/^[\p{L}0-9 ._-]{2,50}$/u', $name);
}


function save_upload(string $field, int $userId = 0): ?array {
  if (!isset($_FILES[$field]) || !is_array($_FILES[$field])) return null;
  $f = $_FILES[$field];
  if (($f['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
  if (($f['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) return null;

  $tmp = $f['tmp_name'] ?? '';
  if (!$tmp || !is_uploaded_file($tmp)) return null;

  $orig = (string)($f['name'] ?? 'file');
  $size = (int)($f['size'] ?? 0);
  $mime = (string)($f['type'] ?? 'application/octet-stream');

  $ext = pathinfo($orig, PATHINFO_EXTENSION);
  $ext = preg_replace('/[^a-zA-Z0-9]/', '', $ext) ?: 'bin';

  $safeName = bin2hex(random_bytes(8)) . '.' . $ext;
  $destAbs = rtrim(UPLOAD_DIR, '/\\') . DIRECTORY_SEPARATOR . $safeName;

  if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0775, true);
  if (!move_uploaded_file($tmp, $destAbs)) return null;

  $path = 'uploads/' . $safeName;

  // сохраняем в БД как отдельную таблицу uploads (не обязательно, но полезно для лабы)
  try {
    $pdo = db();
    $st = $pdo->prepare("INSERT INTO uploads(user_id, original_name, file_path, mime, size, created_at)
                         VALUES(?,?,?,?,?,NOW())");
    $st->execute([$userId ?: null, $orig, $path, $mime, $size]);
  } catch (Throwable $e) {
    // если таблицы нет — просто молча продолжаем
  }

  return ['file_path' => $path, 'original_name' => $orig, 'mime' => $mime, 'size' => $size];
}

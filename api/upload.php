<?php
require_once __DIR__ . '/../server/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_out(['error' => 'Method not allowed'], 405);

// multipart upload: field name "file"
$up = save_upload('file', (int)current_user()['id']);
if (!$up) json_out(['error' => 'Upload failed'], 400);

json_out(['ok' => true] + $up);

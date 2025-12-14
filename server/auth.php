<?php
declare(strict_types=1);

function current_user(): ?array {
  return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool {
  return isset($_SESSION['user']);
}

function login(string $username, string $password): bool {
  $pdo = db();
  $stmt = $pdo->prepare("SELECT u.id, u.username, u.pass_hash, r.name AS role
                         FROM users u LEFT JOIN roles r ON r.id=u.role_id
                         WHERE u.username=? LIMIT 1");
  $stmt->execute([$username]);
  $u = $stmt->fetch();
  if (!$u) return false;
  if (!password_verify($password, $u['pass_hash'])) return false;

  $_SESSION['user'] = [
    'id' => (int)$u['id'],
    'username' => $u['username'],
    'role' => $u['role'] ?? 'user'
  ];
  return true;
}

function logout(): void {
  $_SESSION = [];
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]
    );
  }
  session_destroy();
}

function require_login(): void {
  if (!is_logged_in()) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
  }
}

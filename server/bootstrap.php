<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/auth.php';

function highlight(string $text, string $q): string {
  if ($q === '') return h($text);

  return preg_replace(
    '/(' . preg_quote($q, '/') . ')/iu',
    '<mark class="search-hit">$1</mark>',
    h($text)
  );
}

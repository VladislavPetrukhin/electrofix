<?php
declare(strict_types=1);

/**
 * Настройки БД (MySQL/MariaDB)
 * Для Open Server / XAMPP обычно подойдёт:
 *   host=localhost, dbname=electrofix, user=root, pass=''
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'electrofix');
define('DB_USER', 'root');
define('DB_PASS', '');

/** Базовый URL проекта (если ставишь в подпапку, поменяй) */
define('BASE_URL', '');

/** Папка для загрузок (внутри проекта) */
define('UPLOAD_DIR', __DIR__ . '/../uploads');

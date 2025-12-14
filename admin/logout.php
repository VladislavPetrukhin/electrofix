<?php
require_once __DIR__ . '/../server/bootstrap.php';
logout();
header('Location: ' . BASE_URL . '/index.php');
exit;

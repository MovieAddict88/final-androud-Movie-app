<?php
require_once __DIR__ . '/config.php';

function get_pdo(): PDO {
  $cfg = get_env_db_config();
  $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $cfg['host'], $cfg['name']);
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ];
  return new PDO($dsn, $cfg['user'], $cfg['pass'], $options);
}

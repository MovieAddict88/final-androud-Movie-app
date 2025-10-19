<?php
require_once __DIR__ . '/../lib/config.php';
require_once __DIR__ . '/../lib/db.php';

try {
  $pdo = get_pdo();
  $stmt = $pdo->query('SELECT id, name, slug FROM categories ORDER BY name ASC');
  $rows = $stmt->fetchAll();
  send_json(['data' => $rows]);
} catch (Throwable $e) {
  send_json(['error' => 'Server error'], 500);
}

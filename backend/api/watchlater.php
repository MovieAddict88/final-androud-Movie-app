<?php
require_once __DIR__ . '/../lib/config.php';
require_once __DIR__ . '/../lib/db.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method === 'POST' && isset($_POST['_method']) && strtoupper($_POST['_method']) === 'DELETE') {
  $method = 'DELETE';
}

try {
  $pdo = get_pdo();
  if ($method === 'GET') {
    $device_id = $_GET['device_id'] ?? '';
    if ($device_id === '') {
      send_json(['error' => 'device_id required'], 400);
    }
    $stmt = $pdo->prepare("SELECT w.content_id, c.title, c.type, c.poster_url
                            FROM watch_later w JOIN contents c ON w.content_id = c.id
                            WHERE w.device_id = :d ORDER BY w.created_at DESC");
    $stmt->execute([':d' => $device_id]);
    $rows = $stmt->fetchAll();
    send_json(['data' => $rows]);
  }

  if ($method === 'POST') {
    $device_id = $_POST['device_id'] ?? '';
    $content_id = isset($_POST['content_id']) ? (int)$_POST['content_id'] : 0;
    if ($device_id === '' || $content_id <= 0) {
      send_json(['error' => 'device_id and content_id required'], 400);
    }
    $stmt = $pdo->prepare('INSERT INTO watch_later (device_id, content_id) VALUES (:d, :c)
                            ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP');
    $stmt->execute([':d' => $device_id, ':c' => $content_id]);
    send_json(['ok' => true]);
  }

  if ($method === 'DELETE') {
    $device_id = $_POST['device_id'] ?? '';
    $content_id = isset($_POST['content_id']) ? (int)$_POST['content_id'] : 0;
    if ($device_id === '' || $content_id <= 0) {
      send_json(['error' => 'device_id and content_id required'], 400);
    }
    $stmt = $pdo->prepare('DELETE FROM watch_later WHERE device_id = :d AND content_id = :c');
    $stmt->execute([':d' => $device_id, ':c' => $content_id]);
    send_json(['ok' => true]);
  }

  send_json(['error' => 'Method not allowed'], 405);
} catch (Throwable $e) {
  send_json(['error' => 'Server error'], 500);
}

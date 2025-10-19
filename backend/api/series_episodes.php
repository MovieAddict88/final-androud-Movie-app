<?php
require_once __DIR__ . '/../lib/config.php';
require_once __DIR__ . '/../lib/db.php';

$series_id = isset($_GET['series_id']) ? (int)$_GET['series_id'] : 0;
if ($series_id <= 0) {
  send_json(['error' => 'Invalid series_id'], 400);
}

try {
  $pdo = get_pdo();
  $stmt = $pdo->prepare('SELECT id, season_number, episode_number, title, description, video_url
                          FROM episodes WHERE series_id = :sid ORDER BY season_number ASC, episode_number ASC');
  $stmt->execute([':sid' => $series_id]);
  $rows = $stmt->fetchAll();
  send_json(['data' => $rows]);
} catch (Throwable $e) {
  send_json(['error' => 'Server error'], 500);
}

<?php
require_once __DIR__ . '/../lib/config.php';
require_once __DIR__ . '/../lib/db.php';

$type = $_GET['type'] ?? '';
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = min(100, max(1, (int)($_GET['per_page'] ?? 20)));
$offset = ($page - 1) * $per_page;

if (!in_array($type, ['movie','series','live'], true)) {
  send_json(['error' => 'Invalid type'], 400);
}

try {
  $pdo = get_pdo();

  $where = 'WHERE type = :type';
  $params = [':type' => $type];
  if ($category_id) {
    $where .= ' AND category_id = :category_id';
    $params[':category_id'] = $category_id;
  }

  $sql = "SELECT id, title, description, type, category_id, poster_url, backdrop_url, video_url, is_featured, created_at
          FROM contents $where
          ORDER BY created_at DESC
          LIMIT :limit OFFSET :offset";
  $stmt = $pdo->prepare($sql);
  foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
  }
  $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  $rows = $stmt->fetchAll();

  send_json(['data' => $rows, 'page' => $page, 'per_page' => $per_page]);
} catch (Throwable $e) {
  send_json(['error' => 'Server error'], 500);
}

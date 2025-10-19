<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/config.php';
require_once __DIR__ . '/../lib/db.php';
require_admin();

$pdo = get_pdo();
$categoryCount = $pdo->query('SELECT COUNT(*) AS c FROM categories')->fetch()['c'] ?? 0;
$contentCount = $pdo->query('SELECT COUNT(*) AS c FROM contents')->fetch()['c'] ?? 0;
$seriesCount = $pdo->query("SELECT COUNT(*) AS c FROM contents WHERE type='series'")->fetch()['c'] ?? 0;
$movieCount = $pdo->query("SELECT COUNT(*) AS c FROM contents WHERE type='movie'")->fetch()['c'] ?? 0;
$liveCount = $pdo->query("SELECT COUNT(*) AS c FROM contents WHERE type='live'")->fetch()['c'] ?? 0;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard</title>
  <style>body{font-family:sans-serif;max-width:960px;margin:2rem auto;padding:0 1rem}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem}.card{border:1px solid #ddd;border-radius:8px;padding:1rem}</style>
</head>
<body>
  <h2>Dashboard</h2>
  <nav>
    <a href="/backend/admin/categories.php">Categories</a> |
    <a href="/backend/admin/contents.php">Contents</a> |
    <a href="/backend/admin/logout.php">Logout</a>
  </nav>
  <div class="grid" style="margin-top:1rem">
    <div class="card">Categories: <?php echo (int)$categoryCount; ?></div>
    <div class="card">Contents: <?php echo (int)$contentCount; ?></div>
    <div class="card">Movies: <?php echo (int)$movieCount; ?></div>
    <div class="card">Series: <?php echo (int)$seriesCount; ?></div>
    <div class="card">Live: <?php echo (int)$liveCount; ?></div>
  </div>
</body>
</html>

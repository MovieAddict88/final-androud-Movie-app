<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/db.php';
require_admin();
$pdo = get_pdo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['create'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type = $_POST['type'] ?? 'movie';
    $category_id = (int)($_POST['category_id'] ?? 0);
    $poster_url = trim($_POST['poster_url'] ?? '');
    $backdrop_url = trim($_POST['backdrop_url'] ?? '');
    $video_url = trim($_POST['video_url'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    if ($title !== '' && in_array($type, ['movie','series','live'], true)) {
      $stmt = $pdo->prepare('INSERT INTO contents (title, description, type, category_id, poster_url, backdrop_url, video_url, is_featured)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
      $stmt->execute([$title, $description, $type, $category_id ?: null, $poster_url, $backdrop_url, $video_url, $is_featured]);
    }
  }
  if (isset($_POST['delete'])) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
      $stmt = $pdo->prepare('DELETE FROM contents WHERE id = ?');
      $stmt->execute([$id]);
    }
  }
}

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name ASC')->fetchAll();
$rows = $pdo->query('SELECT id, title, type, category_id, poster_url FROM contents ORDER BY created_at DESC LIMIT 200')->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contents</title>
  <style>body{font-family:sans-serif;max-width:1100px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;width:100%}td,th{border:1px solid #ddd;padding:.5rem}.row{display:grid;gap:.5rem;grid-template-columns:repeat(2, minmax(0,1fr))}input,select,textarea{padding:.25rem .5rem;width:100%}</style>
</head>
<body>
  <h2>Contents</h2>
  <nav>
    <a href="/backend/admin/dashboard.php">Dashboard</a> |
    <a href="/backend/admin/categories.php">Categories</a> |
    <a href="/backend/admin/logout.php">Logout</a>
  </nav>

  <h3>Create Content</h3>
  <form method="post">
    <div class="row">
      <label>Title <input name="title" required></label>
      <label>Type
        <select name="type">
          <option value="movie">Movie</option>
          <option value="series">Series</option>
          <option value="live">Live TV</option>
        </select>
      </label>
      <label>Category
        <select name="category_id">
          <option value="">None</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?php echo (int)$c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Poster URL <input name="poster_url"></label>
      <label>Backdrop URL <input name="backdrop_url"></label>
      <label>Video URL <input name="video_url"></label>
      <label style="grid-column:1/-1">Description <textarea name="description" rows="3"></textarea></label>
      <label><input type="checkbox" name="is_featured"> Featured</label>
    </div>
    <button name="create" value="1">Create</button>
  </form>

  <h3 style="margin-top:1rem">All Contents</h3>
  <table>
    <thead><tr><th>ID</th><th>Title</th><th>Type</th><th>Category</th><th>Poster</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?php echo (int)$r['id']; ?></td>
        <td><?php echo htmlspecialchars($r['title']); ?></td>
        <td><?php echo htmlspecialchars($r['type']); ?></td>
        <td><?php echo (int)$r['category_id']; ?></td>
        <td><?php if ($r['poster_url']): ?><img src="<?php echo htmlspecialchars($r['poster_url']); ?>" width="60"><?php endif; ?></td>
        <td>
          <form method="post" onsubmit="return confirm('Delete?')">
            <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
            <button name="delete" value="1">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>

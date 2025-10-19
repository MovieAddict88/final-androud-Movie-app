<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/db.php';
require_admin();
$pdo = get_pdo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['create'])) {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if ($name !== '' && $slug !== '') {
      $stmt = $pdo->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)');
      $stmt->execute([$name, $slug]);
    }
  }
  if (isset($_POST['delete'])) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
      $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
      $stmt->execute([$id]);
    }
  }
}

$rows = $pdo->query('SELECT id, name, slug FROM categories ORDER BY name ASC')->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Categories</title>
  <style>body{font-family:sans-serif;max-width:960px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;width:100%}td,th{border:1px solid #ddd;padding:.5rem}.row{display:flex;gap:.5rem}input{padding:.25rem .5rem}</style>
</head>
<body>
  <h2>Categories</h2>
  <nav>
    <a href="/backend/admin/dashboard.php">Dashboard</a> |
    <a href="/backend/admin/contents.php">Contents</a> |
    <a href="/backend/admin/logout.php">Logout</a>
  </nav>

  <h3>Create Category</h3>
  <form method="post" class="row">
    <input name="name" placeholder="Name" required>
    <input name="slug" placeholder="Slug" required>
    <button name="create" value="1">Create</button>
  </form>

  <h3 style="margin-top:1rem">All Categories</h3>
  <table>
    <thead><tr><th>ID</th><th>Name</th><th>Slug</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?php echo (int)$r['id']; ?></td>
        <td><?php echo htmlspecialchars($r['name']); ?></td>
        <td><?php echo htmlspecialchars($r['slug']); ?></td>
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

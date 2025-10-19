<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/db.php';
require_admin();
$pdo = get_pdo();

$seriesList = $pdo->query("SELECT id, title FROM contents WHERE type='series' ORDER BY title ASC")->fetchAll();
$selectedSeriesId = isset($_GET['series_id']) ? (int)$_GET['series_id'] : (count($seriesList) ? (int)$seriesList[0]['id'] : 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['create'])) {
    $sid = (int)($_POST['series_id'] ?? 0);
    $season = (int)($_POST['season_number'] ?? 1);
    $episode = (int)($_POST['episode_number'] ?? 1);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $video_url = trim($_POST['video_url'] ?? '');
    if ($sid > 0 && $title !== '' && $video_url !== '') {
      $stmt = $pdo->prepare('INSERT INTO episodes (series_id, season_number, episode_number, title, description, video_url) VALUES (?,?,?,?,?,?)');
      $stmt->execute([$sid, $season, $episode, $title, $description, $video_url]);
      $selectedSeriesId = $sid;
    }
  }
  if (isset($_POST['delete'])) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
      $stmt = $pdo->prepare('DELETE FROM episodes WHERE id = ?');
      $stmt->execute([$id]);
    }
  }
}

$episodes = [];
if ($selectedSeriesId > 0) {
  $stmt = $pdo->prepare('SELECT id, season_number, episode_number, title FROM episodes WHERE series_id = ? ORDER BY season_number ASC, episode_number ASC');
  $stmt->execute([$selectedSeriesId]);
  $episodes = $stmt->fetchAll();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Episodes</title>
  <style>body{font-family:sans-serif;max-width:1100px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;width:100%}td,th{border:1px solid #ddd;padding:.5rem}.row{display:grid;gap:.5rem;grid-template-columns:repeat(2, minmax(0,1fr))}input,select,textarea{padding:.25rem .5rem;width:100%}</style>
</head>
<body>
  <h2>Episodes</h2>
  <nav>
    <a href="/admin/dashboard.php">Dashboard</a> |
    <a href="/admin/categories.php">Categories</a> |
    <a href="/admin/contents.php">Contents</a> |
    <a href="/admin/logout.php">Logout</a>
  </nav>

  <h3>Select Series</h3>
  <form method="get">
    <select name="series_id" onchange="this.form.submit()">
      <?php foreach ($seriesList as $s): $sid=(int)$s['id']; ?>
        <option value="<?php echo $sid; ?>" <?php if ($sid === $selectedSeriesId) echo 'selected'; ?>><?php echo htmlspecialchars($s['title']); ?></option>
      <?php endforeach; ?>
    </select>
  </form>

  <h3>Create Episode</h3>
  <form method="post">
    <input type="hidden" name="series_id" value="<?php echo (int)$selectedSeriesId; ?>">
    <div class="row">
      <label>Season <input name="season_number" type="number" value="1" min="1"></label>
      <label>Episode <input name="episode_number" type="number" value="1" min="1"></label>
      <label>Title <input name="title" required></label>
      <label style="grid-column:1/-1">Description <textarea name="description" rows="3"></textarea></label>
      <label style="grid-column:1/-1">Video URL <input name="video_url" required></label>
    </div>
    <button name="create" value="1">Create</button>
  </form>

  <h3 style="margin-top:1rem">Episodes</h3>
  <table>
    <thead><tr><th>ID</th><th>Season</th><th>Episode</th><th>Title</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($episodes as $e): ?>
      <tr>
        <td><?php echo (int)$e['id']; ?></td>
        <td><?php echo (int)$e['season_number']; ?></td>
        <td><?php echo (int)$e['episode_number']; ?></td>
        <td><?php echo htmlspecialchars($e['title']); ?></td>
        <td>
          <form method="post" onsubmit="return confirm('Delete?')">
            <input type="hidden" name="id" value="<?php echo (int)$e['id']; ?>">
            <button name="delete" value="1">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>

<?php
require_once __DIR__ . '/../lib/config.php';
require_once __DIR__ . '/../lib/auth.php';

if (!empty($_SESSION['admin_logged_in'])) {
  header('Location: /backend/admin/dashboard.php');
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = $_POST['username'] ?? '';
  $p = $_POST['password'] ?? '';
  global $ADMIN_USER, $ADMIN_PASS;
  if ($u === $ADMIN_USER && $p === $ADMIN_PASS) {
    $_SESSION['admin_logged_in'] = true;
    header('Location: /backend/admin/dashboard.php');
    exit;
  } else {
    $error = 'Invalid credentials';
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login</title>
  <style>body{font-family:sans-serif;max-width:420px;margin:3rem auto}label{display:block;margin:.5rem 0}.err{color:#b00}</style>
</head>
<body>
  <h2>Admin Login</h2>
  <?php if ($error): ?><p class="err"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
  <form method="post">
    <label>Username <input name="username" required></label>
    <label>Password <input name="password" type="password" required></label>
    <button type="submit">Login</button>
  </form>
</body>
</html>

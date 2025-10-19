<?php
require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function require_admin(): void {
  if (empty($_SESSION['admin_logged_in'])) {
    header('Location: /admin/index.php');
    exit;
  }
}

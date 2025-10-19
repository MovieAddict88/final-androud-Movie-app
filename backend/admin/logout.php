<?php
require_once __DIR__ . '/../lib/auth.php';
$_SESSION = [];
session_destroy();
header('Location: /backend/admin/index.php');
exit;

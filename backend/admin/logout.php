<?php
require_once __DIR__ . '/../lib/auth.php';
$_SESSION = [];
session_destroy();
header('Location: /admin/index.php');
exit;

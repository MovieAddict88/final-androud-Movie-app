<?php
// Basic configuration and CORS handling

// Allow overriding via environment variables
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('DB_NAME') ?: 'stream_app';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

// Simple admin credentials (change in production!)
$ADMIN_USER = getenv('ADMIN_USER') ?: 'admin';
$ADMIN_PASS = getenv('ADMIN_PASS') ?: 'admin123';

// CORS
if (isset($_SERVER['REQUEST_METHOD'])) {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type, Authorization');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
  }
}

function send_json($data, int $status = 200): void {
  http_response_code($status);
  header('Content-Type: application/json');
  echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  exit;
}

function get_env_db_config(): array {
  global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS;
  return [
    'host' => $DB_HOST,
    'name' => $DB_NAME,
    'user' => $DB_USER,
    'pass' => $DB_PASS,
  ];
}

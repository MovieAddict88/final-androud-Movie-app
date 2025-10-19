<?php
require_once __DIR__ . '/../lib/config.php';

$routes = [
  'GET /categories.php' => 'List categories',
  'GET /contents.php?type={movie|series|live}&category_id={id?}&page=1&per_page=20' => 'List contents',
  'GET /home.php' => 'Featured contents for Home',
  'GET /series_episodes.php?series_id={id}' => 'List episodes for a series',
  'GET /watchlater.php?device_id={device}' => 'List watch later items',
  'POST /watchlater.php {device_id, content_id}' => 'Add to watch later',
  'POST /watchlater.php {_method: DELETE, device_id, content_id}' => 'Remove from watch later'
];

send_json(['name' => 'Streaming API', 'routes' => $routes]);

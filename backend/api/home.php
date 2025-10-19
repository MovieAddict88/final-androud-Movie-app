<?php
require_once __DIR__ . '/../lib/config.php';
require_once __DIR__ . '/../lib/db.php';

try {
  $pdo = get_pdo();

  $featured = $pdo->query("SELECT id, title, description, type, poster_url, backdrop_url, video_url
                            FROM contents WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 20")->fetchAll();

  $latestMovies = $pdo->query("SELECT id, title, description, poster_url, video_url FROM contents WHERE type='movie' ORDER BY created_at DESC LIMIT 20")->fetchAll();
  $latestSeries = $pdo->query("SELECT id, title, description, poster_url FROM contents WHERE type='series' ORDER BY created_at DESC LIMIT 20")->fetchAll();
  $liveChannels = $pdo->query("SELECT id, title, description, poster_url, video_url FROM contents WHERE type='live' ORDER BY created_at DESC LIMIT 50")->fetchAll();

  send_json([
    'featured' => $featured,
    'latestMovies' => $latestMovies,
    'latestSeries' => $latestSeries,
    'live' => $liveChannels,
  ]);
} catch (Throwable $e) {
  send_json(['error' => 'Server error'], 500);
}

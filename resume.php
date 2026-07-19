<?php
// PWA cold-launch entry point (manifest start_url = /resume).
//
// iOS kills backgrounded home-screen apps to reclaim memory; when reopened they
// relaunch from start_url. This page reads the last location saved by nav.php
// and redirects there, restoring the user's spot instead of dumping them on a
// random verse. First-time visitors (no saved state) fall back to a random verse,
// preserving the app's original homepage behavior.
//
// This is intentionally NOT the public homepage: crawlers hit "/" (index.php),
// which keeps its clean random-verse 302. /resume is noindex and JS-only.
require_once 'bible-view-helpers.php';

$book = array_rand($new_testament);
$chapter = rand(1, $new_testament[$book]);
$verse = rand(1, $lookup_versestotals["$book|$chapter"]);
$fallback = '/' . formatBookName($book) . '/' . $chapter . '/' . $verse;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="robots" content="noindex">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="HCF Bible">
<meta name="theme-color" content="#080d15">
<link rel="manifest" href="/manifest.json">
<title>Resuming…</title>
<style>html, body { background: #080d15; margin: 0; height: 100%; }</style>
<script>
(function () {
  var fallback = <?= json_encode($fallback) ?>;
  try {
    var saved = JSON.parse(localStorage.getItem('hcf:lastLocation') || 'null');
    if (saved && saved.url) {
      // Signal nav.php on the destination page to restore the saved scroll.
      sessionStorage.setItem('hcf:resuming', '1');
      location.replace(saved.url);
      return;
    }
  } catch (e) {}
  location.replace(fallback);
})();
</script>
</head>
<body></body>
</html>

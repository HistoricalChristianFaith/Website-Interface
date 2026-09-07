<?php
// Endpoint for the home-page "fire" easter egg: returns the curated "spicy"
// quotes grouped by timeline name. Fetched on demand (only when the egg is lit)
// so the landing page ships none of this. The data is static per deploy, so it
// is safe for the browser to cache for a day.
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=86400');
$grouped = require __DIR__ . '/spicy-data.php';
echo json_encode($grouped, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

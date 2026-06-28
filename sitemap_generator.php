<?php

if($_GET['hieronymus'] != "jerome") {
    // To try to avoid bots/search engines from triggering this.
    http_response_code(404);
    die;
}

require __DIR__ . '/bible-view-helpers.php'; // $lookup_chaptertotals (book => chapter count)

// Base URL of your site
$base_url = 'https://historicalchristian.faith';

// Append one <url> entry to the sitemap.
function addUrl(&$sitemap, $loc, $lastmod, $changefreq, $priority) {
    $sitemap .= '    <url>' . PHP_EOL;
    $sitemap .= '        <loc>' . htmlspecialchars($loc) . '</loc>' . PHP_EOL;
    $sitemap .= '        <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
    $sitemap .= '        <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
    $sitemap .= '        <priority>' . $priority . '</priority>' . PHP_EOL;
    $sitemap .= '    </url>' . PHP_EOL;
}

// Start XML sitemap structure
$sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// ---------------------------------------------------------------------------
// 1) Bible chapter URLs (/book/chapter)
// The authoritative book list + ordering lives in book_names.json
// (canonical_names); chapter counts come from bible-view-helpers.php. Drive the
// loop from the JSON so the sitemap follows the one canonical list, and look up
// each book's chapter count by name.
// ---------------------------------------------------------------------------
$today = date('Y-m-d');
$bible_count = 0;
$missing = [];

$book_names = json_decode(file_get_contents(__DIR__ . '/book_names.json'), true);
$canonical_names = $book_names['canonical_names'] ?? [];

foreach ($canonical_names as $book) {
    if (!isset($lookup_chaptertotals[$book])) {
        // In the canonical list but no chapter count known — skip + report below.
        $missing[] = $book;
        continue;
    }
    $chapters = $lookup_chaptertotals[$book];
    $bookUrl = strtolower(str_replace(' ', '', $book)); // lowercase, strip spaces (matches /book/ch routing)
    for ($chapter = 1; $chapter <= $chapters; $chapter++) {
        addUrl($sitemap, "$base_url/$bookUrl/$chapter", $today, 'weekly', '0.8');
        $bible_count++;
    }
}

// ---------------------------------------------------------------------------
// 2) Doctrine wiki URLs (/doctrine/...)
// The wiki is the standalone static site deployed beside this app and served at
// real per-page URLs by doctrine-render.php. Walk every .html page and emit its
// public URL so each doctrine/person/argument page is discoverable. (Same root
// resolution as doctrine-render.php.)
// ---------------------------------------------------------------------------
$doctrine_root = null;
foreach ([
    __DIR__ . '/../doctrine-database',
    __DIR__ . '/../doctrine-database/docs',
] as $cand) {
    if (is_file($cand . '/index.html')) { $doctrine_root = realpath($cand); break; }
}

$doctrine_count = 0;
if ($doctrine_root !== null) {
    $pages = []; // relative path => mtime
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($doctrine_root, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'html') {
            continue;
        }
        $rel = substr($file->getPathname(), strlen($doctrine_root) + 1);
        $rel = str_replace(DIRECTORY_SEPARATOR, '/', $rel);
        $pages[$rel] = $file->getMTime();
    }
    ksort($pages);
    foreach ($pages as $rel => $mtime) {
        // The home page's canonical URL is /doctrine/ (not /doctrine/index.html).
        $loc = ($rel === 'index.html') ? "$base_url/doctrine/" : "$base_url/doctrine/$rel";
        addUrl($sitemap, $loc, date('Y-m-d', $mtime), 'monthly', '0.7');
        $doctrine_count++;
    }
}

// Close XML sitemap structure
$sitemap .= '</urlset>';

// Save sitemap to a file
file_put_contents(__DIR__ . '/sitemap.xml', $sitemap);

echo "Sitemap generated successfully!" . PHP_EOL;
echo "  Bible URLs:    $bible_count" . PHP_EOL;
echo "  Doctrine URLs: $doctrine_count" . ($doctrine_root === null ? " (wiki not found beside app)" : "") . PHP_EOL;
if ($missing) {
    echo "  WARNING: in book_names.json but no chapter count: " . implode(', ', $missing) . PHP_EOL;
}

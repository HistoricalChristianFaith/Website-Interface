<?php

if($_GET['hieronymus'] != "jerome") {
    // To try to avoid bots/search engines from triggering this.
    http_response_code(404);
    die;
}

require("bible-view-helpers.php");

// Base URL of your site
$base_url = 'https://historicalchristian.faith';

// Start XML sitemap structure
$sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// Function to add each book and chapter to the sitemap
function addToSitemap($bookArray, &$sitemap, $base_url) {
    foreach ($bookArray as $book => $chapters) {
        $bookUrl = strtolower(str_replace(' ', '', $book)); // Convert book names to lowercase, strip spaces
        for ($chapter = 1; $chapter <= $chapters; $chapter++) {
            $sitemap .= '    <url>' . PHP_EOL;
            $sitemap .= '        <loc>' . htmlspecialchars("$base_url/$bookUrl/$chapter") . '</loc>' . PHP_EOL;
            $sitemap .= '        <lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL; // Today's date as the last modified date
            $sitemap .= '        <changefreq>weekly</changefreq>' . PHP_EOL;
            $sitemap .= '        <priority>0.8</priority>' . PHP_EOL;
            $sitemap .= '    </url>' . PHP_EOL;
        }
    }
}

// Add Old Testament URLs
addToSitemap($old_testament, $sitemap, $base_url);

// Add New Testament URLs
addToSitemap($new_testament, $sitemap, $base_url);

// Close XML sitemap structure
$sitemap .= '</urlset>';

// Save sitemap to a file
file_put_contents('sitemap.xml', $sitemap);

echo "Sitemap generated successfully!";

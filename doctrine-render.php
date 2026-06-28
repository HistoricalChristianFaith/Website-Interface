<?php
/**
 * doctrine-render.php — serve the standalone "Doctrine Across Time" wiki under the
 * shared HistoricalChristian.Faith chrome, at real per-page URLs (no iframe).
 *
 * The wiki is a self-contained static site (deployed beside this app at
 * ../doctrine-database/docs by its own FTP deploy). Those files render perfectly on
 * their own — open index.html off a GitHub download and every depth-relative link and
 * footnote works. The site header is NOT baked into them; it exists only here, injected
 * server-side, so the standalone copy stays pristine.
 *
 * Routing (see .htaccess): every request under /doctrine/ is internally rewritten here
 * with ?p=<relative path>. The browser keeps the real URL (e.g.
 * /doctrine/doctrines/canon.html), so the page's own relative links + assets resolve
 * correctly and crawlers index each page on its own URL.
 *  - *.html  -> read the file, inject the header (+ a small layout shim), echo it.
 *  - assets  -> stream verbatim with the right Content-Type, caching + conditional GET.
 */

const DOCTRINE_URL_BASE = '/doctrine/'; // public mount; must match the .htaccess rules
const CSS_VERSION       = '3';          // bible-view.css?v=… (keep in step with the rest of the site)
const HEADER_PX         = 60;           // sticky .hcf-header height to clear (56px + a little)

/** Insert $insert immediately after the first match of $pattern; prepend if no match. */
function insert_after(string $haystack, string $pattern, string $insert): string {
    if (preg_match($pattern, $haystack, $m, PREG_OFFSET_CAPTURE)) {
        $pos = $m[0][1] + strlen($m[0][0]);
        return substr($haystack, 0, $pos) . $insert . substr($haystack, $pos);
    }
    return $insert . $haystack;
}

/** Insert $insert immediately before the first match of $pattern; append if no match. */
function insert_before(string $haystack, string $pattern, string $insert): string {
    if (preg_match($pattern, $haystack, $m, PREG_OFFSET_CAPTURE)) {
        $pos = $m[0][1];
        return substr($haystack, 0, $pos) . $insert . substr($haystack, $pos);
    }
    return $haystack . $insert;
}

// 1) Locate the deployed wiki root on disk (a filesystem path — may be outside the docroot).
$root = null;
foreach ([
    __DIR__ . '/../doctrine-database',
    __DIR__ . '/../doctrine-database/docs'
] as $cand) {
    if (is_file($cand . '/index.html')) { $root = realpath($cand); break; }
}
if ($root === null) {
    http_response_code(503);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Doctrine wiki is not deployed.';
    exit;
}

// 2) Resolve + sanitize the requested path.
$rel = isset($_GET['p']) ? (string) $_GET['p'] : '';
$rel = str_replace('\\', '/', $rel);
$rel = ltrim($rel, '/');
if ($rel === '') {
    $rel = 'index.html';
}

$path = realpath($root . '/' . $rel);
if ($path !== false && is_dir($path)) {           // a directory -> its index.html
    $rel  = rtrim($rel, '/') . '/index.html';
    $path = realpath($root . '/' . $rel);
}

// Must resolve to a real file living strictly under $root (blocks ../ traversal).
$prefix = $root . DIRECTORY_SEPARATOR;
if ($path === false || strncmp($path, $prefix, strlen($prefix)) !== 0 || !is_file($path)) {
    http_response_code(404);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><html lang="en"><meta charset="utf-8">'
       . '<title>Not found | Doctrine Across Time</title>'
       . '<body style="font-family:system-ui,sans-serif;background:#181818;color:#ddd;padding:2rem">'
       . '<h1>Page not found</h1><p><a style="color:#8ab4f8" href="' . DOCTRINE_URL_BASE . '">'
       . 'Back to Doctrine Across Time</a></p>';
    exit;
}

// 3) Content type by extension.
$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$types = [
    'html' => 'text/html; charset=utf-8',  'css'  => 'text/css; charset=utf-8',
    'js'   => 'text/javascript; charset=utf-8', 'json' => 'application/json; charset=utf-8',
    'svg'  => 'image/svg+xml', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
    'gif'  => 'image/gif', 'webp' => 'image/webp', 'avif' => 'image/avif', 'ico' => 'image/x-icon',
    'woff' => 'font/woff', 'woff2' => 'font/woff2', 'ttf' => 'font/ttf', 'otf' => 'font/otf',
    'txt'  => 'text/plain; charset=utf-8', 'xml' => 'application/xml',
    'webmanifest' => 'application/manifest+json', 'map' => 'application/json',
];
$ctype = $types[$ext] ?? 'application/octet-stream';

// 4) Non-HTML assets: stream verbatim, cacheable, with conditional-GET (304) support.
if ($ext !== 'html') {
    $mtime = filemtime($path);
    $etag  = '"' . dechex($mtime) . '-' . dechex(filesize($path)) . '"';
    header('Content-Type: ' . $ctype);
    header('Cache-Control: public, max-age=3600');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
    header('ETag: ' . $etag);

    $inm = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : '';
    $ims = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) : 0;
    if ($inm === $etag || ($ims && $ims >= $mtime)) {
        http_response_code(304);
        exit;
    }
    readfile($path);
    exit;
}

// 5) HTML: inject the shared header so the page sits under the site chrome.
$html = file_get_contents($path);

// 5a) Build the header markup once (nav.php emits it; bible-view.css styles it).
$current_page = 'doctrine';
ob_start();
include __DIR__ . '/nav.php';
$nav = ob_get_clean();

// 5b) The header's stylesheet goes right after <head>, BEFORE the wiki's own style.css,
// so the wiki still wins on shared properties (page background, body font). We only want
// bible-view.css for the header itself.
$canonical = DOCTRINE_URL_BASE . ($rel === 'index.html' ? '' : $rel);
$headTop = "\n"
    . '<link rel="stylesheet" href="/bible-view.css?v=' . CSS_VERSION . '">' . "\n"
    . '<link rel="canonical" href="' . htmlspecialchars($canonical, ENT_QUOTES) . '">' . "\n";

// 5c) The layout shim goes just before </head>, AFTER style.css, so it wins. The wiki
// uses <body> itself as a centered reading column, so an injected header would inherit
// that column (indented + pushed down by the body's top margin). Pin it full-width at the
// top like every other site page, offset the content below it, and clear the header for
// in-page anchor jumps (footnotes) and the wiki's fixed side TOC.
$shim = "<style>\n"
    . "/* doctrine-render.php — pin the injected site header over the wiki's reading column */\n"
    . ".hcf-header { position: fixed; top: 0; left: 0; right: 0; width: 100%; z-index: 100; }\n"
    . "body { margin-top: 0 !important; padding-top: calc(" . HEADER_PX . "px + 1.25rem); }\n"
    . "html { scroll-padding-top: calc(" . HEADER_PX . "px + 0.5rem); }\n"
    . "@media (min-width: 78.5em) { .toc { top: calc(2.5rem + " . HEADER_PX . "px) !important; } }\n"
    . "</style>\n";

$html = insert_after($html, '/<head[^>]*>/i', $headTop);
$html = insert_before($html, '/<\/head>/i', $shim);
$html = insert_after($html, '/<body[^>]*>/i', "\n" . $nav . "\n");

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: public, max-age=0, must-revalidate');
echo $html;

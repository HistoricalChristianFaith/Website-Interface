<?php
/**
 * by_father.php — serve the standardized Writings-Database corpus under the shared
 * HistoricalChristian.Faith chrome, at real per-page URLs (no iframe).
 *
 * This file used to be a SPA: a jsTree sidebar + a cross-origin <iframe> that
 * fetched each writing from GitHub Pages. It now SERVER-RENDERS each writing so a
 * visitor landing from Google and a visitor navigating in-site get the identical
 * fully-rendered page. The URL contract is unchanged — every existing
 * `?file=<Author>/<Work>.html` link keeps working and is now crawlable.
 *
 * The corpus is deployed beside this app at ../writings-database/ (its own FTP
 * deploy, exactly like the Doctrine wiki). Each writing is a bare
 * `<article class="writing">` fragment; this script builds the whole page around it.
 *
 * Request shapes:
 *   - no ?file            -> the Writings landing page (tree + welcome).
 *   - ?file=<html>        -> full server-rendered page (head + chrome + tree + article).
 *   - ?file=<html>&partial=1 -> JSON {title, canonical, breadcrumb, html} for the
 *                            client-side article swap (no full reload on tree clicks).
 *   - ?file=<pdf>         -> a writings page with the PDF embedded in the reading
 *                            column (also supports &partial=1).
 *   - ?file=<pdf>&raw=1   -> the PDF bytes themselves (what the embed loads).
 *   - ?file=<asset>       -> stream the asset (images, …) with caching.
 *
 * Mirrors the hardened path-resolution/asset-streaming from doctrine-render.php.
 */

const SITE_ORIGIN       = 'https://historicalchristian.faith'; // canonical origin (always production)
const WRITINGS_SCRIPT   = '/by_father.php';
const CSS_VERSION       = '4';

// ---------------------------------------------------------------------------
// 1) Locate the deployed corpus root on disk beside the app.
//    Production uses lowercase ../writings-database (created + deployed manually);
//    the lowercase candidate also resolves the mixed-case local checkout on
//    case-insensitive macOS.
// ---------------------------------------------------------------------------
$root = null;
foreach ([
    __DIR__ . '/../writings-database',
    __DIR__ . '/../Writings-Database',
] as $cand) {
    if (is_dir($cand) && is_file($cand . '/menu.html')) { $root = realpath($cand); break; }
}
if ($root === null) {
    http_response_code(503);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Writings database is not deployed.';
    exit;
}

$isPartial = isset($_GET['partial']) && $_GET['partial'] === '1';
$fileParam = isset($_GET['file']) ? (string) $_GET['file'] : '';

// ---------------------------------------------------------------------------
// 2) No ?file -> the landing page (tree + welcome). Server-rendered like any page.
// ---------------------------------------------------------------------------
if ($fileParam === '') {
    if ($isPartial) { // a swap back to the landing state -> tell the client to full-load
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['fullReload' => true]);
        exit;
    }
    render_full_page($root, null, null);
    exit;
}

// ---------------------------------------------------------------------------
// 3) Resolve + sanitize ?file= (backwards-compatible double-decode).
//    The client double-encodes (menu.html's data-fname is already %-encoded, then
//    searchParams/encodeURIComponent encodes it a second time). PHP auto-decodes
//    $_GET once; decode once MORE to reach the real on-disk name. Author/work
//    names contain no literal '%' or '+', so this is also a no-op for the older
//    single-encoded links — both resolve. Do NOT normalize the scheme.
// ---------------------------------------------------------------------------
$rel = urldecode($fileParam);
$rel = str_replace('\\', '/', $rel);
$rel = ltrim($rel, '/');

$path   = realpath($root . '/' . $rel);
$prefix = $root . DIRECTORY_SEPARATOR;
if ($path === false || strncmp($path, $prefix, strlen($prefix)) !== 0 || !is_file($path)) {
    if ($isPartial) {
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'not_found']);
        exit;
    }
    render_404();
    exit;
}

$ext   = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$isRaw = isset($_GET['raw']) && $_GET['raw'] === '1';

// ---------------------------------------------------------------------------
// 4a) PDF-only works: the ?file= URL is a normal writings page with the PDF
//     embedded in the reading column (as the old iframe app showed it inside the
//     chrome). The PDF bytes themselves are served at ?file=…&raw=1.
// ---------------------------------------------------------------------------
if ($ext === 'pdf' && !$isRaw) {
    $writing = render_writing($root, $rel, pdf_article_html($rel));
    if ($isPartial) {
        send_partial($writing);
        exit;
    }
    render_full_page($root, $rel, $writing);
    exit;
}

// ---------------------------------------------------------------------------
// 4b) Other non-HTML assets (and raw PDF bytes): stream verbatim, cacheable, with
//     conditional-GET (304). (Ported from doctrine-render.php §3–4.)
// ---------------------------------------------------------------------------
if ($ext !== 'html' && $ext !== 'htm') {
    stream_asset($path, $ext);
    exit;
}

// ---------------------------------------------------------------------------
// 5) HTML: standardized fragment (normal path) vs legacy full document.
// ---------------------------------------------------------------------------
$raw = file_get_contents($path);
$isFragment = (strncasecmp(ltrim($raw), '<article', 8) === 0);

if (!$isFragment) {
    // Interim: not-yet-standardized files are legacy full HTML documents. Fall
    // back to Doctrine-style header injection so nothing 404s while standardization
    // finishes (renders standalone, not in the two-panel chrome). A client swap to a
    // legacy file can't slot into the article container, so tell it to full-load.
    if ($isPartial) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['fullReload' => true]);
        exit;
    }
    render_legacy_page($raw, $rel);
    exit;
}

// Standardized fragment.
$writing = render_writing($root, $rel, $raw);

if ($isPartial) {
    send_partial($writing);
    exit;
}

render_full_page($root, $rel, $writing);
exit;


// ===========================================================================
// Helpers
// ===========================================================================

/**
 * Build the four pieces the page (full render) and the swap (partial render) both
 * need: title, canonical URL, breadcrumb HTML, and the article HTML ($articleHtml —
 * the bare <article class="writing"> fragment, or the PDF embed for PDF works).
 * One source of truth so the full-page and partial paths never diverge.
 */
function render_writing(string $root, string $rel, string $articleHtml): array {
    $parts    = explode('/', $rel);
    $author   = $parts[0];
    $fileBase = preg_replace('/\.(html?|pdf)$/i', '', end($parts)); // work-or-part label
    $middle   = array_slice($parts, 1, -1);                   // subfolder groupings

    // Metadata from ../<author>/metadata.toml (default_year, wiki). Coverage is
    // ~339/340 authors; degrade gracefully when absent.
    $meta = read_metadata_toml($root . '/' . $author . '/metadata.toml');
    $year = $meta['default_year'] ?? null;

    $titlePlain = $fileBase . ' — ' . $author . ($year !== null ? " (c. {$year} AD)" : '');
    $canonical  = writings_canonical($rel);

    // Breadcrumb: author › [subfolders › ] work/part. The author links to its
    // first work is overkill; keep crumbs as plain labels except a "Writings" home.
    $crumbLabels = array_merge([$author], $middle, [$fileBase]);
    $crumbHtml   = '<a href="' . WRITINGS_SCRIPT . '">Writings</a>';
    foreach ($crumbLabels as $label) {
        $crumbHtml .= '<span class="sep">›</span>' . htmlspecialchars($label, ENT_QUOTES);
    }

    return [
        'title'      => $titlePlain,
        'canonical'  => $canonical,
        'breadcrumb' => $crumbHtml,
        'html'       => $articleHtml,
    ];
}

/** Emit the partial-render JSON envelope used by the client-side article swap. */
function send_partial(array $writing): void {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: public, max-age=0, must-revalidate');
    echo json_encode($writing, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

/**
 * Article markup for a PDF-only work: title, an "open in a new tab" link, and the
 * PDF embedded in the reading column. The PDF bytes come from ?file=…&raw=1 (a
 * site-relative URL, so it works on localhost too). On phones the frame is hidden
 * by writings.css and the link carries the reading — mobile browsers render PDFs
 * in iframes poorly or not at all.
 */
function pdf_article_html(string $rel): string {
    $parts     = explode('/', $rel);
    $title     = htmlspecialchars(preg_replace('/\.pdf$/i', '', end($parts)), ENT_QUOTES);
    $dataFname = implode('/', array_map('rawurlencode', $parts));
    $src       = htmlspecialchars(WRITINGS_SCRIPT . '?file=' . rawurlencode($dataFname) . '&raw=1', ENT_QUOTES);

    return '<article class="writing writing-pdf">'
        . '<h1>' . $title . '</h1>'
        . '<p class="pdf-open"><a href="' . $src . '" target="_blank" rel="noopener">'
        . '<i class="fas fa-file-pdf"></i> Open PDF in a new tab</a></p>'
        . '<iframe class="pdf-frame" src="' . $src . '" title="' . $title . ' (PDF)"></iframe>'
        . '</article>';
}

/**
 * Canonical/href builder. Reproduces the site's existing double-encoding so the
 * emitted URL is byte-identical to the links already in the wild: percent-encode
 * each path segment (the "data-fname" form, spaces -> %20), then encode the whole
 * thing again (matching the client's encodeURIComponent).
 */
function writings_canonical(string $rel): string {
    $dataFname = implode('/', array_map('rawurlencode', explode('/', $rel)));
    return SITE_ORIGIN . WRITINGS_SCRIPT . '?file=' . rawurlencode($dataFname);
}

/** Minimal TOML reader — only default_year / wiki are needed (no library). */
function read_metadata_toml(string $file): array {
    $out = [];
    if (!is_file($file)) return $out;
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (!preg_match('/^\s*([A-Za-z0-9_]+)\s*=\s*(.+?)\s*$/', $line, $m)) continue;
        $key = $m[1];
        $val = trim($m[2], " \t\"'"); // strip surrounding quotes
        if ($key === 'default_year' && ctype_digit($val)) {
            $out['default_year'] = (int) $val;
        } elseif ($key === 'wiki') {
            $out['wiki'] = $val;
        }
    }
    return $out;
}

/** Read the prebuilt jsTree menu markup from the sibling corpus (interim source). */
function read_menu_html(string $root): string {
    $menu = @file_get_contents($root . '/menu.html');
    return $menu !== false ? $menu : '<p>Menu unavailable.</p>';
}

/**
 * Full server-rendered page: <head> + shared chrome + two-panel layout. $writing is
 * null on the landing page (no ?file=), in which case a welcome note is shown.
 */
function render_full_page(string $root, ?string $rel, ?array $writing): void {
    $menuHtml = read_menu_html($root);

    if ($writing !== null) {
        $title      = $writing['title'];
        $canonical  = $writing['canonical'];
        $breadcrumb = $writing['breadcrumb'];
        $articleHtml = $writing['html'];
        $ogType     = 'article';
    } else {
        $title      = 'Historical Christian Writings';
        $canonical  = SITE_ORIGIN . WRITINGS_SCRIPT;
        $breadcrumb = '';
        $articleHtml = '<div class="writings-welcome">'
            . '<p>Select a writing from the menu to read it here.</p>'
            . '<p>This database is open source and entirely in the public domain. '
            . '<a target="_blank" rel="noopener" href="https://github.com/HistoricalChristianFaith/Writings-Database/">'
            . 'Contribute or fix typos here.</a></p></div>';
        $ogType     = 'website';
    }

    $titleTag = htmlspecialchars($title, ENT_QUOTES) . ' | HistoricalChristian.Faith';
    $ogTitle  = htmlspecialchars($title, ENT_QUOTES);
    $ogUrl    = htmlspecialchars($canonical, ENT_QUOTES);

    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: public, max-age=0, must-revalidate');
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $titleTag ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">

    <link rel="canonical" href="<?= $ogUrl ?>">
    <meta property="og:type" content="<?= $ogType ?>">
    <meta property="og:site_name" content="HistoricalChristian.Faith">
    <meta property="og:title" content="<?= $ogTitle ?>">
    <meta property="og:url" content="<?= $ogUrl ?>">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?= $ogTitle ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <!-- Font Awesome: the jsTree menu (menu.html) sets each node's icon via
         data-jstree='{"icon":"fas fa-folder-open"}' / "fas fa-file-alt". -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="/bible-view.css?v=<?= CSS_VERSION ?>" rel="stylesheet">
    <link href="/writings.css?v=1" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="HCF Bible">
    <meta name="theme-color" content="#080d15">
    <link rel="manifest" href="/manifest.json">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
    <script src="/highlight.js?v=1"></script>
</head>
<body>

<?php $current_page = 'writings'; $has_sidebar = true; include __DIR__ . '/nav.php'; ?>

<div class="writings-shell">
    <nav id="sidebarMenu" class="writings-sidebar">
        <div class="sidebar-sticky">
            <div id="cfmenu"><?= $menuHtml ?></div>
        </div>
    </nav>
    <main role="main" class="writings-main">
        <div class="writings-breadcrumb" id="wbreadcrumb"><?= $breadcrumb ?></div>
        <div id="writing-container"><?= $articleHtml ?></div>
    </main>
</div>

<script>
(function () {
    // Current selected file in the "data-fname" form used throughout menu.html:
    // each path segment percent-encoded (spaces -> %20), joined by '/'. This is the
    // key everything below compares against and re-encodes from. Empty on landing.
    var currentFile = <?= json_encode($rel === null ? '' : implode('/', array_map('rawurlencode', explode('/', $rel))), JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) ?>;

    var $tree = $('#cfmenu');

    // Build the ?file= value for a data-fname, matching writings_canonical() on the
    // server (encodeURIComponent double-encodes: '/' -> %2F, '%' -> %25).
    function fileUrl(dataFname) {
        return <?= json_encode(WRITINGS_SCRIPT) ?> + '?file=' + encodeURIComponent(dataFname);
    }

    function runHighlighter() {
        if (window.highlightWriting) {
            window.highlightWriting(document.querySelector('.writing'));
        }
    }

    // Surface each author row's reference link (data-metadataurl, usually
    // Wikipedia) as a small external-link icon that opens in a new tab. jsTree
    // recreates child DOM when a folder opens, so re-run after each open. The
    // icon stops click propagation so it never toggles/activates the node.
    function decorateWikiLinks() {
        document.querySelectorAll('#cfmenu li[data-metadataurl]').forEach(function (li) {
            var url = li.getAttribute('data-metadataurl');
            if (!url) return;
            var anchor = li.querySelector(':scope > .jstree-anchor');
            if (!anchor || anchor.querySelector('.wiki-link')) return;
            var a = document.createElement('a');
            a.className = 'wiki-link';
            a.href = url;
            a.target = '_blank';
            a.rel = 'noopener';
            a.title = 'Open reference in a new tab';
            a.setAttribute('aria-label', 'Open reference in a new tab');
            a.innerHTML = '<i class="fas fa-external-link-alt"></i>';
            a.addEventListener('click', function (e) { e.stopPropagation(); });
            anchor.appendChild(a);
        });
    }

    // Apply a partial-render JSON payload into the existing shell.
    function applyWriting(data, dataFname, pushHash) {
        document.getElementById('writing-container').innerHTML = data.html;
        document.getElementById('wbreadcrumb').innerHTML = data.breadcrumb || '';
        if (data.title) {
            document.title = data.title + ' | HistoricalChristian.Faith';
        }
        var canon = document.querySelector('link[rel="canonical"]');
        if (canon && data.canonical) canon.setAttribute('href', data.canonical);
        currentFile = dataFname;
        runHighlighter();
        if (!pushHash) window.scrollTo({ top: 0 });
    }

    // Navigate to a writing without a full reload: fetch its partial render, swap
    // the article, update the URL. Falls back to a full navigation on any failure
    // or when the server asks for one (landing state / legacy documents).
    function navigateTo(dataFname, opts) {
        opts = opts || {};
        var url = fileUrl(dataFname);
        fetch(url + '&partial=1', { headers: { 'X-Requested-With': 'fetch' } })
            .then(function (r) {
                if (!r.ok) throw new Error('bad status');
                return r.json();
            })
            .then(function (data) {
                if (data.fullReload) { window.location.assign(url); return; }
                if (!opts.replace) {
                    history.pushState({ file: dataFname }, '', url);
                }
                applyWriting(data, dataFname, false);
            })
            .catch(function () { window.location.assign(url); });
        $('#sidebarMenu').removeClass('show');
        $('.v1-backdrop').removeClass('open');
        $('.hcf-hamburger').attr('aria-expanded', 'false');
    }

    $(document).ready(function () {
        // Bind tree handlers BEFORE .jstree() so we never miss ready.jstree.

        // Decorate reference links on initial render and whenever a folder opens
        // (jsTree rebuilds child DOM on open).
        $tree.on('ready.jstree', decorateWikiLinks);
        $tree.on('after_open.jstree', decorateWikiLinks);

        // On load, expand/select the node for the current ?file= (data-fname form).
        if (currentFile) {
            $tree.on('ready.jstree', function () {
                var tree = $.jstree.reference('#cfmenu');
                var nodes = tree.get_json('#', { flat: true });
                var hit = nodes.find(function (n) {
                    return n.li_attr && n.li_attr['data-fname'] === currentFile;
                });
                if (hit) {
                    tree._open_to(hit.id);
                    tree.select_node(hit.id, true); // suppress -> no swap on programmatic select
                }
            });
        }

        // Tree activation: folders toggle; leaves swap the article in place.
        $tree.on('activate_node.jstree', function (e, data) {
            if (data.node.children.length > 0) {
                $tree.jstree(true).toggle_node(data.node);
                return;
            }
            var fname = data.node.li_attr && data.node.li_attr['data-fname'];
            if (fname && fname !== currentFile) {
                navigateTo(fname);
            }
        });

        $tree.jstree(); // init after handlers are bound

        // Compare two ?file= values in decoded (real-path) space, so the
        // double-encoded and older single-encoded URL forms compare equal.
        function samePath(a, b) {
            function dec(x) { try { return decodeURIComponent(x); } catch (e) { return x; } }
            return dec(a || '') === dec(b || '');
        }

        // Back/forward: re-swap to the URL's ?file= (or full-load the landing page).
        // Browsers also fire popstate for same-page #hash navigation (footnote
        // jumps and their back/forward); when the file hasn't changed, leave the
        // scroll to the browser instead of refetching the article.
        window.addEventListener('popstate', function () {
            var raw = new URLSearchParams(window.location.search).get('file') || '';
            if (samePath(raw, currentFile)) return;
            if (!raw) { window.location.reload(); return; }
            // params.get decoded once -> the data-fname form.
            navigateTo(raw, { replace: true });
        });

        // Wire the shared hamburger (nav.php) to the writings drawer.
        $('.hcf-hamburger').on('click', function () {
            var open = !$('#sidebarMenu').hasClass('show');
            $('#sidebarMenu').toggleClass('show', open);
            $('.v1-backdrop').toggleClass('open', open);
            $(this).attr('aria-expanded', String(open));
        });
        $('.v1-backdrop').on('click', function () {
            $('#sidebarMenu').removeClass('show');
            $(this).removeClass('open');
            $('.hcf-hamburger').attr('aria-expanded', 'false');
        });

        // Initial highlight for a deep-linked quote (#<text>).
        runHighlighter();
    });
})();
</script>

</body>
</html>
    <?php
}

/**
 * Legacy interim fallback: a not-yet-standardized full HTML document, wrapped with
 * the shared site header injected server-side (Doctrine-style). Renders standalone
 * — no two-panel tree — until the file is standardized into a fragment.
 */
function render_legacy_page(string $html, string $rel): void {
    $current_page = 'writings';
    ob_start();
    include __DIR__ . '/nav.php';
    $nav = ob_get_clean();

    $canonical = writings_canonical($rel);
    $ogUrl     = htmlspecialchars($canonical, ENT_QUOTES);

    $pageTitle = '';
    if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $tm)) {
        $pageTitle = trim(html_entity_decode($tm[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
    $ogTitle = htmlspecialchars($pageTitle, ENT_QUOTES);

    // Patch viewport for the installed PWA (safe-area) and inject the site metas +
    // header stylesheet right after <head>.
    $html = preg_replace(
        '/(<meta\s+name=["\']viewport["\']\s+content=["\'])([^"\']*?)(["\'][^>]*>)/i',
        '$1$2, viewport-fit=cover$3',
        $html, 1
    );

    $headTop = "\n"
        . '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n"
        . '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">' . "\n"
        . '<meta name="apple-mobile-web-app-title" content="HCF Bible">' . "\n"
        . '<meta name="theme-color" content="#080d15">' . "\n"
        . '<link rel="manifest" href="/manifest.json">' . "\n"
        . '<link rel="stylesheet" href="/bible-view.css?v=' . CSS_VERSION . '">' . "\n"
        . '<link rel="canonical" href="' . $ogUrl . '">' . "\n"
        . '<meta property="og:type" content="article">' . "\n"
        . '<meta property="og:site_name" content="HistoricalChristian.Faith">' . "\n"
        . '<meta property="og:title" content="' . $ogTitle . '">' . "\n"
        . '<meta property="og:url" content="' . $ogUrl . '">' . "\n";

    $shim = "<style>\n"
        . ".hcf-header { position: fixed; top: 0; left: 0; right: 0; width: 100%; z-index: 100; }\n"
        . "body { margin-top: 0 !important; padding-top: calc(env(safe-area-inset-top, 0px) + 60px + 1.25rem); }\n"
        . "html { scroll-padding-top: calc(env(safe-area-inset-top, 0px) + 60px + 0.5rem); }\n"
        . "</style>\n";

    if (preg_match('/<head[^>]*>/i', $html)) {
        $html = preg_replace('/(<head[^>]*>)/i', '$1' . $headTop, $html, 1);
        $html = preg_replace('/(<\/head>)/i', $shim . '$1', $html, 1);
    } else {
        $html = $headTop . $shim . $html; // no <head> — prepend
    }
    if (preg_match('/<body[^>]*>/i', $html)) {
        $html = preg_replace('/(<body[^>]*>)/i', '$1' . "\n" . $nav . "\n", $html, 1);
    } else {
        $html = $nav . $html;
    }

    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: public, max-age=0, must-revalidate');
    echo $html;
}

/** Stream a non-HTML asset with the right Content-Type, caching + 304 support. */
function stream_asset(string $path, string $ext): void {
    $types = [
        'css'  => 'text/css; charset=utf-8', 'js' => 'text/javascript; charset=utf-8',
        'json' => 'application/json; charset=utf-8', 'svg' => 'image/svg+xml',
        'png'  => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif', 'webp' => 'image/webp', 'avif' => 'image/avif',
        'ico'  => 'image/x-icon', 'pdf' => 'application/pdf',
        'txt'  => 'text/plain; charset=utf-8', 'xml' => 'application/xml',
        'woff' => 'font/woff', 'woff2' => 'font/woff2', 'ttf' => 'font/ttf', 'otf' => 'font/otf',
    ];
    $ctype = $types[$ext] ?? 'application/octet-stream';

    $mtime = filemtime($path);
    $etag  = '"' . dechex($mtime) . '-' . dechex(filesize($path)) . '"';
    header('Content-Type: ' . $ctype);
    header('Cache-Control: public, max-age=3600');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
    header('ETag: ' . $etag);
    if ($ext === 'pdf') {
        // Display inline, but save/download under the work's real filename rather
        // than "by_father.php" (ASCII fallback + RFC 5987 UTF-8 form).
        $name  = basename($path);
        $ascii = str_replace(['"', '\\'], '', preg_replace('/[^\x20-\x7E]/', '_', $name));
        header('Content-Disposition: inline; filename="' . $ascii . '"; filename*=UTF-8\'\'' . rawurlencode($name));
    }

    $inm = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : '';
    $ims = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) : 0;
    if ($inm === $etag || ($ims && $ims >= $mtime)) {
        http_response_code(304);
        return;
    }
    readfile($path);
}

/** Styled 404 for a ?file= that doesn't resolve. */
function render_404(): void {
    http_response_code(404);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><html lang="en"><meta charset="utf-8">'
       . '<title>Not found | HistoricalChristian.Faith</title>'
       . '<body style="font-family:system-ui,sans-serif;background:#080d15;color:#ddd;padding:2rem">'
       . '<h1>Writing not found</h1><p><a style="color:#e0b354" href="' . WRITINGS_SCRIPT . '">'
       . 'Back to Writings</a></p>';
}

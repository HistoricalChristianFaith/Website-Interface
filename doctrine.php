<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctrine Across Time | HistoricalChristian.Faith</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">

    <link href="/bible-view.css?v=3" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="HCF Doctrine">
    <meta name="theme-color" content="#080d15">
    <link rel="manifest" href="/manifest.json">

    <style>
        body { background: var(--bg-0); color: var(--fg-0); margin: 0; }
        /* The doctrine wiki ships its own dark theme, so (unlike the light Writings
           iframe in by_father.php) we keep the frame dark to avoid a white flash. */
        #content {
            display: block;
            width: 100%;
            height: calc(100vh - 56px);
            border: none;
            background: var(--bg-0);
            color-scheme: dark;
        }
    </style>
    <script>
        // GitHub Pages root for the Doctrine-Database repo (published from /docs).
        const DOCTRINE_BASE = "https://historicalchristianfaith.github.io/Doctrine-Database/";
        const DOCTRINE_ORIGIN = "https://historicalchristianfaith.github.io";

        // The iframe is cross-origin, so we can't read its location. Instead each wiki
        // page posts its path up (see postLocationToParent() in the wiki's toc.js); we
        // mirror it into the address bar. replaceState (not pushState): the iframe
        // navigation already created a history entry, so we only relabel it — Back /
        // Forward then step through the iframe and re-sync via the next message.
        window.addEventListener('message', function (e) {
            if (e.origin !== DOCTRINE_ORIGIN) return;
            var data = e.data;
            if (!data || data.type !== 'doctrine-nav') return;
            var url = new URL(window.location);
            if (!data.page || data.page === 'index.html') url.searchParams.delete('page');
            else url.searchParams.set('page', data.page);
            url.hash = data.hash || '';
            history.replaceState({}, '', url);
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Default to the wiki's own index (which already lists every doctrine and
            // crux). A ?page= param allows deep-linking straight to a sub-page; any
            // #hash (e.g. a footnote) is carried through.
            var params = new URLSearchParams(window.location.search);
            var page = params.get('page') || 'index.html';
            document.getElementById('content').src = DOCTRINE_BASE + page + window.location.hash;
        });
    </script>
</head>
<body>

<?php $current_page = 'doctrine'; include 'nav.php'; ?>

<iframe id="content" name="contentFrame" title="Doctrine Across Time"></iframe>

</body>
</html>

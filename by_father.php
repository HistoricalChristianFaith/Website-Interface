<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Historical Christian Writings | HistoricalChristian.Faith</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@700&display=swap" rel="stylesheet">
    <link href="/bible-view.css?v=3" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="HCF Bible">
    <meta name="theme-color" content="#080d15">
    <link rel="manifest" href="/manifest.json">

    <style>
        body { background: var(--bg-0); color: var(--fg-0); }
        #content { height: calc(100vh - 56px); background-color: #fff; color-scheme: light; }

        details > div, details > details { padding-left: 20px; }
        details, summary { cursor: pointer; }

        #sidebarMenu {
            height: calc(100vh - 56px);
            overflow-y: auto;
            background: var(--bg-1);
            border-right: 1px solid var(--line-soft);
        }
        .sidebar-sticky { position: -webkit-sticky; position: sticky; top: 0; }

        /* jsTree overrides for dark theme */
        .jstree-default a,
        .jstree-default .jstree-anchor { color: var(--fg-1); }
        .jstree-default .jstree-hovered { background: var(--bg-2); color: var(--fg-0); box-shadow: none; border-radius: 3px; }
        .jstree-default .jstree-clicked { background: var(--gold-soft); color: var(--gold); box-shadow: none; border-radius: 3px; }
        .jstree-default .jstree-wholerow-hovered { background: var(--bg-2); }
        .jstree-default .jstree-wholerow-clicked { background: var(--gold-soft); }
        .jstree-anchor {
            white-space: normal !important;
            height: auto !important;
            padding-right: 24px;
        }

        @media (max-width: 767.98px) {
            #sidebarMenu {
                position: fixed;
                top: 56px;
                left: 0;
                width: 80%;
                max-width: 300px;
                z-index: 50;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            #sidebarMenu.show {
                transform: translateX(0);
            }
        }
    </style>
    <script>
        function get_url_hash_fragment(url) {
            var hashtag_fragment = "#";
            var parts = window.location.href.split('#');
            if (parts.length > 1 && parts[1].length > 0) {
                hashtag_fragment += parts[1];
            }
            return hashtag_fragment;
        }

        function loadFile(filePath) {
            document.getElementById('content').removeAttribute('srcdoc');
            console.log("Loading file...", filePath)
            document.getElementById('content').src = "https://historicalchristianfaith.github.io/Writings-Database/" + filePath;
            const url = new URL(window.location);
            url.searchParams.set('file', filePath);
            window.history.pushState({}, '', url);
            $('#sidebarMenu').removeClass('show');
        }

        function loadMetaFile(filePath) {
            document.getElementById('content').removeAttribute('srcdoc');
            console.log("Loading metafile...", filePath)
            document.getElementById('content').src = filePath;
        }

        $(document).ready(function(){
            $('#cfmenu').jstree();
            const urlParams = new URLSearchParams(window.location.search);
            const file = urlParams.get('file');
            if (file) {
                console.log("Onload: file...", file)
                document.getElementById('content').removeAttribute('srcdoc');
                document.getElementById('content').src = "https://historicalchristianfaith.github.io/Writings-Database/" + file + get_url_hash_fragment();

                var tree = $.jstree.reference('#cfmenu');

                var allNodes = tree.get_json('#', { 'flat': true });
                var nodeToSelect = allNodes.find(node => node.li_attr && node.li_attr['data-fname'] === file);
                if(nodeToSelect) {
                    tree.select_node(nodeToSelect.id);
                } else {
                    console.log('No node found with data-fname:', file);
                }
            }
            $('#cfmenu').on("changed.jstree", function (e, data) {
                console.log("***ii1", data.selected);
            });

            $('#cfmenu').on('activate_node.jstree', function (e, data) {
                if(data.node.children.length > 0) {
                    $("#cfmenu").jstree(true).toggle_node(data.node);

                    if (data.node && data.node['li_attr'] && data.node['li_attr']['data-metadataurl']) {
                        loadMetaFile(data.node['li_attr']['data-metadataurl']);
                    }

                    return;
                }

                if (data.node && data.node['li_attr'] && data.node['li_attr']['data-fname']) {
                    loadFile(data.node['li_attr']['data-fname']);
                }

            });

            // Wire the shared .hcf-hamburger (from nav.php) to toggle the writings sidebar
            $('.hcf-hamburger').on('click', function() {
                var open = !$('#sidebarMenu').hasClass('show');
                $('#sidebarMenu').toggleClass('show', open);
                $('.v1-backdrop').toggleClass('open', open);
                $(this).attr('aria-expanded', String(open));
            });
            $('.v1-backdrop').on('click', function() {
                $('#sidebarMenu').removeClass('show');
                $(this).removeClass('open');
                $('.hcf-hamburger').attr('aria-expanded', 'false');
            });

        });

    </script>
</head>
<body>

<?php $current_page = 'writings'; $has_sidebar = true; include 'nav.php'; ?>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-4 col-lg-3 d-md-block sidebar">
            <div class="sidebar-sticky pt-3">
                <div id="cfmenu">
<?php
$htmlContent = file_get_contents("https://historicalchristianfaith.github.io/Writings-Database/menu.html?v=1");
if ($htmlContent !== false) {
    echo $htmlContent;
} else {
    echo "Error fetching menu.";
}
?>
                </div>
            </div>
        </nav>
        <main role="main" class="col-md-8 ms-sm-auto col-lg-9 px-0" style="height: calc(100vh - 56px);">
            <iframe id="content" name="contentFrame" style="width: 100%; height: 100%; border: none;" srcdoc="<p style='padding: 20px;'>Click on a writing from the menu to open it here!</p><p style='padding: 0 20px;'>Note: This database is open source, and everything is in the public domain! <a target='_blank' href='https://github.com/HistoricalChristianFaith/Writings-Database/'>Contribute/fix typos here!</a></p>"></iframe>
        </main>
    </div>
</div>

</body>
</html>

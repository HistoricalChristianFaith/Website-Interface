<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Historical Christian Writings | HistoricalChristian.Faith</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon.png">

    <style>
        #content { height: calc(100vh - 56px); }

        details > div, details > details { padding-left: 20px; }
        details, summary { cursor: pointer; }

        #sidebarMenu { height: calc(100vh - 56px); overflow-y: auto; }
        .sidebar-sticky { position: -webkit-sticky; position: sticky; top: 0; }

        /* Toggle button for mobile sidebar */
        .sidebar-toggle-btn {
            position: fixed;
            top: 70px;
            left: 10px;
            z-index: 1000;
            display: none;
        }
        @media (max-width: 767.98px) {
            .sidebar-toggle-btn {
                display: block;
            }
            #sidebarMenu {
                position: fixed;
                top: 56px;
                left: 0;
                width: 80%;
                max-width: 300px;
                z-index: 999;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            #sidebarMenu.show {
                transform: translateX(0);
            }
        }
        .jstree-anchor {
            white-space : normal !important;
            height : auto !important;
            padding-right : 24px;
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

        });

    </script>
</head>
<body>

<?php $current_page = 'writings'; include 'nav.php'; ?>

<!-- Mobile sidebar toggle button -->
<button class="btn btn-outline-secondary sidebar-toggle-btn" type="button" onclick="$('#sidebarMenu').toggleClass('show');">
    <i class="fas fa-bars"></i> Menu
</button>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-4 col-lg-3 d-md-block bg-light sidebar">
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

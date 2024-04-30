<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HistoricalChristian.Faith</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon.png">

    <style>

        #content { height: 100vh; }

        details > div, details > details { padding-left: 20px; }
        details, summary { cursor: pointer; }

        #sidebarMenu { height: 100vh; overflow-y: auto; }
        .sidebar-sticky { position: -webkit-sticky; position: sticky; top: 56px; /* Height of navbar */ }

        /* Additional style for toggle button */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 56px; /* Below the top navbar */
            left: 0;
            z-index: 1000; /* Above sidebar and content */
        }
        @media (max-width: 992px) {
            .sidebar-toggle {
                display: block; /* Show toggle button on small screens */
            }
        }
        .jstree-anchor {
            /*enable wrapping*/
            white-space : normal !important;
            /*ensure lower nodes move down*/
            height : auto !important;
            /*offset icon width*/
            padding-right : 24px;
        }
    </style>
    <script>
        function loadFile(filePath) {
            document.getElementById('content').removeAttribute('srcdoc');
            console.log("Loading file...", filePath)
            document.getElementById('content').src = "https://historicalchristianfaith.github.io/Writings-Database/" + filePath;
            const url = new URL(window.location);
            url.searchParams.set('file', filePath);
            window.history.pushState({}, '', url);
            $('#sidebarMenu').removeClass('show');
        }

        const CACHE_NAME = 'v1';
        $(document).ready(function(){
            $('#cfmenu').jstree({
                "plugins" : ["contextmenu"],
                "contextmenu": {
                    "items": function($node) {
                        return {
                            "saveOffline": {
                                "label": "Save for offline reading",
                                "action": function (obj) {
                                    const fname = $node.data.fname;
                                    const urlToCache = "https://historicalchristianfaith.github.io/Writings-Database/" + fname;
                                    caches.open(CACHE_NAME).then(cache => {
                                        cache.add(urlToCache).then(() => {
                                            console.log('File cached for offline use:', urlToCache);
                                            // Optionally, change the node icon to indicate it's saved for offline
                                            $node.icon = "fas fa-file-download";
                                            $('#cfmenu').jstree(true).redraw(true);
                                        });
                                    });
                                }
                            }
                        };
                    }
                }
            });
            const urlParams = new URLSearchParams(window.location.search);
            const file = urlParams.get('file');
            if (file) {
                console.log("Onload: file...", file)
                document.getElementById('content').removeAttribute('srcdoc');
                document.getElementById('content').src = "https://historicalchristianfaith.github.io/Writings-Database/" + file;

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
                    // Toggle (open/close) the node
                    $("#cfmenu").jstree(true).toggle_node(data.node);
                    return;
                }

                if (data.node && data.node['li_attr'] && data.node['li_attr']['data-fname']) {
                    loadFile(data.node['li_attr']['data-fname']); 
                }
            });


            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/bf-service-worker.js')
                .then(function(registration) {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch(function(err) {
                    console.log('Service Worker registration failed:', err);
                });
            }
            
            caches.open(CACHE_NAME).then(cache => {
                cache.add(urlToCache).then(() => {
                    console.log('File cached for offline use:', urlToCache);
                    // Optionally, change the node icon to indicate it's saved for offline
                    $node.icon = "fas fa-file-download";
                    $('#cfmenu').jstree(true).redraw(true);
                });
            });
        });

    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container-fluid"> 
        <div class="navbar-header">
            <a class="navbar-brand" href="/by_father.php" target="_blank">
                <span class="d-lg-none">HCF - By Father</span> <!-- Visible on xs to md screens -->
                <span class="d-none d-lg-inline">HistoricalChristian.Faith - By Father</span> <!-- Visible on lg and larger screens -->
            </a>
        </div>
        

        <div class="ml-auto">
            <button class="navbar-toggler" type="button" aria-label="Toggle navigation" onclick="$('#sidebarMenu').toggleClass('show');">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="/" class="navbar-text d-none d-lg-inline-block">View By Verse</a>
        </div>
    </div>
</nav>


<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-4 col-lg-3 d-md-block bg-light sidebar collapse">
            <div class="sidebar-sticky">
                <!-- Visible only on xs, sm, and md screens -->
                <h4 class="d-block d-lg-none">
                    <a href='/'>By Verse</a> | 
                    <a href='/by_father.php' style="font-weight:bold;">By Father</a>
                </h4>
                <div id="cfmenu">
<?php
$htmlContent = file_get_contents("https://historicalchristianfaith.github.io/Writings-Database/menu.html");
if ($htmlContent !== false) {
    echo $htmlContent;
} else {
    echo "Error fetching menu.";
}
?>
                </div>



            </div>
        </nav>
        <main role="main" class="col-md-8 ml-sm-auto col-lg-9 px-4" style="height: 100vh;padding: 0px !important;">
            <iframe id="content" name="contentFrame" style="width: 100%; height: 100%;" srcdoc="<p>Click on a writing from the menu to open it here!</p><p>Note: This database is open source, and everything is in the public domain! <a target='_blank' href='https://github.com/HistoricalChristianFaith/Writings-Database/'>Contribute/fix typos here!</a></p>"></iframe>
        </main>
    </div>
</div>

</body>
</html>

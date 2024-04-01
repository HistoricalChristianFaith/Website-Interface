<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Historical Christian Faith - Writings Database</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
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

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const file = urlParams.get('file');
            if (file) {
                console.log("Onload: file...", file)
                document.getElementById('content').removeAttribute('srcdoc');
                document.getElementById('content').src = "https://historicalchristianfaith.github.io/Writings-Database/" + file;
            }
        };
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container-fluid"> 
        <div class="navbar-header">
            <a class="navbar-brand" href="https://historicalchristian.faith/by_father.php" target="_blank">
                <span class="d-lg-none">HCF - By Father</span> <!-- Visible on xs to md screens -->
                <span class="d-none d-lg-inline">HistoricalChristian.Faith - By Father</span> <!-- Visible on lg and larger screens -->
            </a>
        </div>
        

        <div class="ml-auto">
            <button class="navbar-toggler" type="button" aria-label="Toggle navigation" onclick="$('#sidebarMenu').toggleClass('show');">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="https://historicalchristian.faith/" class="navbar-text d-none d-lg-inline-block">View By Verse</a>
        </div>
    </div>
</nav>



<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="sidebar-sticky">
                <!-- Visible only on xs, sm, and md screens -->
                <h4 class="d-block d-lg-none">
                    <a target='_blank' href='https://historicalchristian.faith/'>By Verse</a> | 
                    <a target='_blank' href='https://historicalchristian.faith/by_father.php' style="font-weight:bold;">By Father</a>
                </h4>

<?php
$htmlContent = file_get_contents("https://historicalchristianfaith.github.io/Writings-Database/menu.html");
if ($htmlContent !== false) {
    echo $htmlContent;
} else {
    echo "Error fetching menu.";
}
?>

            </div>
        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="height: 100vh;padding: 0px !important;">
            <iframe id="content" name="contentFrame" style="width: 100%; height: 100%;" srcdoc="<p>Click on a writing from the left menu to open it here!</p><p>Note: This database is open source, and everything is in the public domain! <a target='_blank' href='https://github.com/HistoricalChristianFaith/Writings-Database/'>Contribute/fix typos here!</a></p>"></iframe>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

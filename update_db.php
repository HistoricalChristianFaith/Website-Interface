<?php

if($_GET['hieronymus'] != "jerome") {
    // To try to avoid bots/search engines from triggering this.
    http_response_code(404);
    die;
}

set_time_limit(0);

//Download the latest compile SQLITE file from Commentaries-Database
$url = "https://github.com/HistoricalChristianFaith/Commentaries-Database/releases/download/latest/commentaries.sqlite";

$fp = fopen (dirname(__FILE__) . '/data2.sqlite', 'w+');
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_TIMEOUT, 600);
curl_setopt($ch, CURLOPT_FILE, $fp); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch); 
curl_close($ch);
fclose($fp);


// After it's been fully downloaded, replace the existing database file.
rename("data2.sqlite", "data.sqlite");

?>
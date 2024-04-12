<?php
$db = new SQLite3('data.sqlite', SQLITE3_OPEN_READONLY);
$kjvdb = new SQLite3('kjv.sqlite', SQLITE3_OPEN_READONLY);
include("func.php");

if(!isset($_GET['search_query']) || !$_GET['search_query']) {
    //Default query
    $_GET['search_query'] = "Matthew 1:1";
}

$parsed_input = parse_user_input($_GET['search_query']);
$location_start = ($parsed_input['start']['chapter']*1000000) + $parsed_input['start']['verse'];
$location_end = ($parsed_input['end']['chapter']*1000000) + $parsed_input['end']['verse'];
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover,initial-scale=1,maximum-scale=1,user-scalable=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>
    <title>HistoricalChristian.Faith</title>
    <meta name="description" content="Historical Christian Commentary for the Bible / Writings in the Public Domain">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon.png">
    <script>
    $(document).ready(function(){
        var maxLength = 300;
        $(".show-read-more").each(function(){
            var myStr = $.trim($(this).html());
            var split_by_words = myStr.split(' ');
            if(split_by_words.length > 150){
                var newStr = split_by_words.slice(0, 80).join(' ');
                var removedStr = split_by_words.slice(80).join(' ');
                $(this).empty().html(newStr);
                $(this).append(' <a href="javascript:void(0);" class="read-more">[Read More]</a>');
                $(this).append('<span class="more-text">' + removedStr + '</span>');
            }
        });
        $(".read-more").click(function(){
            $(this).siblings(".more-text").contents().unwrap();
            $(this).remove();
        });
    });
    </script>
    <style>
        .show-read-more .more-text{
            display: none;
        }
    </style>
</head>
<body>
<ion-app>
  <ion-header>
    <ion-toolbar>
      <ion-title onclick="window.location.href='/'">HistoricalChristian.Faith</ion-title>
    </ion-toolbar>
    <ion-toolbar>
        <form method='GET' action='?' onsubmit="window.location.href='commentaries.php?search_query='+$('#search_query').val();return false;">
            <ion-searchbar placeholder="Matthew 1:1" id="search_query" value="<?=htmlentities($_GET['search_query'])?>"></ion-searchbar>
        </form>
        <ion-buttons slot="end">
            <ion-button onclick="window.location.href='commentaries.php?search_query='+$('#search_query').val();">
                Search
                <ion-icon slot="end" name="search"></ion-icon>
            </ion-button>
        </ion-buttons>
    </ion-toolbar>
  </ion-header>

  <ion-content class="ion-padding">


<ion-card id='scripture_card' style="">
    <ion-item>
        <ion-label class="ion-text-wrap">
            <?=$parsed_input['normalized_book']?> <?=$parsed_input['normalized_verse']?>
        </ion-label>
    </ion-item>

    <ion-card-content>
    <?php
        $scripture_found = 0;
        $the_scriptures = 'No scripture found from that query.';

        $statement = $kjvdb->prepare("SELECT * FROM bible_kjv WHERE book=(:book) and txt_location >= (:location_start) and txt_location <= (:location_end) ORDER BY txt_location ASC");
        $statement->bindValue(':book', $parsed_input['book']);
        $statement->bindValue(':location_start', $location_start);
        $statement->bindValue(':location_end', $location_end);
        $q = $statement->execute();

        while ($r = $q->fetchArray(SQLITE3_ASSOC)) {
            $chapter = intval($r['txt_location']/1000000);
            $verse = $r['txt_location']-($chapter*1000000);

            //<sup class="versenum">2&nbsp;</sup>
            if(!$scripture_found) {
                $scripture_found = 1;
                $the_scriptures = "";
            }
            $the_scriptures .= ' <sup>'.$verse.'</sup>'.$r['txt'];
        }
        print $the_scriptures;
    ?>
    </ion-card-content>
</ion-card>

<ion-toolbar id='commentaries_header' style="display:none">
  <ion-title>Commentaries</ion-title>
</ion-toolbar>

<div id='commentaries_results' style=''>
    

<?php
$statement = $db->prepare("SELECT c.*,fm.wiki_url FROM commentary c LEFT JOIN father_meta fm ON c.father_name=fm.name WHERE c.book=(:book) and c.location_end >= (:location_start) and c.location_start <= (:location_end) ORDER BY c.ts ASC, c.location_start ASC");
$statement->bindValue(':book', $parsed_input['book']);
$statement->bindValue(':location_start', $location_start);
$statement->bindValue(':location_end', $location_end);
$q = $statement->execute();
error_log("SELECT c.*,fm.wiki_url FROM commentary c LEFT JOIN father_meta fm ON c.father_name=fm.name WHERE c.book=(:book) and c.location_end >= (:location_start) and c.location_start <= (:location_end) ORDER BY c.ts ASC, c.location_start ASC");
error_log("book=".$parsed_input['book']);
error_log("location_start=".$location_start);
error_log("location_end=".$location_end);

while ($r = $q->fetchArray(SQLITE3_ASSOC)) {

    if($r['ts'] && $r['ts'] < 1000000) {
        if($r['ts'] > 0) {
            if($r['ts'] == 9999) {
                $time_string = "[Unknown Date]";
            }
            else {
                $time_string = "[AD ".$r['ts']."]";
            }

        }
        else {
            $time_string = "[".($r['ts']*-1)." BC]";
        }
    }

    $chapter_start = intval($r['location_start']/1000000);
    $verse_start = $r['location_start']-($chapter_start*1000000);
    $chapter_end = intval($r['location_end']/1000000);
    $verse_end = $r['location_end']-($chapter_end*1000000);
    $verse_string = normalize_verse($chapter_start, $verse_start, $chapter_end, $verse_end);
    ?>
    <ion-card class="commentary-card">
        <ion-item>
            <ion-label class="ion-text-wrap">
            <strong class='father_title'>
                <?=$time_string?> <?php
                if($r['wiki_url']) {
                    print "<a href='".$r['wiki_url']."' target='_blank'>".htmlentities($r['father_name'])."</a>";
                }
                else {
                    print htmlentities($r['father_name']);
                }
                if($r['append_to_author_name']) {
                    print htmlentities($r['append_to_author_name']);
                }
                ?></strong> on <?=ucwords($parsed_input['normalized_book'])?> <?=$verse_string?>
                
            </ion-label>
            <ion-button class="ion-hide-md-down" fill="outline" color="medium" slot="end" target='_blank' href="https://github.com/HistoricalChristianFaith/Commentaries-Database/blob/master/<?=rawurlencode($r['father_name'])?>/<?=rawurlencode($r['file_name'])?>"><ion-icon name="logo-github"></ion-icon>&nbsp;Edit on Github</ion-button>
        </ion-item>

        <ion-card-content>
            <div class="father_commentary" style="display:none;"><?=nl2br($r['txt'])?></div>
            <div class="show-read-more">
                <?=nl2br($r['txt'])?>
            <?php
    if($r['source_title']) {
        print "<br>";
        if($r['source_url']) {
            print "- <strong class='father_source'><a href='".$r['source_url']."' target='_blank' title='".htmlentities($r['source_title'], ENT_QUOTES)."'>".htmlentities($r['source_title'])."</a></strong>";

        }
        else {
            print "- <strong class='father_source'>".htmlentities($r['source_title'])."</strong>";
        }
    }
            ?>
            </div>
        </ion-card-content>
    </ion-card><br>
    <?php
}
?>


</div>




<br><br>
<div id='poweredby' class="ion-text-center ion-margin-end" style="font-size:10px">

<ion-button onclick="copy_stack_exchange()" size="small">
    Copy <ion-icon slot="end" name="logo-stackoverflow"></ion-icon>
</ion-button>
<hr>
Powered by the open, crowd-sourced <a href="https://github.com/HistoricalChristianFaith/Commentaries-Database" target='_blank'>Historical Christian Commentaries Database</a>

</div>
<script>

    function copyText(str) {
        const el = document.createElement('textarea');  // Create a <textarea> element
        el.value = str;                                 // Set its value to the string that you want copied
        el.setAttribute('readonly', '');                // Make it readonly to be tamper-proof
        el.style.position = 'absolute';                 
        el.style.left = '-9999px';                      // Move outside the screen to make it invisible
        document.body.appendChild(el);                  // Append the <textarea> element to the HTML document
        el.select();                                    // Select the <textarea> content
        document.execCommand('copy');                   // Copy - only works as a result of a user action (e.g. click events)
        document.body.removeChild(el);                  // Remove the <textarea> element
    }
    function copy_stack_exchange(){
        var final = "";
        $(".commentary-card").each(function(){
            var t = $(this);
            final += "**"+t.find('.father_title').text().trim()+"** ";
            final += "\n";
            final += "*"+t.find('.father_commentary').text().replace(/\n/g, " ").trim()+"*";
            var fsource = t.find('.father_source');
            if(fsource) {
                if(fsource.find('a')) {
                    final += " - ["+fsource.text().trim()+"]("+fsource.find('a').attr('href')+")";
                }
                else {
                    final += " - "+fsource.text().trim();
                }
            }
            final += "\n\n";
        });
        final += "The above was taken from [Historical Christian Faith Commentaries]("+window.location.href+").";
        copyText(final);
    }
</script>

</ion-content>

</ion-app>
    </body>
</html>
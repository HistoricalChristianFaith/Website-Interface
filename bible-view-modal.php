<?php
// Database connections
$kjvdb = new SQLite3('kjv.sqlite', SQLITE3_OPEN_READONLY);
$commentarydb = new SQLite3('data.sqlite', SQLITE3_OPEN_READONLY);
require("bible-view-helpers.php");

// Get parameters
$book = isset($_GET['book']) ? $_GET['book'] : 'genesis';
$chapter = isset($_GET['chapter']) ? intval($_GET['chapter']) : 1;
$verse = isset($_GET['verse']) ? intval($_GET['verse']) : 1;

// Function to get single verse
function getSingleVerse($book, $chapter, $verse) {
    global $kjvdb;
    $statement = $kjvdb->prepare("SELECT txt FROM bible_kjv WHERE book = :book AND txt_location = :location");
    $statement->bindValue(':book', $book);
    $statement->bindValue(':location', $chapter * 1000000 + $verse);
    $result = $statement->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    return $row ? $row['txt'] : '';
}

// Function to get commentaries for a single verse
function getVerseCommentaries($book, $chapter, $verse) {
    global $commentarydb, $lookup_formatted_to_full_booknames;
    $statement = $commentarydb->prepare("SELECT c.*, fm.wiki_url FROM commentary c LEFT JOIN father_meta fm ON c.father_name = fm.name WHERE c.book = :book AND c.location_end >= (:location) and c.location_start <= (:location) ORDER BY c.ts ASC");

    $statement->bindValue(':book', $book);
    $statement->bindValue(':location', $chapter * 1000000 + $verse);

    $result = $statement->execute();

    $output = "";
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $year = $row['ts'];
        $output .= "<div class='card mb-3 commentary-card'>";
        $output .= "<div class='card-header'>";

        $chapter_start = intval($row['location_start']/1000000);
        $verse_start = $row['location_start']-($chapter_start*1000000);
        $chapter_end = intval($row['location_end']/1000000);
        $verse_end = $row['location_end']-($chapter_end*1000000);
        $verse_string = normalize_verse($chapter_start, $verse_start, $chapter_end, $verse_end);

        $output .= "<h5 class='card-title'><strong>[AD {$year}]</strong> <a href='" . htmlspecialchars($row['wiki_url']) . "' target='_blank'>" . htmlspecialchars($row['father_name']) . "</a> on " . $lookup_formatted_to_full_booknames[$book] . " " . $verse_string . "</h5>";
        $output .= "</div>";
        $output .= "<div class='card-body'><div class='show-read-more'>" . nl2br(htmlspecialchars($row['txt'])) . "</div></div>";
        if (!empty($row['source_title'])) {
            $output .= "<div class='card-footer'><small class='text-muted'>Source: ";
            if (!empty($row['source_url'])) {
                $source_url = $row['source_url'] . "#" . urlencode(substr($row['txt'], 0, 500));
                $output .= "<a href='" . htmlspecialchars($source_url) . "' target='_blank' title='" . htmlentities($row['source_title'], ENT_QUOTES) . "'>" . htmlentities($row['source_title']) . "</a>";
            } else {
                $output .= "<strong class='father_source'>" . htmlentities($row['source_title']) . "</strong>";
            }
            $output .= "</small></div>";
        }
        $output .= "</div>";
    }

    return $output;
}

$verseText = getSingleVerse($book, $chapter, $verse);
$commentaries = getVerseCommentaries($book, $chapter, $verse);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verse Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bible-view.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
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
</head>
<body>
    <div class="container-fluid">
        <div class="verse-content mb-4">
            <h4><?= htmlspecialchars($lookup_formatted_to_full_booknames[$book]) ?> <?= $chapter ?>:<?= $verse ?></h4>
            <p><?= htmlspecialchars($verseText) ?></p>
        </div>
        <div class="commentaries">
            <h5>Commentaries</h5>
            <?= $commentaries ?>
        </div>
    </div>
</body>
</html>

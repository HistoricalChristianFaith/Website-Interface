<?php
// Database connections
$kjvdb = new SQLite3('kjv.sqlite', SQLITE3_OPEN_READONLY);
$commentarydb = new SQLite3('data.sqlite', SQLITE3_OPEN_READONLY);
require("bible-view-helpers.php");

// Get current book and chapter
$user_input_book = isset($_GET['book']) ? $_GET['book'] : 'matthew';
$formattedCurrentBook = formatBookName(book_normalize_userinput($user_input_book));
if (!array_key_exists($formattedCurrentBook, $lookup_formatted_to_full_booknames)) {
    $formattedCurrentBook = 'matthew';
}
$currentBook = $lookup_formatted_to_full_booknames[$formattedCurrentBook];

$currentChapter = isset($_GET['chapter']) ? intval($_GET['chapter']) : 1;
if ($currentChapter < 1) {
    $currentChapter = 1;
}
if ($currentChapter > $lookup_chaptertotals[$currentBook]) {
    $currentChapter = $lookup_chaptertotals[$currentBook];
}

$currentVerse = isset($_GET['verse']) ? intval($_GET['verse']) : 1;
if ($currentVerse) {
    if ($currentVerse < 1) {
        $currentVerse = 1;
    }
    if ($currentVerse > $lookup_versestotals[$currentBook . "|" . $currentChapter]) {
        $currentVerse = $lookup_versestotals[$currentBook . "|" . $currentChapter];
    }
}
else {
    $currentVerse = 'all';
}

// Function to get chapter text
function getBibleText($book, $chapter, $verse) {
    global $kjvdb;
    error_log("getBibleText/".$book."/".$chapter."/".$verse);
    $statement = $kjvdb->prepare("SELECT * FROM bible_kjv WHERE book = :book AND txt_location >= :start AND txt_location < :end ORDER BY txt_location ASC");
    $statement->bindValue(':book', $book);
    if($verse && $verse != 'all') {
        $statement->bindValue(':start', ($chapter * 1000000) + $verse);
        $statement->bindValue(':end', ($chapter * 1000000) + $verse + 1);
    }
    else {
        $statement->bindValue(':start', $chapter * 1000000);
        $statement->bindValue(':end', ($chapter + 1) * 1000000);
    }
    $result = $statement->execute();

    $output = "<div class='chapter-text'>";
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $verse = $row['txt_location'] % 1000000;
        $output .= "<span class='verse' data-verse='$verse' data-book='$book' data-chapter='$chapter'><sup>$verse</sup> " . htmlspecialchars($row['txt']) . "</span> ";
    }
    $output .= "</div>";

    return $output;
}

// Function to get commentaries
function getCommentaries($book, $chapter, $verse) {
    global $commentarydb, $currentBook;
    $query = "SELECT c.*, fm.wiki_url FROM commentary c LEFT JOIN father_meta fm ON c.father_name = fm.name WHERE c.book = :book AND c.location_end >= :start AND c.location_start < :end ORDER BY c.location_start ASC, c.ts ASC";
    error_log("*****");
    error_log($query);
    $statement = $commentarydb->prepare($query);
    error_log("book: " . $book);
    $statement->bindValue(':book', $book);

    if($verse && $verse != 'all') {
        $start_filter = ($chapter * 1000000) + $verse;
        $end_filter = ($chapter * 1000000) + $verse + 1;
    }
    else {
        $start_filter = $chapter * 1000000;
        $end_filter = ($chapter + 1) * 1000000;
    }

    error_log("start_filter: " . $start_filter);
    error_log("end_filter: " . $end_filter);

    $statement->bindValue(':start', $start_filter);
    $statement->bindValue(':end', $end_filter);

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

        $output .= "<h5 class='card-title'><strong>[AD {$year}]</strong> <a href='" . htmlspecialchars($row['wiki_url']) . "' target='_blank'>" . htmlspecialchars($row['father_name']) . "</a> on " . $currentBook . " " . $verse_string . "</h5>";
        $output .= "</div>";
        $output .= "<div class='card-body'><div class='show-read-more'>" . nl2br(htmlspecialchars($row['txt'])) . "</div></div>";
        if (!empty($row['source_title'])) {
            $output .= "<br>";
            if (!empty($row['source_url'])) {
                $source_url = $row['source_url'] . "#" . urlencode(substr($row['txt'], 0, 500));
                $output .= "<div class='card-footer'><small class='text-muted'>Source: <a href='" . htmlspecialchars($source_url) . "' target='_blank' title='" . htmlentities($row['source_title'], ENT_QUOTES) . "'>" . htmlentities($row['source_title']) . "</a></small></div>";
            } else {
                $output .= "<div class='card-footer'><small class='text-muted'>Source: <strong class='father_source'>" . htmlentities($row['source_title']) . "</strong></small></div>";
            }
        }
        $output .= "</div>";
    }

    if(!$output) {
        $output = "No Commentaries in database for this selection.";
    }

    return $output;
}

/* Next/Prev chapters */


$prevChapter = $currentChapter > 1 ? $currentChapter - 1 : null;
$nextChapter = $currentChapter < $lookup_chaptertotals[$currentBook] ? $currentChapter + 1 : null;

/* Next/Prev verses */

$prevVerse = null;
$nextVerse = null;
if ($currentVerse && $currentVerse != 'all') {
    $prevVerse = $currentVerse > 1 ? $currentVerse - 1 : null;
    $nextVerse = $currentVerse < $lookup_versestotals[$currentBook . "|" . $currentChapter] ? $currentVerse + 1 : null;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bible Verses and Early Church Commentary | Historical Christian Faith</title>
    <meta name="description" content="Explore Bible verses alongside historical commentaries from early church fathers. Deepen your understanding of scripture with insights from Christian history.">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <link href="/bible-view.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <header class="bg-light py-3">
            <div class="d-flex align-items-center justify-content-center flex-nowrap">
                <div class="dropdown me-2">
                    <button class="btn btn-navigation dropdown-toggle" type="button" id="bookDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="dropdown-toggle-text"><?= $lookup_formatted_to_full_booknames[$formattedCurrentBook] ?></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="bookDropdown" style="max-height: 400px; overflow-y: auto;">
                        <?php foreach ($lookup_formatted_to_full_booknames as $formattedName => $displayName): ?>
                            <li><a class="dropdown-item <?= $formattedName === $formattedCurrentBook ? 'active' : '' ?>" 
                                href="/<?= urlencode($formattedName) ?>/1/all"><?= $displayName ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="dropdown me-2">
                    <button class="btn btn-navigation dropdown-toggle" type="button" id="chapterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="dropdown-toggle-text"><?= $currentChapter ?></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="chapterDropdown" style="max-height: 400px; overflow-y: auto;">
                        <?php for ($i = 1; $i <= $lookup_chaptertotals[$currentBook]; $i++): ?>
                            <li><a class="dropdown-item <?= $i === $currentChapter ? 'active' : '' ?>" 
                                href="/<?= urlencode($formattedCurrentBook) ?>/<?= $i ?>/all"><?= $i ?></a></li>
                        <?php endfor; ?>
                    </ul>
                </div>
                <div class="me-2 text-center">
                    <span class="nav-separator">:</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-navigation dropdown-toggle" type="button" id="verseDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="dropdown-toggle-text"><?= $currentVerse === 'all' ? "1-" . $lookup_versestotals[$currentBook."|".$currentChapter] : "" . $currentVerse ?></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="verseDropdown" style="max-height: 400px; overflow-y: auto;">
                        <li><a class="dropdown-item <?= !$currentVerse || $currentVerse === 'all' ? 'active' : '' ?>" 
                            href="/<?= urlencode($formattedCurrentBook) ?>/<?= $currentChapter ?>/all">1-<?= $lookup_versestotals[$currentBook."|".$currentChapter] ?></a></li>
                        <?php for ($i = 1; $i <= $lookup_versestotals[$currentBook."|".$currentChapter]; $i++): ?>
                            <li><a class="dropdown-item <?= $i === $currentVerse ? 'active' : '' ?>" 
                                href="/<?= urlencode($formattedCurrentBook) ?>/<?= $currentChapter ?>/<?= $i ?>"><?= $i ?></a></li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>
        </header>

        <main class="my-4">
            <div id="chapter-content" class="mb-4">
                <?= getBibleText($formattedCurrentBook, $currentChapter, $currentVerse) ?>
            </div>
            <div class="row">
                <?php

                    if (!$currentVerse || $currentVerse == 'all') {
                        //if current verse = all, do next/previous chapter
                        $prev_url = "/".urlencode($formattedCurrentBook)."/".$prevChapter."/all";
                        $next_url = "/".urlencode($formattedCurrentBook)."/".$nextChapter."/all";

                        if (!$prevChapter) {
                            // We're already at the first chapter in a book...
                            // So... just do nothing.
                            $prev_url = "/".urlencode($formattedCurrentBook)."/".$currentChapter."/all";
                        }

                        if (!$nextChapter) {
                            // We're already at the last chapter in a book...
                            // So... just do nothing.
                            $next_url = "/".urlencode($formattedCurrentBook)."/".$currentChapter."/all";
                        }
                    }
                    else {
                        // Default to next/previous verse
                        $prev_url = "/".urlencode($formattedCurrentBook)."/".$currentChapter."/".$prevVerse;
                        $next_url = "/".urlencode($formattedCurrentBook)."/".$currentChapter."/".$nextVerse;

                        if (!$prevVerse) {
                            // We're already at the first verse in a chapter. 
                            // So... let's just load the prior chapter
                            $prev_url = "/".urlencode($formattedCurrentBook)."/".$prevChapter."/all";

                            if (!$prevChapter) {
                                // We're already at the first chapter in a book...
                                // So... just do nothing.
                                $prev_url = "/".urlencode($formattedCurrentBook)."/".$currentChapter."/".$currentVerse;
                            }
                        }

                        if (!$nextVerse) {
                            // We're already at the last verse in a chapter. 
                            // So... let's just load the next chapter
                            $next_url = "/".urlencode($formattedCurrentBook)."/".$nextChapter."/all";

                            if (!$nextChapter) {
                                // We're already at the last chapter in a book...
                                // So... just do nothing.
                                $next_url = "/".urlencode($formattedCurrentBook)."/".$currentChapter."/".$currentVerse;
                            }
                        }
                    }
                ?>
                <div class="col">
                    <a href="<?= $prev_url ?>" class="btn nav-button w-100">Previous</a>
                </div>
                <div class="col">
                    <a href="<?= $next_url ?>" class="btn nav-button w-100">Next</a>
                </div>
            </div>
        </main>

        <section id="commentaries" class="mt-4">
            <?= getCommentaries($formattedCurrentBook, $currentChapter, $currentVerse) ?>
        </section>

    </div>

    <script>
    $(document).ready(function(){
        $(".card-body").each(function(){
            const $cardBody = $(this);
            if ($cardBody.prop('scrollHeight') > 200) {
                $cardBody.addClass('has-read-more')
                        .append('<a class="read-more">[Read More]</a>');
                
                $cardBody.find('.read-more').on('click', function(e) {
                    e.preventDefault();
                    $cardBody.addClass('expanded');
                });
            }
        });
    });
    
    // Remove old change functions since we're using direct links now
    document.querySelectorAll('.verse').forEach(verseElement => {
        verseElement.addEventListener('click', function() {
            const book = this.dataset.book;
            const chapter = this.dataset.chapter;
            const verse = this.dataset.verse;
            window.location.href = `/${encodeURIComponent(book)}/${encodeURIComponent(chapter)}/${encodeURIComponent(verse)}`;
        });
    });
    </script>
</body>
<footer class="footer py-3 mt-4">
    <div class="container text-center">
        <p>
            Powered by the open source, crowd-sourced 
            <a href="https://github.com/HistoricalChristianFaith/Commentaries-Database" target='_blank'>HistoricalChristianFaith/Commentaries-Database</a>
        </p>
    </div>
</footer>
</html>

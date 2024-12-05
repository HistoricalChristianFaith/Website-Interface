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
if ($currentVerse && $currentVerse != 'all') {
    if ($currentVerse < 1) {
        $currentVerse = 1;
    }
    if ($currentVerse > $lookup_versestotals[$currentBook . "|" . $currentChapter]) {
        $currentVerse = $lookup_versestotals[$currentBook . "|" . $currentChapter];
    }
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
    $statement = $commentarydb->prepare("SELECT c.*, fm.wiki_url FROM commentary c LEFT JOIN father_meta fm ON c.father_name = fm.name WHERE c.book = :book AND c.location_start >= :start AND c.location_start < :end ORDER BY c.location_start ASC, c.ts ASC");
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

    function changeBook(book) {
        window.location.href = `/${encodeURIComponent(book)}/1/all`;
    }

    function changeChapter(chapter) {
        const currentBook = document.getElementById('book-select').value;
        window.location.href = `/${encodeURIComponent(currentBook)}/${chapter}/all`;
    }
    
    function changeVerse(verse) {
        const currentBook = document.getElementById('book-select').value;
        const currentchapter = document.getElementById('chapter-select').value;
        window.location.href = `/${encodeURIComponent(currentBook)}/${encodeURIComponent(currentchapter)}/${verse}`;
    }
    </script>
</head>
<body>
    <div class="container-fluid">
        <header class="bg-light py-3">
            <div class="d-flex align-items-center justify-content-center flex-nowrap">
                <div class="me-2">
                    <select id="book-select" class="form-select" style="display: inline-block; width: auto;" onchange="changeBook(this.value)">
                        <?php foreach ($lookup_formatted_to_full_booknames as $formattedName => $displayName): ?>
                            <option value="<?= $formattedName ?>" <?= $formattedName === $formattedCurrentBook ? 'selected' : '' ?>><?= $displayName ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="me-2">
                    <select id="chapter-select" class="form-select" style="display: inline-block; width: auto;" onchange="changeChapter(this.value)">
                        <?php for ($i = 1; $i <= $lookup_chaptertotals[$currentBook]; $i++): ?>
                            <option value="<?= $i ?>" <?= $i === $currentChapter ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="me-2 text-center">
                    <span>:</span>
                </div>
                <div>
                    <select id="verse-select" class="form-select" style="display: inline-block; width: auto;" onchange="changeVerse(this.value)">
                        <option value="all">1-<?= $lookup_versestotals[$currentBook . "|" . $currentChapter] ?></option>
                        <?php for ($i = 1; $i <= $lookup_versestotals[$currentBook."|".$currentChapter]; $i++): ?>
                            <option value="<?= $i ?>" <?= $i === $currentVerse ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <a target='_blank' href="https://github.com/HistoricalChristianFaith" class="github-corner" title="This website and its database are open source! Click to open it on Github!">
                <svg width="80" height="80" viewBox="0 0 250 250" style="fill:#151513; color:#fff; position: absolute; top: 0; border: 0; right: 0;" aria-hidden="true">
                    <path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"/>
                    <path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"/>
                    <path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor" class="octo-body"/>
                </svg>
            </a>
            <style>
                .github-corner:hover .octo-arm {
                    animation: octocat-wave 560ms ease-in-out;
                }
                @keyframes octocat-wave {
                    0%, 100% { transform: rotate(0); }
                    20%, 60% { transform: rotate(-25deg); }
                    40%, 80% { transform: rotate(10deg); }
                }

                @media (max-width: 768px) {
                    .github-corner {
                        display: none;
                    }
                }
            </style>

        </header>

        <main class="my-4">
            <div id="chapter-content" class="mb-4">
                <?= getBibleText($formattedCurrentBook, $currentChapter, $currentVerse) ?>
            </div>
            <div class="row">
                <div class="col" style="<?= $currentVerse && $currentVerse != 'all' ? 'visibility: hidden;' : '' ?>">
                    <a href="/<?= urlencode($formattedCurrentBook) ?>/<?= $prevChapter ?>/all" class="btn nav-button w-100 <?= is_null($prevChapter) ? 'disabled' : '' ?>">Previous Chapter</a>
                </div>
                <div class="col" style="<?= !$currentVerse || $currentVerse == 'all' ? 'visibility: hidden;' : '' ?>">
                    <a href="/<?= urlencode($formattedCurrentBook) ?>/<?= $currentChapter ?>/<?= $prevVerse ?>" class="btn nav-button w-100 <?= is_null($prevVerse) ? 'disabled' : '' ?>">Previous Verse</a>
                </div>
                <div class="col" style="<?= !$currentVerse || $currentVerse == 'all' ? 'visibility: hidden;' : '' ?>">
                    <a href="/<?= urlencode($formattedCurrentBook) ?>/<?= $currentChapter ?>/<?= $nextVerse ?>" class="btn nav-button w-100 <?= is_null($nextVerse) ? 'disabled' : '' ?>">Next Verse</a>
                </div>
                <div class="col" style="<?= $currentVerse && $currentVerse != 'all' ? 'visibility: hidden;' : '' ?>">
                    <a href="/<?= urlencode($formattedCurrentBook) ?>/<?= $nextChapter ?>/all" class="btn nav-button w-100 <?= is_null($nextChapter) ? 'disabled' : '' ?>">Next Chapter</a>
                </div>
            </div>
        </main>

        <section id="commentaries" class="mt-4">
            <?= getCommentaries($formattedCurrentBook, $currentChapter, $currentVerse) ?>
        </section>

    </div>

    <script>
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
</html>

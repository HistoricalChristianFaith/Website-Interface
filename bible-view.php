<?php
// Database connections
$kjvdb = new SQLite3('kjv.sqlite', SQLITE3_OPEN_READONLY);
$commentarydb = new SQLite3('data.sqlite', SQLITE3_OPEN_READONLY);
require("bible-view-helpers.php");

// Get current book and chapter
$user_input_book = isset($_GET['book']) ? $_GET['book'] : 'matthew';
$formattedCurrentBook = formatBookName(book_normalize_userinput($user_input_book));
$currentChapter = isset($_GET['chapter']) ? intval($_GET['chapter']) : 1;
$currentVerse = isset($_GET['verse']) ? intval($_GET['verse']) : 1;

if (!array_key_exists($formattedCurrentBook, $lookup_formatted_to_full_booknames)) {
    $formattedCurrentBook = 'matthew';
}
$currentBook = $lookup_formatted_to_full_booknames[$formattedCurrentBook];

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

    return $output;
}

if ($currentChapter < 1) {
    $currentChapter = 1;
} elseif ($currentChapter > $lookup_chaptertotals[$currentBook]) {
    $currentChapter = $lookup_chaptertotals[$currentBook];
}

$prevChapter = $currentChapter > 1 ? $currentChapter - 1 : null;
$nextChapter = $currentChapter < $lookup_chaptertotals[$currentBook] ? $currentChapter + 1 : null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HCF: Bible + Commentaries</title>
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
        window.location.href = `/${encodeURIComponent(book)}/1/1`;
    }

    function changeChapter(chapter) {
        const currentBook = document.getElementById('book-select').value;
        window.location.href = `/${encodeURIComponent(currentBook)}/${chapter}/1`;
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
                        <?php for ($i = 1; $i <= $lookup_versestotals[$currentBook."|".$currentChapter]; $i++): ?>
                            <option value="<?= $i ?>" <?= $i === $currentVerse ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </header>

        <main class="my-4">
            <div id="chapter-content" class="mb-4">
                <?= getBibleText($formattedCurrentBook, $currentChapter, $currentVerse) ?>
            </div>
            <div class="row">
                <div class="col">
                    <a href="/<?= urlencode($formattedCurrentBook) ?>/<?= $prevChapter ?>" class="btn nav-button w-100 <?= is_null($prevChapter) ? 'disabled' : '' ?>">Previous Chapter</a>
                </div>
                <div class="col">
                    <a href="/<?= urlencode($formattedCurrentBook) ?>/<?= $nextChapter ?>" class="btn nav-button w-100 <?= is_null($nextChapter) ? 'disabled' : '' ?>">Next Chapter</a>
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

<?php
// Database connections
$kjvdb = new SQLite3('kjv.sqlite', SQLITE3_OPEN_READONLY);
$commentarydb = new SQLite3('data.sqlite', SQLITE3_OPEN_READONLY);
require("bible-view-helpers.php");

// Get current book and chapter
$formattedCurrentBook = isset($_GET['book']) ? $_GET['book'] : 'genesis';
$currentChapter = isset($_GET['chapter']) ? intval($_GET['chapter']) : 1;

if (!array_key_exists($formattedCurrentBook, $lookup_formatted_to_full_booknames)) {
    $formattedCurrentBook = 'genesis';
}
$currentBook = $lookup_formatted_to_full_booknames[$formattedCurrentBook];

// Function to get chapter text
function getChapterText($book, $chapter) {
    global $kjvdb;
    error_log("getChapterText/".$book."/".$chapter);
    $statement = $kjvdb->prepare("SELECT * FROM bible_kjv WHERE book = :book AND txt_location >= :start AND txt_location < :end ORDER BY txt_location ASC");
    $statement->bindValue(':book', $book);
    $statement->bindValue(':start', $chapter * 1000000);
    $statement->bindValue(':end', ($chapter + 1) * 1000000);
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
function getCommentaries($book, $chapter) {
    global $commentarydb, $currentBook;
    $statement = $commentarydb->prepare("SELECT c.*, fm.wiki_url FROM commentary c LEFT JOIN father_meta fm ON c.father_name = fm.name WHERE c.book = :book AND c.location_start >= :start AND c.location_start < :end ORDER BY c.location_start ASC, c.ts ASC");
    $statement->bindValue(':book', $book);
    $statement->bindValue(':start', $chapter * 1000000);
    $statement->bindValue(':end', ($chapter + 1) * 1000000);
    $result = $statement->execute();

    $output = "";
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $verse = $row['location_start'] % 1000000;
        $year = $row['ts'];
        $output .= "<div class='card mb-3 commentary-card' data-verse='$verse'>";
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
    <title>Bible Chapter Viewer</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <link href="bible-view.css" rel="stylesheet">
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
        <header class="bg-light py-3">
            <div class="row align-items-center">
                <div class="col">
                    <select id="book-select" class="form-select" onchange="changeBook(this.value)">
                        <?php foreach ($lookup_formatted_to_full_booknames as $formattedName => $displayName): ?>
                            <option value="<?= $formattedName ?>" <?= $formattedName === $formattedCurrentBook ? 'selected' : '' ?>><?= $displayName ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col text-end">
                    <select id="chapter-select" class="form-select" onchange="changeChapter(this.value)">
                        <?php for ($i = 1; $i <= $lookup_chaptertotals[$currentBook]; $i++): ?>
                            <option value="<?= $i ?>" <?= $i === $currentChapter ? 'selected' : '' ?>>Chapter <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </header>

        <main class="my-4">
            <div id="chapter-content" class="mb-4">
                <?= getChapterText($formattedCurrentBook, $currentChapter) ?>
            </div>
            <div class="row">
                <div class="col">
                    <a href="?book=<?= urlencode($formattedCurrentBook) ?>&chapter=<?= $prevChapter ?>" class="btn nav-button w-100 <?= is_null($prevChapter) ? 'disabled' : '' ?>">Previous Chapter</a>
                </div>
                <div class="col">
                    <a href="?book=<?= urlencode($formattedCurrentBook) ?>&chapter=<?= $nextChapter ?>" class="btn nav-button w-100 <?= is_null($nextChapter) ? 'disabled' : '' ?>">Next Chapter</a>
                </div>
            </div>
        </main>

        <section id="commentaries" class="mt-4">
            <?= getCommentaries($formattedCurrentBook, $currentChapter) ?>
        </section>


        <!-- Add this modal structure at the end of the body -->
        <div class="modal fade" id="verseModal" tabindex="-1" aria-labelledby="verseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verseModalLabel">Verse Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <iframe id="verseIframe" src="" width="100%" height="500px" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
    function changeBook(book) {
        window.location.href = `?book=${encodeURIComponent(book)}&chapter=1`;
    }

    function changeChapter(chapter) {
        const currentBook = document.getElementById('book-select').value;
        window.location.href = `?book=${encodeURIComponent(currentBook)}&chapter=${chapter}`;
    }

    // Add this new function to handle verse clicks
    function showVerseModal(book, chapter, verse) {
        const modal = new bootstrap.Modal(document.getElementById('verseModal'));
        const iframe = document.getElementById('verseIframe');
        iframe.src = `bible-view-modal.php?book=${encodeURIComponent(book)}&chapter=${chapter}&verse=${verse}`;
        modal.show();
    }

    // Add click event listeners to verses
    document.querySelectorAll('.verse').forEach(verseElement => {
        verseElement.addEventListener('click', function() {
            const book = this.dataset.book;
            const chapter = this.dataset.chapter;
            const verse = this.dataset.verse;
            showVerseModal(book, chapter, verse);
        });
    });
    </script>
</body>
</html>

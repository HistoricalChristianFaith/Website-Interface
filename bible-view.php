<?php
// Database connections
$kjvdb = new SQLite3('kjv.sqlite', SQLITE3_OPEN_READONLY);
$commentarydb = new SQLite3('data.sqlite', SQLITE3_OPEN_READONLY);
require("bible-view-helpers.php");

// Function to format book name for maxChapters array
function formatBookName($book) {
    return strtolower(str_replace(' ', '', $book));
}

// Get current book and chapter
$currentBook = isset($_GET['book']) ? $_GET['book'] : 'Genesis';
$currentChapter = isset($_GET['chapter']) ? intval($_GET['chapter']) : 1;


$both_testaments = array_merge($old_testament, $new_testament);

// Get max chapters for each book
$maxChapters = array();
$bookDisplayNames = array();
foreach ($both_testaments as $book => $chapters) {
    $formattedName = formatBookName($book);
    $maxChapters[$formattedName] = $chapters;
    $bookDisplayNames[$formattedName] = $book;
    $books[] = $formattedName;
}

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
    global $commentarydb;
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
        $output .= "<h5 class='card-title'><strong>[AD {$year}]</strong> <a href='" . htmlspecialchars($row['wiki_url']) . "' target='_blank'>" . htmlspecialchars($row['father_name']) . "</a> on " . htmlspecialchars($book) . " " . htmlspecialchars($chapter) . ":" . htmlspecialchars($verse) . "</h5>";
        $output .= "</div>";
        $output .= "<div class='card-body'>" . nl2br(htmlspecialchars($row['txt'])) . "</div>";
        if (!empty($row['source'])) {
            $output .= "<div class='card-footer'><small class='text-muted'>Source: <a href='" . htmlspecialchars($row['source']) . "' target='_blank'>" . htmlspecialchars($row['source']) . "</a></small></div>";
        }
        $output .= "</div>";
    }

    return $output;
}

// Handle navigation
$formattedCurrentBook = formatBookName($currentBook);
if ($currentChapter < 1) {
    $currentChapter = 1;
} elseif ($currentChapter > $maxChapters[$formattedCurrentBook]) {
    $currentChapter = $maxChapters[$formattedCurrentBook];
}

$prevChapter = $currentChapter > 1 ? $currentChapter - 1 : null;
$nextChapter = $currentChapter < $maxChapters[$formattedCurrentBook] ? $currentChapter + 1 : null;

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
    <link href="bible-view.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <header class="bg-light py-3">
            <div class="row align-items-center">
                <div class="col">
                    <select id="book-select" class="form-select" onchange="changeBook(this.value)">
                        <?php foreach ($bookDisplayNames as $formattedName => $displayName): ?>
                            <option value="<?= $formattedName ?>" <?= $formattedName === $formattedCurrentBook ? 'selected' : '' ?>><?= $displayName ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col text-end">
                    <select id="chapter-select" class="form-select" onchange="changeChapter(this.value)">
                        <?php for ($i = 1; $i <= $maxChapters[$formattedCurrentBook]; $i++): ?>
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
                    <a href="?book=<?= urlencode($currentBook) ?>&chapter=<?= $prevChapter ?>" class="btn nav-button w-100 <?= is_null($prevChapter) ? 'disabled' : '' ?>">Previous Chapter</a>
                </div>
                <div class="col">
                    <a href="?book=<?= urlencode($currentBook) ?>&chapter=<?= $nextChapter ?>" class="btn nav-button w-100 <?= is_null($nextChapter) ? 'disabled' : '' ?>">Next Chapter</a>
                </div>
            </div>
        </main>

        <section id="commentaries" class="mt-4">
            <?= getCommentaries($formattedCurrentBook, $currentChapter) ?>
        </section>
    </div>

    <script>
    function changeBook(book) {
        window.location.href = `?book=${encodeURIComponent(book)}&chapter=1`;
    }

    function changeChapter(chapter) {
        const currentBook = document.getElementById('book-select').value;
        window.location.href = `?book=${encodeURIComponent(currentBook)}&chapter=${chapter}`;
    }
    </script>
</body>
</html>

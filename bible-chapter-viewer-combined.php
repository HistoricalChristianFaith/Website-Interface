<?php
// Database connections
$kjvdb = new SQLite3('kjv.sqlite', SQLITE3_OPEN_READONLY);
$commentarydb = new SQLite3('data.sqlite', SQLITE3_OPEN_READONLY);

// Function to format book name for maxChapters array
function formatBookName($book) {
    return strtolower(str_replace(' ', '', $book));
}

// Get current book and chapter
$currentBook = isset($_GET['book']) ? $_GET['book'] : 'Genesis';
$currentChapter = isset($_GET['chapter']) ? intval($_GET['chapter']) : 1;

$old_testament = [
    "Genesis" => 50,
    "Exodus" => 40,
    "Leviticus" => 27,
    "Numbers" => 36,
    "Deuteronomy" => 34,
    "Joshua" => 24,
    "Judges" => 21,
    "Ruth" => 4,
    "1 Samuel" => 31,
    "2 Samuel" => 24,
    "1 Kings" => 22,
    "2 Kings" => 25,
    "1 Chronicles" => 29,
    "2 Chronicles" => 36,
    "1 Esdras" => 9,
    "Ezra" => 10,
    "Nehemiah" => 13,
    "Tobit" => 14,
    "Judith" => 16,
    "Esther" => 10,
    "Maccabees 1" => 16,
    "Maccabees 2" => 15,
    "Psalms" => 150,
    "Job" => 42,
    "Proverbs" => 31,
    "Ecclesiastes" => 12,
    "Song of Songs" => 8,
    "Wisdom of Solomon" => 19,
    "Wisdom of Sirach" => 51,
    "Hosea" => 14,
    "Amos" => 9,
    "Micah" => 7,
    "Joel" => 3,
    "Obadiah" => 1,
    "Jonah" => 4,
    "Nahum" => 3,
    "Habakkuk" => 3,
    "Zephaniah" => 3,
    "Haggai" => 2,
    "Zechariah" => 14,
    "Malachi" => 4,
    "Isaiah" => 66,
    "Jeremiah" => 52,
    "Baruch" => 5,
    "Lamentations" => 5,
    "Epistle of Jeremiah" => 1,
    "Ezekiel" => 48,
    "Daniel" => 14,
    "Prayer of Manasseh" => 1
];

$new_testament = [
    "Matthew" => 28,
    "Mark" => 16,
    "Luke" => 24,
    "John" => 21,
    "Acts" => 28,
    "Romans" => 16,
    "1 Corinthians" => 16,
    "2 Corinthians" => 13,
    "Galatians" => 6,
    "Ephesians" => 6,
    "Philippians" => 4,
    "Colossians" => 4,
    "1 Thessalonians" => 5,
    "2 Thessalonians" => 3,
    "1 Timothy" => 6,
    "2 Timothy" => 4,
    "Titus" => 3,
    "Philemon" => 1,
    "Hebrews" => 13,
    "James" => 5,
    "1 Peter" => 5,
    "2 Peter" => 3,
    "1 John" => 5,
    "2 John" => 1,
    "3 John" => 1,
    "Jude" => 1,
    "Revelation" => 22
];

$both_testaments = array_merge($old_testament, $new_testament);

// Get max chapters for each book
$maxChapters = array();
$bookDisplayNames = array(); // New array to store display names
foreach ($both_testaments as $book => $chapters) {
    $formattedName = formatBookName($book);
    $maxChapters[$formattedName] = $chapters;
    $bookDisplayNames[$formattedName] = $book; // Store display name
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

// Get current book and chapter
$currentBook = isset($_GET['book']) ? $_GET['book'] : 'Genesis';
$currentChapter = isset($_GET['chapter']) ? intval($_GET['chapter']) : 1;

// Handle navigation
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'change_book':
            $currentBook = $bookDisplayNames[$_POST['book']];
            $currentChapter = 1;
            break;
        case 'change_chapter':
            $currentChapter = intval($_POST['chapter']);
            break;
        case 'prev_chapter':
            if ($currentChapter > 1) {
                $currentChapter--;
            }
            break;
        case 'next_chapter':
            if ($currentChapter < $maxChapters[formatBookName($currentBook)]) {
                $currentChapter++;
            }
            break;
    }
}

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
    <style>
        .verse {
            cursor: pointer;
        }
        .verse:hover {
            background-color: #f0f0f0;
        }
        .nav-button {
            background-color: transparent;
            border: 1px solid #007bff;
            color: #007bff;
        }
        .nav-button:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <header class="bg-light py-3">
            <div class="row align-items-center">
                <div class="col">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="change_book">
                        <select name="book" class="form-select" onchange="this.form.submit()">
                            <?php foreach ($bookDisplayNames as $formattedName => $displayName): ?>
                                <option value="<?= $formattedName ?>" <?= $displayName === $currentBook ? 'selected' : '' ?>><?= $displayName ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
                <div class="col text-end">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="change_chapter">
                        <select name="chapter" class="form-select" onchange="this.form.submit()">
                            <?php for ($i = 1; $i <= $maxChapters[formatBookName($currentBook)]; $i++): ?>
                                <option value="<?= $i ?>" <?= $i === $currentChapter ? 'selected' : '' ?>>Chapter <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </form>
                </div>
            </div>
        </header>

        <main class="my-4">
            <div id="chapter-content" class="mb-4">
                <?= getChapterText(formatBookName($currentBook), $currentChapter) ?>
            </div>
            <div class="row">
                <div class="col">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="prev_chapter">
                        <button type="submit" class="btn nav-button w-100" <?= ($currentChapter <= 1) ? 'disabled' : '' ?>>Previous Chapter</button>
                    </form>
                </div>
                <div class="col">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="next_chapter">
                        <button type="submit" class="btn nav-button w-100" <?= ($currentChapter >= $maxChapters[formatBookName($currentBook)]) ? 'disabled' : '' ?>>Next Chapter</button>
                    </form>
                </div>
            </div>
        </main>

        <section id="commentaries" class="mt-4">
            <?= getCommentaries(formatBookName($currentBook), $currentChapter) ?>
        </section>
    </div>
</body>
</html>

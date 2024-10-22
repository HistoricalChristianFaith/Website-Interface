<?php

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

$lookup_chaptertotals = array_merge($old_testament, $new_testament);

// Function to format book name for maxChapters array
function formatBookName($book) {
    return strtolower(str_replace(' ', '', $book));
}


$lookup_formatted_to_full_booknames = [];

foreach ($lookup_chaptertotals as $book => $chapters) {
    $lookup_formatted_to_full_booknames[formatBookName($book)] = $book;
}


function normalize_verse($start_verse_CHAPTER, $start_verse_VERSE, $end_verse_CHAPTER, $end_verse_VERSE) {
    if($start_verse_CHAPTER != $end_verse_CHAPTER) {
        $query_verse_string = $start_verse_CHAPTER.":".$start_verse_VERSE."-".$end_verse_CHAPTER.":".$end_verse_VERSE;
    }
    else {
        if($start_verse_VERSE == $end_verse_VERSE) {
            $query_verse_string = $start_verse_CHAPTER.":".$start_verse_VERSE;
        }
        else {
            $query_verse_string = $start_verse_CHAPTER.":".$start_verse_VERSE."-".$end_verse_VERSE;
        }
    }
    $query_verse_string = str_replace(":1-99999","",$query_verse_string);
    return $query_verse_string;
}

?>
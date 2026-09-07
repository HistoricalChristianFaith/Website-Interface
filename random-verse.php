<?php
require_once 'bible-view-helpers.php';

$book = array_rand($new_testament);
$chapter = rand(1, $new_testament[$book]);
$verse = rand(1, $lookup_versestotals["$book|$chapter"]);
$url = '/' . formatBookName($book) . '/' . $chapter . '/' . $verse;

header("Location: $url");
exit;

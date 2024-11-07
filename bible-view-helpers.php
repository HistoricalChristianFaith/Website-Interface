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
    "1 Maccabees" => 16,
    "2 Maccabees" => 15,
    "Psalms" => 150,
    "Job" => 42,
    "Proverbs" => 31,
    "Ecclesiastes" => 12,
    "Song of Solomon" => 8,
    "Wisdom" => 19,
    "Sirach" => 51,
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
    //"Epistle of Jeremiah" => 1,
    "Ezekiel" => 48,
    "Daniel" => 14,
    //"Prayer of Manasseh" => 1
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


function book_normalize_userinput($book) {
    //https://www.logos.com/bible-book-abbreviations
    $lookup = [
        "1chronicles" => "1 Chronicles",
        "1chronicle" => "1 Chronicles",
        "1chron" => "1 Chronicles",
        "1chr" => "1 Chronicles",
        "1ch" => "1 Chronicles",
        "1stchron" => "1 Chronicles",
        "1stchronicles" => "1 Chronicles",
        "firstchronicles" => "1 Chronicles",
        "firstchron" => "1 Chronicles",

        "2chronicles" => "2 Chronicles",
        "2chronicle" => "2 Chronicles",
        "2chron" => "2 Chronicles",
        "2chr" => "2 Chronicles",
        "2ch" => "2 Chronicles",
        "2ndchron" => "2 Chronicles",
        "2ndchronicles" => "2 Chronicles",
        "secondchronicles" => "2 Chronicles",
        "secondchron" => "2 Chronicles",

        "1corinthians" => "1 Corinthians",
        "1corinthian" => "1 Corinthians",
        "1corinth" => "1 Corinthians",
        "1corin" => "1 Corinthians",
        "1cor" => "1 Corinthians",
        "1co" => "1 Corinthians",
        "1stcorinthians" => "1 Corinthians",
        "1stcor" => "1 Corinthians",
        "firstcorinthians" => "1 Corinthians",
        "firstcor" => "1 Corinthians",

        "2corinthians" => "2 Corinthians",
        "2corinthian" => "2 Corinthians",
        "2corinth" => "2 Corinthians",
        "2corin" => "2 Corinthians",
        "2cor" => "2 Corinthians",
        "2co" => "2 Corinthians",
        "2ndcorinthians" => "2 Corinthians",
        "2ndcor" => "2 Corinthians",
        "secondcorinthians" => "2 Corinthians",
        "secondcor" => "2 Corinthians",

        "1esdras" => "1 Esdras",
        "1esdra" => "1 Esdras",
        "1esdr" => "1 Esdras",
        "1esd" => "1 Esdras",
        "1es" => "1 Esdras",
        "1stesdras" => "1 Esdras",
        "firstesdras" => "1 Esdras",

        "2esdras" => "2 Esdras",
        "2esdra" => "2 Esdras",
        "2esdr" => "2 Esdras",
        "2esd" => "2 Esdras",
        "2es" => "2 Esdras",
        "2ndesdras" => "2 Esdras",
        "secondesdras" => "2 Esdras",

        "1john" => "1 John",
        "1jhn" => "1 John",
        "1jn" => "1 John",
        "1jon" => "1 John",
        "1stjohn" => "1 John",
        "firstjohn" => "1 John",

        "2john" => "2 John",
        "2jhn" => "2 John",
        "2jn" => "2 John",
        "2jon" => "2 John",
        "2ndjohn" => "2 John",
        "secondjohn" => "2 John",

        "3john" => "3 John",
        "3jhn" => "3 John",
        "3jn" => "3 John",
        "3jon" => "3 John",
        "3rdjohn" => "3 John",
        "thirdjohn" => "3 John",

        "1kings" => "1 Kings",
        "1king" => "1 Kings",
        "1kng" => "1 Kings",
        "1kgs" => "1 Kings",
        "1ki" => "1 Kings",
        "1kin" => "1 Kings",
        "1stkings" => "1 Kings",
        "1stkgs" => "1 Kings",
        "firstkings" => "1 Kings",
        "firstkgs" => "1 Kings",

        "2kings" => "2 Kings",
        "2king" => "2 Kings",
        "2kng" => "2 Kings",
        "2kgs" => "2 Kings",
        "2ki" => "2 Kings",
        "2kin" => "2 Kings",
        "2ndkings" => "2 Kings",
        "2ndkgs" => "2 Kings",
        "secondkings" => "2 Kings",
        "secondkgs" => "2 Kings",



        "1maccabees" => "1 Maccabees",
        "1macabbees" => "1 Maccabees",
        "1macabees" => "1 Maccabees",
        "1maccabee" => "1 Maccabees",
        "1macabbee" => "1 Maccabees",
        "1macabee" => "1 Maccabees",
        "1mac" => "1 Maccabees",
        "1macc" => "1 Maccabees",
        "1stmaccabees" => "1 Maccabees",
        "firstmaccabees" => "1 Maccabees",

        "2maccabees" => "2 Maccabees",
        "2macabbees" => "2 Maccabees",
        "2macabees" => "2 Maccabees",
        "2maccabee" => "2 Maccabees",
        "2macabbee" => "2 Maccabees",
        "2macabee" => "2 Maccabees",
        "2mac" => "2 Maccabees",
        "2macc" => "2 Maccabees",
        "2ndmaccabees" => "2 Maccabees",
        "secondmaccabees" => "2 Maccabees",

        "1peter" => "1 Peter",
        "1pet" => "1 Peter",
        "1ptr" => "1 Peter",
        "1pt" => "1 Peter",
        "1stpeter" => "1 Peter",
        "firstpeter" => "1 Peter",

        "2peter" => "2 Peter",
        "2pet" => "2 Peter",
        "2ptr" => "2 Peter",
        "2pt" => "2 Peter",
        "2ndpeter" => "2 Peter",
        "secondpeter" => "2 Peter",

        "1samuel" => "1 Samuel",
        "1sam" => "1 Samuel",
        "1stsamuel" => "1 Samuel",
        "1stsam" => "1 Samuel",
        "firstsamuel" => "1 Samuel",
        "firstsam" => "1 Samuel",

        "2samuel" => "2 Samuel",
        "2sam" => "2 Samuel",
        "2ndsamuel" => "2 Samuel",
        "2ndsam" => "2 Samuel",
        "secondsamuel" => "2 Samuel",
        "secondsam" => "2 Samuel",

        "1thessalonians" => "1 Thessalonians",
        "1thessalonian" => "1 Thessalonians",
        "1thesalonians" => "1 Thessalonians",
        "1thess" => "1 Thessalonians",
        "1thes" => "1 Thessalonians",
        "1th" => "1 Thessalonians",
        "1stthessalonians" => "1 Thessalonians",
        "1stthess" => "1 Thessalonians",
        "firstthessalonians" => "1 Thessalonians",
        "firstthess" => "1 Thessalonians",

        "2thessalonians" => "2 Thessalonians",
        "2thessalonian" => "2 Thessalonians",
        "2thesalonians" => "2 Thessalonians",
        "2thess" => "2 Thessalonians",
        "2thes" => "2 Thessalonians",
        "2th" => "2 Thessalonians",
        "2ndthessalonians" => "2 Thessalonians",
        "2ndthess" => "2 Thessalonians",
        "secondthessalonians" => "2 Thessalonians",
        "secondthess" => "2 Thessalonians",

        "1timothy" => "1 Timothy",
        "1tim" => "1 Timothy",
        "1ti" => "1 Timothy",
        "1sttimothy" => "1 Timothy",
        "1sttim" => "1 Timothy",
        "firsttimothy" => "1 Timothy",
        "firsttim" => "1 Timothy",

        "2timothy" => "2 Timothy",
        "2tim" => "2 Timothy",
        "2ti" => "2 Timothy",
        "2ndtimothy" => "2 Timothy",
        "2ndtim" => "2 Timothy",
        "secondtimothy" => "2 Timothy",
        "secondtim" => "2 Timothy",

        "acts" => "Acts",
        "act" => "Acts",
        "ac" => "Acts",

        "amos" => "Amos",
        "am" => "Amos",

        "baruch" => "Baruch",
        "bar" => "Baruch",

        //"belandthedragon" => "",

        "colossians" => "Colossians",
        "colossian" => "Colossians",
        "col" => "Colossians",
        "co" => "Colossians",

        "daniel" => "Daniel",
        "dan" => "Daniel",
        "da" => "Daniel",
        "dn" => "Daniel",

        "deuteronomy" => "Deuteronomy",
        "deut" => "Deuteronomy",
        "deu" => "Deuteronomy",
        "de" => "Deuteronomy",
        "dt" => "Deuteronomy",

        "ecclesiastes" => "Ecclesiastes",
        "ecclesiaste" => "Ecclesiastes",
        "eccles" => "Ecclesiastes",
        "eccle" => "Ecclesiastes",
        "ecc" => "Ecclesiastes",
        "ec" => "Ecclesiastes",

        "wisdomofsirach" => "Sirach",
        "sirach" => "Sirach",
        "sir" => "Sirach",
        "ecclesiasticus" => "Sirach",
        "ecclus" => "Sirach",
        
        "ephesians" => "Ephesians",
        "ephesian" => "Ephesians",
        "eph" => "Ephesians",
        "ephes" => "Ephesians",

        //"epistleofjeremiah" => "Epistle of Jeremiah",

        "esther" => "Esther",
        "est" => "Esther",
        "esth" => "Esther",
        "es" => "Esther",

        //"esther,greek" => "",

        "exodus" => "Exodus",
        "ex" => "Exodus",
        "exod" => "Exodus",
        "exo" => "Exodus",

        "ezekiel" => "Ezekiel",
        "ezek" => "Ezekiel",
        "eze" => "Ezekiel",
        "ezk" => "Ezekiel",

        "ezra" => "Ezra",
        "ezr" => "Ezra",
        "ez" => "Ezra",

        "galatians" => "Galatians",
        "galatian" => "Galatians",
        "gal" => "Galatians",
        "ga" => "Galatians",

        "genesis" => "Genesis",
        "gen" => "Genesis",
        "ge" => "Genesis",
        "gn" => "Genesis",

        "habakkuk" => "Habakkuk",
        "habakuk" => "Habakkuk",
        "hab" => "Habakkuk",
        "hb" => "Habakkuk",

        "haggai" => "Haggai",
        "hag" => "Haggai",
        "hg" => "Haggai",

        "hebrews" => "Hebrews",
        "hebrew" => "Hebrews",
        "heb" => "Hebrews",

        "hosea" => "Hosea",
        "hos" => "Hosea",
        "ho" => "Hosea",

        "isaiah" => "Isaiah",
        "isa" => "Isaiah",
        "is" => "Isaiah",

        "james" => "James",
        "jas" => "James",
        "jm" => "James",

        "jeremiah" => "Jeremiah",
        "jer" => "Jeremiah",
        "je" => "Jeremiah",
        "jr" => "Jeremiah",

        "job" => "Job",
        "jb" => "Job",

        "joel" => "Joel",
        "jl" => "Joel",

        "john" => "John",
        "joh" => "John",
        "jhn" => "John",
        "jn" => "John",

        "jonah" => "Jonah",
        "jnh" => "Jonah",
        "jon" => "Jonah",

        "joshua" => "Joshua",
        "josh" => "Joshua",
        "jos" => "Joshua",
        "jsh" => "Joshua",

        "jude" => "Jude",
        "jud" => "Jude",
        "jd" => "Jude",

        "judges" => "Judges",
        "judg" => "Judges",
        "jdg" => "Judges",
        "jg" => "Judges",
        "jdgs" => "Judges",

        "judith" => "Judith",
        "jth" => "Judith",
        "jdth" => "Judith",
        "jdt" => "Judith",

        "lamentations" => "Lamentations",
        "lam" => "Lamentations",
        "la" => "Lamentations",

        "leviticus" => "Leviticus",
        "lev" => "Leviticus",
        "le" => "Leviticus",
        "lv" => "Leviticus",

        "luke" => "Luke",
        "luk" => "Luke",
        "lk" => "Luke",

        "malachi" => "Malachi",
        "mal" => "Malachi",
        "ml" => "Malachi",

        "mark" => "Mark",
        "mrk" => "Mark",
        "mar" => "Mark",
        "mk" => "Mark",
        "mr" => "Mark",

        "matthew" => "Matthew",
        "matt" => "Matthew",
        "mt" => "Matthew",

        "micah" => "Micah",
        "mic" => "Micah",
        "mc" => "Micah",

        "nahum" => "Nahum",
        "nah" => "Nahum",
        "na" => "Nahum",

        "nehemiah" => "Nehemiah",
        "neh" => "Nehemiah",
        "ne" => "Nehemiah",

        "numbers" => "Numbers",
        "num" => "Numbers",
        "nu" => "Numbers",
        "nm" => "Numbers",
        "nb" => "Numbers",

        "obadiah" => "Obadiah",
        "obad" => "Obadiah",
        "ob" => "Obadiah",

        "philemon" => "Philemon",
        "philem" => "Philemon",
        "phm" => "Philemon",
        "pm" => "Philemon",

        "philippians" => "Philippians",
        "phil" => "Philippians",
        "php" => "Philippians",
        "pp" => "Philippians",

        /*
        "prayerofazariah" => "Prayer of Azariah",
        "prazar" => "Prayer of Azariah",
        "praz" => "Prayer of Azariah",
        "azariah" => "Prayer of Azariah",
        "songofthreeyouths" => "Prayer of Azariah",
        "Sgof3childr" => "Prayer of Azariah",
        "songofthree" => "Prayer of Azariah",
        "songofthr" => "Prayer of Azariah",
        "songthr" => "Prayer of Azariah",
        "thesongofthreeyouths" => "Prayer of Azariah",
        "songofthethreeholychildren" => "Prayer of Azariah",
        "songofthreechildren" => "Prayer of Azariah",
        "thesongofthreejews" => "Prayer of Azariah",
        "azariah" => "Prayer of Azariah",
        "azariah" => "Prayer of Azariah",
        */

        //"prayerofmanasseh" => "Prayer of Manasseh",

        "proverbs" => "Proverbs",
        "proverb" => "Proverbs",
        "prov" => "Proverbs",
        "pro" => "Proverbs",
        "prv" => "Proverbs",
        "pr" => "Proverbs",

        "psalms" => "Psalms",
        "psalm" => "Psalms",
        "ps" => "Psalms",
        "pslm" => "Psalms",
        "psa" => "Psalms",
        "psm" => "Psalms",
        "pss" => "Psalms",

        "revelation" => "Revelation",
        "revelations" => "Revelation",
        "rev" => "Revelation",
        "re" => "Revelation",

        "romans" => "Romans",
        "rom" => "Romans",
        "ro" => "Romans",
        "rm" => "Romans",

        "ruth" => "Ruth",
        "rth" => "Ruth",
        "ru" => "Ruth",

        "songofsongs" => "Song of Solomon",
        "song" => "Song of Solomon",
        "sos" => "Song of Solomon",
        "so" => "Song of Solomon",
        "canticles" => "Song of Solomon",
        "canticleofcanticles" => "Song of Solomon",
        "cant" => "Song of Solomon",
        "songofsolomon" => "Song of Solomon",

        //"susanna" => "Susanna",

        "titus" => "Titus",
        "tit" => "Titus",
        "ti" => "Titus",

        "tobit" => "Tobit",
        "tob" => "Tobit",
        "tb" => "Tobit",

        "wisdomofsolomon" => "Wisdom",
        "wisdom" => "Wisdom",
        "wisdofsol" => "Wisdom",
        "wis" => "Wisdom",
        "ws" => "Wisdom",

        "zechariah" => "Zechariah",
        "zech" => "Zechariah",
        "zec" => "Zechariah",
        "zc" => "Zechariah",

        "zephaniah" => "Zephaniah",
        "zeph" => "Zephaniah",
        "zep" => "Zephaniah",
        "zp" => "Zephaniah"
    ];
    if(isset($lookup[$book])) {
        return $lookup[$book];
    }
    return "Genesis";
}

?>
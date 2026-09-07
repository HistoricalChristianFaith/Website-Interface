<?php
// Shared: group the curated "spicy" quotes by the name each father carries in
// the home-page timeline, each with a short linked citation. Fetched on demand
// via spicy.php only when the "fire" easter egg is lit — so the landing page
// never parses or ships any of this. Returns an array keyed by timeline name.
$spicy_src = require __DIR__ . '/spicy-quotes.php';
$spicy_name_map = array(
    'Jerome'                 => 'Jerome',
    'Augustine'              => 'Augustine of Hippo',
    'Tertullian'             => 'Tertullian',
    'Origen'                 => 'Origen of Alexandria',
    'Epiphanius of Salamis'  => 'Epiphanius of Salamis',
    'Ignatius of Antioch'    => 'Ignatius of Antioch',
    'Cosmas Indicopleustes'  => 'Cosmas Indicopleustes',
    'Justin Martyr'            => 'Justin Martyr',
    'Irenaeus'                 => 'Irenaeus',
    'Clement of Alexandria'    => 'Clement of Alexandria',
    'Hippolytus of Rome'       => 'Hippolytus of Rome',
    'Cyprian'                  => 'Cyprian',
    'Methodius of Olympus'     => 'Methodius of Olympus',
    'Lactantius'               => 'Lactantius',
    'Eusebius of Caesarea'     => 'Eusebius of Caesarea',
    'Hilary of Poitiers'       => 'Hilary of Poitiers',
    'Ephrem the Syrian'        => 'Ephrem the Syrian',
    'Athanasius of Alexandria' => 'Athanasius of Alexandria',
    'Basil of Caesarea'        => 'Basil of Caesarea',
    'Ambrosiaster'             => 'Ambrosiaster',
    'Cyril of Jerusalem'       => 'Cyril of Jerusalem',
    'Gregory of Nazianzus'     => 'Gregory of Nazianzus',
    'Gregory of Nyssa'         => 'Gregory of Nyssa',
    'Ambrose of Milan'         => 'Ambrose of Milan',
    'Didymus the Blind'        => 'Didymus the Blind',
    'John Chrysostom'          => 'John Chrysostom',
    'Theodore of Mopsuestia'   => 'Theodore of Mopsuestia',
    'John Cassian'             => 'John Cassian',
    'Cyril of Alexandria'      => 'Cyril of Alexandria',
    'Theodoret of Cyrus'       => 'Theodoret of Cyrus',
    'Leo the Great'            => 'Leo the Great',
    'Desert Fathers'           => 'Desert Fathers',
    'Philoxenus of Mabbug'     => 'Philoxenus of Mabbug',
    'Cassiodorus'              => 'Cassiodorus',
    'John Damascene'           => 'John Damascene',
    'Alcuin of York'           => 'Alcuin of York',
    'Rabanus Maurus'           => 'Rabanus Maurus',
    'Theophylact of Ohrid'     => 'Theophylact of Ohrid',
    'Bernard of Clairvaux'     => 'Bernard of Clairvaux',
    'Bonaventure'              => 'Bonaventure',
    'Thomas Aquinas'           => 'Thomas Aquinas',
);
// Turn a source into a short, linked citation. An explicit source_title wins;
// otherwise the title is guessed from the URL. Same-site links are made relative
// so they stay on the site; anything else opens in a new tab.
function spicy_cite($src, $title = null) {
    if (!$title && !$src) return 'Source';
    $titles = array(
        'fathers/3007'        => 'Against Helvidius',
        'fathers/04161'       => 'Against Celsus',
        'fathers/0310'        => 'A Treatise on the Soul',
        'fathers/27102'       => 'Against Rufinus',
        'jerome_daniel'       => 'Commentary on Daniel',
        'Against%2520Praxeas' => 'Against Praxeas',
        'On%2520the%2520Flesh'=> 'On the Flesh of Christ',
        'fathers/1102'        => 'Letters',
        'fathers/3001'        => 'Letters',
        'npnf206'             => 'Letters',
        'masseiana'           => 'Panarion',
        'books.google'        => 'Panarion',
        'panarion'            => 'Panarion',
    );
    if ($title === null || $title === '') {
        $title = 'Source';
        foreach ($titles as $needle => $t) {
            if (stripos($src, $needle) !== false) { $title = $t; break; }
        }
    }
    if (!$src) return htmlspecialchars($title, ENT_QUOTES);   // titled, but no link
    $href = preg_replace('#^https?://historicalchristian\.faith#', '', $src);
    $external = (strpos($href, 'http') === 0) ? ' target="_blank" rel="noopener"' : '';
    return '<a href="' . htmlspecialchars($href, ENT_QUOTES) . '"' . $external . '>'
        . htmlspecialchars($title, ENT_QUOTES) . '</a>';
}
$spicy_grouped = array();
foreach ($spicy_src as $e) {
    $father = isset($e['father']) ? $e['father'] : '';
    if (!isset($spicy_name_map[$father])) continue;   // no timeline row → skip
    $tl = $spicy_name_map[$father];
    $spicy_grouped[$tl][] = array(
        'text' => $e['quote'],
        'cite' => spicy_cite(
            isset($e['source']) ? $e['source'] : '',
            isset($e['source_title']) ? $e['source_title'] : null
        ),
    );
}
return $spicy_grouped;

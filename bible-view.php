<?php
$kjvdb = new SQLite3('kjv.sqlite', SQLITE3_OPEN_READONLY);
$commentarydb = new SQLite3('data.sqlite', SQLITE3_OPEN_READONLY);
require("bible-view-helpers.php");

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
} else {
    $currentVerse = 'all';
}

$currentTestament = array_key_exists($currentBook, $old_testament) ? 'old' : 'new';

function getBibleText($book, $chapter, $verse) {
    global $kjvdb;
    $statement = $kjvdb->prepare("SELECT * FROM bible_kjv WHERE book = :book AND txt_location >= :start AND txt_location < :end ORDER BY txt_location ASC");
    $statement->bindValue(':book', $book);
    if ($verse && $verse != 'all') {
        $statement->bindValue(':start', ($chapter * 1000000) + $verse);
        $statement->bindValue(':end', ($chapter * 1000000) + $verse + 1);
    } else {
        $statement->bindValue(':start', $chapter * 1000000);
        $statement->bindValue(':end', ($chapter + 1) * 1000000);
    }
    $result = $statement->execute();

    $output = '<div class="verse-flow">';
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $verseNum = $row['txt_location'] % 1000000;
        $activeClass = ($verse !== 'all' && (int)$verse === (int)$verseNum) ? ' active' : '';
        $output .= '<span class="vnum">' . $verseNum . '</span>';
        $output .= '<span class="v' . $activeClass . '" data-book="' . htmlspecialchars($book) . '" data-chapter="' . (int)$chapter . '" data-verse="' . (int)$verseNum . '">' . htmlspecialchars($row['txt']) . '</span> ';
    }
    $output .= '</div>';
    return $output;
}

function getCommentaries($book, $chapter, $verse) {
    global $commentarydb, $currentBook;
    $query = "SELECT c.*, fm.wiki_url FROM commentary c LEFT JOIN father_meta fm ON c.father_name = fm.name WHERE c.book = :book AND c.location_end >= :start AND c.location_start < :end ORDER BY c.ts ASC";
    $statement = $commentarydb->prepare($query);
    $statement->bindValue(':book', $book);
    if ($verse && $verse != 'all') {
        $start_filter = ($chapter * 1000000) + $verse;
        $end_filter = ($chapter * 1000000) + $verse + 1;
    } else {
        $start_filter = $chapter * 1000000;
        $end_filter = ($chapter + 1) * 1000000;
    }
    $statement->bindValue(':start', $start_filter);
    $statement->bindValue(':end', $end_filter);
    $result = $statement->execute();

    $output = '';
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $year = $row['ts'];
        $chapter_start = intval($row['location_start'] / 1000000);
        $verse_start = $row['location_start'] - ($chapter_start * 1000000);
        $chapter_end = intval($row['location_end'] / 1000000);
        $verse_end = $row['location_end'] - ($chapter_end * 1000000);
        $verse_string = normalize_verse($chapter_start, $verse_start, $chapter_end, $verse_end);

        $output .= '<article class="commentary">';
        $output .= '<div class="cmt-head">';
        if (!empty($row['wiki_url'])) {
            $output .= '<span class="father"><a href="' . htmlspecialchars($row['wiki_url']) . '" target="_blank">' . htmlspecialchars($row['father_name']) . '</a></span>';
        } else {
            $output .= '<span class="father">' . htmlspecialchars($row['father_name']) . '</span>';
        }
        $output .= '<span class="ref">on <b>' . htmlspecialchars($currentBook) . ' ' . htmlspecialchars($verse_string) . '</b></span>';
        $output .= '<span style="flex:1"></span>';
        $output .= '<span class="year">' . format_year($year) . '</span>';
        $output .= '</div>';
        $output .= '<div class="body">' . nl2br(htmlspecialchars($row['txt'])) . '</div>';
        if (!empty($row['source_title'])) {
            if (!empty($row['source_url'])) {
                $source_url = $row['source_url'] . '#' . urlencode(substr($row['txt'], 0, 500));
                $output .= '<div class="src">Source: <a href="' . htmlspecialchars($source_url) . '" class="src-link" target="_blank" title="' . htmlentities($row['source_title'], ENT_QUOTES) . '">' . htmlentities($row['source_title']) . '</a></div>';
            } else {
                $output .= '<div class="src">Source: <span class="src-title">' . htmlentities($row['source_title']) . '</span></div>';
            }
        }
        $output .= '</article>';
    }

    if (!$output) {
        $output = '<div class="commentary"><div class="body" style="color:var(--fg-2)">No commentaries in database for this selection.</div></div>';
    }
    return $output;
}

$prevChapter = $currentChapter > 1 ? $currentChapter - 1 : null;
$nextChapter = $currentChapter < $lookup_chaptertotals[$currentBook] ? $currentChapter + 1 : null;

$prevVerse = null;
$nextVerse = null;
if ($currentVerse && $currentVerse != 'all') {
    $prevVerse = $currentVerse > 1 ? $currentVerse - 1 : null;
    $nextVerse = $currentVerse < $lookup_versestotals[$currentBook . "|" . $currentChapter] ? $currentVerse + 1 : null;
}

$bookPath = urlencode($formattedCurrentBook);
if (!$currentVerse || $currentVerse == 'all') {
    $prev_url = "/$bookPath/$prevChapter/all";
    $next_url = "/$bookPath/$nextChapter/all";
    if (!$prevChapter) {
        $prev_url = "/$bookPath/$currentChapter/all";
    }
    if (!$nextChapter) {
        $next_url = "/$bookPath/$currentChapter/all";
    }
} else {
    $prev_url = "/$bookPath/$currentChapter/$prevVerse";
    $next_url = "/$bookPath/$currentChapter/$nextVerse";
    if (!$prevVerse) {
        $prev_url = "/$bookPath/$prevChapter/all";
        if (!$prevChapter) {
            $prev_url = "/$bookPath/$currentChapter/$currentVerse";
        }
    }
    if (!$nextVerse) {
        $next_url = "/$bookPath/$nextChapter/all";
        if (!$nextChapter) {
            $next_url = "/$bookPath/$currentChapter/$currentVerse";
        }
    }
}

$is_all_view = ($currentVerse === 'all');
$nav_type = $is_all_view ? 'Chapter' : 'Verse';
$prev_label = $is_all_view ? '← Previous Chapter' : '← Previous Verse';
$next_label = $is_all_view ? 'Next Chapter →'     : 'Next Verse →';
$chapter_url = "/$bookPath/$currentChapter/all";

$current_url = $is_all_view
    ? "/$bookPath/$currentChapter/all"
    : "/$bookPath/$currentChapter/$currentVerse";
$prev_disabled = ($prev_url === $current_url);
$next_disabled = ($next_url === $current_url);

ob_start();
?>
<div class="v1-pager">
  <?php if ($prev_disabled): ?>
    <span class="v1-pager-btn disabled">← Previous <span class="v1-pager-type"><?= $nav_type ?></span></span>
  <?php else: ?>
    <a class="v1-pager-btn" href="<?= htmlspecialchars($prev_url) ?>">← Previous <span class="v1-pager-type"><?= $nav_type ?></span></a>
  <?php endif; ?>
  <?php if (!$is_all_view): ?>
    <a class="v1-pager-btn" href="<?= htmlspecialchars($chapter_url) ?>">Whole Chapter</a>
  <?php endif; ?>
  <?php if ($next_disabled): ?>
    <span class="v1-pager-btn disabled">Next <span class="v1-pager-type"><?= $nav_type ?></span> →</span>
  <?php else: ?>
    <a class="v1-pager-btn" href="<?= htmlspecialchars($next_url) ?>">Next <span class="v1-pager-type"><?= $nav_type ?></span> →</a>
  <?php endif; ?>
</div>
<?php
$pager_html = ob_get_clean();

$pageTitle = $currentBook . ' ' . $currentChapter . ($currentVerse !== 'all' ? ':' . $currentVerse : '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<link rel="apple-touch-icon" sizes="180x180" href="/favicon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="HCF Bible">
<meta name="theme-color" content="#080d15">
<link rel="manifest" href="/manifest.json">
<title>Bible Verses and Early Church Commentary | Historical Christian Faith</title>
<meta name="description" content="Explore Bible verses alongside historical commentaries from early church fathers. Deepen your understanding of scripture with insights from Christian history.">
<link href="/bible-view.css?v=3" rel="stylesheet">
<style>
  /* Verse paragraph */
  .verse-flow {
    font-family: var(--serif); font-size: 15px; line-height: 1.75; color: var(--fg-0);
    text-wrap: pretty;
  }
  .verse-flow .vnum {
    font-family: var(--mono); font-size: 11px; color: var(--gold);
    vertical-align: super; margin-right: 3px; margin-left: 2px;
    font-weight: 500;
  }
  .verse-flow .v { display: inline; padding: 1px 2px; border-radius: 3px; transition: background 0.15s; cursor: pointer; }
  .verse-flow .v:hover { background: var(--bg-2); }
  .verse-flow .v.active { background: var(--gold-soft); }
  .verse-flow .v.active:hover { background: var(--gold-soft); }

  /* Commentary */
  .commentary { border-top: 1px solid var(--line-soft); padding: 14px 0 12px; }
  .commentary:first-of-type { border-top: 0; }
  .commentary .cmt-head { display: flex; align-items: baseline; gap: 10px; flex-wrap: wrap; margin-bottom: 10px; }
  .commentary .year {
    font-family: var(--mono); font-size: 11px; color: var(--gold);
    border: 1px solid var(--gold-line); padding: 2px 7px; border-radius: 3px; letter-spacing: 0.04em;
  }
  .commentary .father { font-family: var(--serif); font-size: 16px; font-weight: 600; color: var(--fg-0); }
  .commentary .father a { color: inherit; text-decoration: none; }
  .commentary .father a:hover { color: var(--gold); }
  .commentary .ref { font-family: var(--sans); font-size: 12px; color: var(--fg-2); }
  .commentary .ref b { color: var(--fg-1); font-weight: 500; }
  .commentary .body { font-family: var(--serif); font-size: 14px; line-height: 1.65; color: var(--fg-0); text-wrap: pretty; position: relative; }
  .commentary .body.has-read-more { max-height: 200px; overflow: hidden; }
  .commentary .body.has-read-more::after {
    content: ''; position: absolute; inset: auto 0 0 0; height: 80px;
    background: linear-gradient(transparent, var(--bg-0)); pointer-events: none;
  }
  .commentary .body.expanded { max-height: none; overflow: visible; }
  .commentary .body.expanded::after { display: none; }
  .commentary .read-more {
    display: inline-block; margin-top: 10px; color: var(--gold); font-family: var(--sans);
    font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer;
    text-decoration: underline; text-decoration-color: var(--gold-line); text-underline-offset: 3px;
  }
  .commentary .read-more:hover { text-decoration-color: var(--gold); }
  .commentary .src { margin-top: 10px; font-family: var(--sans); font-size: 11.5px; color: var(--fg-2); letter-spacing: 0.02em; }
  .commentary .src .src-link {
    color: var(--gold); text-decoration: underline;
    text-decoration-color: var(--gold-line); text-underline-offset: 3px; text-decoration-thickness: 1px;
    font-style: italic; transition: text-decoration-color 0.15s, color 0.15s;
  }
  .commentary .src .src-link:hover { text-decoration-color: var(--gold); color: oklch(0.88 0.13 75); }
  .commentary .src .src-link::after { content: '↗'; font-style: normal; font-size: 10px; margin-left: 4px; opacity: 0.7; }
  .commentary .src .src-title { font-style: italic; color: var(--fg-2); }

  /* Layout */
  .v1-shell { display: grid; grid-template-columns: 280px 1fr; min-height: calc(100vh - 56px); }
  .v1-sidebar {
    border-right: 1px solid var(--line-soft); background: var(--bg-0);
    padding: 20px 0; position: sticky; top: 56px; align-self: start;
    height: calc(100vh - 56px); overflow-y: auto;
  }
  .v1-tabs {
    display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
    margin: 0 20px 18px; border-bottom: 1px solid var(--line-soft);
  }
  .v1-tab {
    padding: 10px 0; font-family: var(--sans); font-size: 11px; font-weight: 600;
    letter-spacing: 0.14em; text-transform: uppercase; color: var(--fg-2);
    border-bottom: 2px solid transparent; margin-bottom: -1px; text-align: center; white-space: nowrap;
  }
  .v1-tab.active { color: var(--fg-0); border-bottom-color: var(--gold); }
  .v1-tab:hover { color: var(--fg-0); }
  .v1-books { padding: 0 8px; }
  .v1-book {
    display: block; font-family: var(--serif); font-size: 14.5px; color: var(--fg-1);
    padding: 7px 12px; border-radius: 4px; cursor: pointer; text-decoration: none;
  }
  .v1-book:hover { background: var(--bg-1); color: var(--fg-0); }
  .v1-book.active { background: var(--bg-2); color: var(--fg-0); }
  .v1-chapter-wrap {
    padding: 6px 14px 14px 14px;
    display: none; grid-template-columns: repeat(8, 1fr); gap: 4px;
  }
  .v1-chapter-wrap.open {
    display: grid;
  }
  .v1-chip {
    font-family: var(--mono); font-size: 11px; height: 26px;
    display: grid; place-items: center;
    border: 1px solid var(--line-soft); background: transparent; color: var(--fg-1);
    border-radius: 3px; text-decoration: none;
  }
  .v1-chip:hover { border-color: var(--line); color: var(--fg-0); }
  .v1-chip.active { border-color: var(--gold); background: var(--gold-soft); color: var(--gold); }

  .v1-content { padding: 36px 56px 80px; min-width: 0; }
  .v1-h1 { font-family: var(--serif); font-size: 38px; font-weight: 600; letter-spacing: -0.01em; margin: 0 0 4px; color: var(--fg-0); }
  .v1-pager {
    display: flex; justify-content: space-between; align-items: center;
    margin: 28px 0 32px;
    border-top: 1px solid var(--line-soft); border-bottom: 1px solid var(--line-soft);
    padding: 12px 0;
  }
  .v1-pager-btn {
    font-family: var(--sans); font-size: 12px; letter-spacing: 0.08em; text-transform: uppercase;
    color: var(--fg-1); text-decoration: none; padding: 4px 8px; border-radius: 3px;
  }
  .v1-pager-btn:hover { color: var(--gold); }
  .v1-pager-btn.disabled { color: var(--fg-3); cursor: default; pointer-events: none; }
  .v1-pager-btn.disabled:hover { color: var(--fg-3); }
  .v1-section { margin-top: 40px; }
  .v1-section-title {
    font-family: var(--sans); font-size: 10.5px; font-weight: 600;
    letter-spacing: 0.18em; text-transform: uppercase; color: var(--fg-2);
    margin-bottom: 14px; display: flex; align-items: center; gap: 10px;
  }
  .rule { flex: 1; height: 1px; background: var(--line-soft); }

  @media (max-width: 768px) {
    .v1-shell { grid-template-columns: 1fr; }
    .v1-sidebar {
      position: fixed; top: 56px; left: 0; width: 280px; height: calc(100vh - 56px);
      transform: translateX(-100%); transition: transform 0.22s ease;
      z-index: 50; background: var(--bg-0); border-right: 1px solid var(--line-soft);
    }
    .v1-sidebar.open { transform: translateX(0); }
    .v1-content { padding: 24px 16px 60px; }
    .v1-pager-type { display: none; }
  }
</style>
</head>
<body>

<?php $current_page = 'bible'; $has_sidebar = true; include 'nav.php'; ?>

<div class="v1-shell">
  <aside class="v1-sidebar">
    <div class="v1-tabs">
      <button class="v1-tab<?= $currentTestament === 'old' ? ' active' : '' ?>" data-testament="old">Old Testament</button>
      <button class="v1-tab<?= $currentTestament === 'new' ? ' active' : '' ?>" data-testament="new">New Testament</button>
    </div>
    <?php
      $testamentLists = ['old' => $old_testament, 'new' => $new_testament];
      foreach ($testamentLists as $testamentKey => $books):
        $hiddenAttr = $currentTestament === $testamentKey ? '' : ' hidden';
    ?>
    <div class="v1-books" data-testament="<?= $testamentKey ?>"<?= $hiddenAttr ?>>
      <?php foreach ($books as $book => $chapters):
        $bookFmt = formatBookName($book);
        $isActiveBook = $bookFmt === $formattedCurrentBook;
      ?>
        <a class="v1-book<?= $isActiveBook ? ' active' : '' ?>" href="/<?= urlencode($bookFmt) ?>/1/all"><?= htmlspecialchars($book) ?></a>
          <div class="v1-chapter-wrap<?= $isActiveBook ? ' open' : '' ?>">
            <?php for ($i = 1; $i <= $chapters; $i++): ?>
              <a class="v1-chip<?= $isActiveBook && $i === $currentChapter ? ' active' : '' ?>" href="/<?= urlencode($bookFmt) ?>/<?= $i ?>/all"><?= $i ?></a>
            <?php endfor; ?>
          </div>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
  </aside>

  <main class="v1-content">
    <h1 class="v1-h1"><?= htmlspecialchars($pageTitle) ?></h1>
    <div style="margin-top:28px">
      <?= getBibleText($formattedCurrentBook, $currentChapter, $currentVerse) ?>
    </div>

    <?= $pager_html ?>

    <div class="v1-section">
      <div class="v1-section-title">
        <span>Commentaries</span>
        <span class="rule"></span>
      </div>
      <?= getCommentaries($formattedCurrentBook, $currentChapter, $currentVerse) ?>
    </div>

    <?= $pager_html ?>
  </main>
</div>

<script>
localStorage.setItem('lastVerse', location.pathname);

document.querySelectorAll('.v1-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    const target = tab.dataset.testament;
    document.querySelectorAll('.v1-tab').forEach(t => t.classList.toggle('active', t === tab));
    document.querySelectorAll('.v1-books').forEach(list => {
      list.hidden = list.dataset.testament !== target;
    });
  });
});

document.querySelectorAll('.verse-flow .v[data-verse]').forEach(el => {
  el.addEventListener('click', () => {
    const book = el.dataset.book;
    const chapter = el.dataset.chapter;
    const verse = el.dataset.verse;
    window.location.href = `/${encodeURIComponent(book)}/${encodeURIComponent(chapter)}/${encodeURIComponent(verse)}`;
  });
});

document.querySelectorAll('.commentary .body').forEach(body => {
  if (body.scrollHeight > 200) {
    body.classList.add('has-read-more');
    const link = document.createElement('a');
    link.className = 'read-more';
    link.textContent = '[Read More]';
    link.addEventListener('click', e => {
      e.preventDefault();
      body.classList.remove('has-read-more');
      body.classList.add('expanded');
      link.remove();
    });
    body.insertAdjacentElement('afterend', link);
  }
});

if (window.navigator.standalone || window.matchMedia('(display-mode: standalone)').matches) {
  document.addEventListener('click', e => {
    const link = e.target.closest('a.src-link');
    if (!link) return;
    const url = new URL(link.href, location.origin);
    if (url.origin === location.origin) {
      e.preventDefault();
      window.open(link.href, '_blank');
    }
  });
}

const hamburger = document.querySelector('.hcf-hamburger');
const sidebar = document.querySelector('.v1-sidebar');
const backdrop = document.querySelector('.v1-backdrop');
function setDrawer(open) {
  sidebar.classList.toggle('open', open);
  backdrop.classList.toggle('open', open);
  hamburger.setAttribute('aria-expanded', String(open));
}
hamburger.addEventListener('click', () => setDrawer(!sidebar.classList.contains('open')));
backdrop.addEventListener('click', () => setDrawer(false));

document.querySelectorAll('.v1-book').forEach(book => {
  book.addEventListener('click', e => {
    e.preventDefault();
    const wrap = book.nextElementSibling;
    const isOpen = wrap.classList.contains('open');
    document.querySelectorAll('.v1-chapter-wrap.open').forEach(w => w.classList.remove('open'));
    document.querySelectorAll('.v1-book.active').forEach(b => b.classList.remove('active'));
    if (!isOpen) {
      wrap.classList.add('open');
      book.classList.add('active');
    }
  });
});
</script>
</body>
</html>

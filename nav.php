<?php
// Shared header component for all pages.
// Usage:
//   $current_page = 'bible' | 'writings' | 'about';
//   $has_sidebar = true;  // optional; emits hamburger when the page has a drawer sidebar
//   include 'nav.php';
// Requires bible-view.css to be linked in the including page.
$current_page = $current_page ?? '';
$has_sidebar = $has_sidebar ?? false;
?>
<header class="hcf-header">
    <?php if ($has_sidebar): ?>
    <button class="hcf-hamburger" aria-label="Open navigation" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>
    <?php endif; ?>
    <div class="hcf-brand">
        <img class="hcf-logo" src="/favicon.png" alt="">
        <span>HistoricalChristian<span class="dot">.</span>Faith</span>
    </div>
    <nav class="hcf-nav">
        <a id="nav-bible" href="/john/3/16" class="<?= $current_page === 'bible' ? 'active' : '' ?>">Bible</a>
        <a href="/by_father.php" class="<?= $current_page === 'writings' ? 'active' : '' ?>">Writings</a>
        <a href="/doctrine/" class="<?= $current_page === 'doctrine' ? 'active' : '' ?>">Doctrine</a>
        <a href="/about" class="<?= $current_page === 'about' ? 'active' : '' ?>">About</a>
    </nav>
</header>
<script>
(function () {
  var LOC_KEY = 'hcf:lastLocation';
  var RESUME_KEY = 'hcf:resuming';

  // Read the previously-saved location BEFORE we overwrite it with the current
  // position below — the resume redirect relies on this exact stored value.
  var saved = null;
  try { saved = JSON.parse(localStorage.getItem(LOC_KEY) || 'null'); } catch (e) {}

  // Whether this load is a cold-launch resume (flag set by /resume). Consume it
  // now, before save() runs, so a stray reload can't accidentally trigger it.
  var resuming = false;
  try {
    resuming = !!sessionStorage.getItem(RESUME_KEY);
    if (resuming) sessionStorage.removeItem(RESUME_KEY);
  } catch (e) {}

  // Point the Bible nav link at the last-read verse (existing behavior).
  try {
    var v = localStorage.getItem('lastVerse');
    if (v) {
      var bibleLink = document.getElementById('nav-bible');
      if (bibleLink) bibleLink.href = v;
    }
  } catch (e) {}

  // Persist current URL + scroll position so /resume can restore it on a cold launch.
  function save() {
    try {
      localStorage.setItem(LOC_KEY, JSON.stringify({
        url: location.pathname + location.search + location.hash,
        scroll: window.scrollY || document.documentElement.scrollTop || 0
      }));
    } catch (e) {}
  }
  save(); // capture the URL immediately, even if the user never scrolls
  var scrollTimer;
  window.addEventListener('scroll', function () {
    clearTimeout(scrollTimer);
    scrollTimer = setTimeout(save, 250);
  }, { passive: true });
  // iOS freezes then kills backgrounded home-screen apps; pagehide and
  // visibilitychange are the last reliable moments to persist before that.
  window.addEventListener('pagehide', save);
  document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'hidden') save();
  });

  // Restore scroll after a resume redirect landed us on the saved page.
  if (resuming && saved && typeof saved.scroll === 'number' && saved.scroll > 0) {
    var restore = function () { window.scrollTo(0, saved.scroll); };
    // Content is server-rendered, so height is settled by DOMContentLoaded;
    // re-apply after load in case images/fonts reflow the page.
    document.addEventListener('DOMContentLoaded', restore);
    window.addEventListener('load', restore);
  }
})();
</script>
<?php if ($has_sidebar): ?>
<div class="v1-backdrop"></div>
<?php endif; ?>

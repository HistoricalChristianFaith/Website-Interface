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
        <span class="mark">&#x2720;</span>
        <span>HistoricalChristian<span class="dot">.</span>Faith</span>
    </div>
    <nav class="hcf-nav">
        <a href="/john/3/16" class="<?= $current_page === 'bible' ? 'active' : '' ?>">Bible</a>
        <a href="/by_father.php" class="<?= $current_page === 'writings' ? 'active' : '' ?>">Writings</a>
        <a href="/about" class="<?= $current_page === 'about' ? 'active' : '' ?>">About</a>
    </nav>
</header>
<?php if ($has_sidebar): ?>
<div class="v1-backdrop"></div>
<?php endif; ?>

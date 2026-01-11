<?php
// Shared navigation bar component
// Usage: include 'nav.php'; after setting $current_page = 'bible' | 'writings' | 'about'
$current_page = $current_page ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1a237e; box-shadow: 0 2px 4px rgba(0,0,0,.1);">
    <div class="container">
        <a class="navbar-brand" href="/john/3/16" style="font-family: 'Lora', serif; font-weight: 700; font-size: 1.5rem;">HistoricalChristian.Faith</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link<?= $current_page === 'bible' ? ' active' : '' ?>" href="/john/3/16">Bible</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= $current_page === 'writings' ? ' active' : '' ?>" href="/by_father.php">Writings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= $current_page === 'about' ? ' active' : '' ?>" href="/about">About</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

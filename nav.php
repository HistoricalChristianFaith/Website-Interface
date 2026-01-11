<?php
// Shared navigation bar component
// Usage: include 'nav.php'; after setting $current_page = 'bible' | 'writings' | 'about'
$current_page = $current_page ?? '';
?>
<style>
.navbar-nav .nav-link {
    position: relative;
    padding: 0.5rem 1.1rem;
    margin: 0 0.15rem;
    font-weight: 500;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}
.navbar-nav .nav-link:hover {
    background-color: rgba(255,255,255,0.1);
    border-radius: 4px;
}
.navbar-nav .nav-link.active {
    color: #fff !important;
}
.navbar-nav .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 2px;
    left: 1rem;
    right: 1rem;
    height: 2px;
    background: #fff;
    border-radius: 1px;
}
</style>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1a237e; box-shadow: 0 2px 4px rgba(0,0,0,.1);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/john/3/16" style="font-family: 'Lora', serif; font-weight: 700; font-size: 1.5rem;">
            <img src="/favicon.png" alt="" height="32" class="me-2" style="border-radius: 4px;">
            <span class="d-none d-sm-inline">HistoricalChristian.Faith</span>
            <span class="d-sm-none">HCF</span>
        </a>
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

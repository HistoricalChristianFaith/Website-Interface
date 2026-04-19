import {
  formatBookName,
  book_normalize_userinput,
  old_testament,
  new_testament,
  lookup_chaptertotals,
  lookup_formatted_to_full_booknames,
  lookup_versestotals,
  spicy
} from './helpers.js';
import { initDatabases, getBibleText, getCommentaries } from './db.js';
import { attachInteractions, attachWritingsInteractions } from './interactions.js';

// ---- Module-level state ----
let kjvDb = null;
let commentaryDb = null;
const templates = {};

async function loadTemplates() {
  const names = ['nav', 'bible-view', 'about', 'by-father'];
  await Promise.all(names.map(async (name) => {
    templates[name] = await fetch(`./templates/${name}.ejs`).then(r => r.text());
  }));
}

function renderNav(data) {
  return window.ejs.render(templates['nav'], data);
}

// ---- Bootstrap on DOM ready ----
document.addEventListener('DOMContentLoaded', async () => {
  await loadTemplates();

  const dbs = await initDatabases();
  kjvDb = dbs.kjvDb;
  commentaryDb = dbs.commentaryDb;

  navigate(window.location.pathname);

  document.addEventListener('click', (e) => {
    const anchor = e.target.closest('a[href]');
    if (!anchor) return;

    const href = anchor.getAttribute('href');
    if (href && href.startsWith('/') && !href.startsWith('//')) {
      if (anchor.getAttribute('target') === '_blank') return;
      e.preventDefault();
      navigate(href);
    }
  });

  window.addEventListener('popstate', () => {
    navigate(window.location.pathname);
  });
});

/**
 * Main SPA router. Matches the path against known routes
 * and renders the appropriate view.
 *
 * @param {string} path - URL pathname (e.g. '/matthew/3/16')
 */
window.navigate = navigate;
async function navigate(path) {
  // Push to history if the path differs from current
  if (window.location.pathname !== path) {
    window.history.pushState({}, '', path);
  }

  // Strip leading/trailing slashes and split
  const segments = path.replace(/^\/+|\/+$/g, '').split('/');

  // ---- Route: / (root) -> redirect to john/3/16 ----
  if (!segments[0]) {
    await renderBibleView('john', '3', '16');
    return;
  }

  // ---- Route: /about ----
  if (segments[0] === 'about') {
    await renderAbout();
    return;
  }

  // ---- Route: /by_father or /by_father.php ----
  if (segments[0] === 'by_father' || segments[0] === 'by_father.php') {
    await renderWritings();
    return;
  }

  // ---- Route: /BOOK/CHAPTER/VERSE (bible view, default) ----
  await renderBibleView(segments[0], segments[1], segments[2]);
}

// ===================================================================
//  Bible View  (port of bible-view.php lines 6-161)
// ===================================================================

async function renderBibleView(rawBook, rawChapter, rawVerse) {
  // ---- Validate book (lines 6-11) ----
  const userInputBook = rawBook || 'matthew';
  let formattedCurrentBook = formatBookName(book_normalize_userinput(userInputBook));
  if (!lookup_formatted_to_full_booknames[formattedCurrentBook]) {
    formattedCurrentBook = 'matthew';
  }
  const currentBook = lookup_formatted_to_full_booknames[formattedCurrentBook];

  // ---- Validate chapter (lines 13-19) ----
  let currentChapter = parseInt(rawChapter, 10) || 1;
  if (currentChapter < 1) {
    currentChapter = 1;
  }
  if (currentChapter > lookup_chaptertotals[currentBook]) {
    currentChapter = lookup_chaptertotals[currentBook];
  }

  // ---- Validate verse (lines 21-31) ----
  let currentVerse;
  if (rawVerse !== undefined && rawVerse !== null && rawVerse !== '') {
    currentVerse = parseInt(rawVerse, 10);
    if (currentVerse) {
      if (currentVerse < 1) {
        currentVerse = 1;
      }
      const maxVerse = lookup_versestotals[currentBook + '|' + currentChapter];
      if (currentVerse > maxVerse) {
        currentVerse = maxVerse;
      }
    } else {
      currentVerse = 'all';
    }
  } else {
    currentVerse = 'all';
  }

  // ---- Determine testament (line 33) ----
  const currentTestament = old_testament.hasOwnProperty(currentBook) ? 'old' : 'new';

  // ---- Query data ----
  const bibleRows = await getBibleText(kjvDb, formattedCurrentBook, currentChapter, currentVerse);
  const commentaryRows = await getCommentaries(commentaryDb, formattedCurrentBook, currentChapter, currentVerse, currentBook);

  // ---- Compute prev/next navigation (lines 113-160) ----
  const prevChapter = currentChapter > 1 ? currentChapter - 1 : null;
  const nextChapter = currentChapter < lookup_chaptertotals[currentBook] ? currentChapter + 1 : null;

  let prevVerse = null;
  let nextVerse = null;
  if (currentVerse && currentVerse !== 'all') {
    prevVerse = currentVerse > 1 ? currentVerse - 1 : null;
    const maxVerse = lookup_versestotals[currentBook + '|' + currentChapter];
    nextVerse = currentVerse < maxVerse ? currentVerse + 1 : null;
  }

  const bookPath = encodeURIComponent(formattedCurrentBook);
  let prev_url, next_url;

  if (!currentVerse || currentVerse === 'all') {
    // Chapter navigation
    prev_url = `/${bookPath}/${prevChapter}/all`;
    next_url = `/${bookPath}/${nextChapter}/all`;
    if (!prevChapter) {
      prev_url = `/${bookPath}/${currentChapter}/all`;
    }
    if (!nextChapter) {
      next_url = `/${bookPath}/${currentChapter}/all`;
    }
  } else {
    // Verse navigation
    prev_url = `/${bookPath}/${currentChapter}/${prevVerse}`;
    next_url = `/${bookPath}/${currentChapter}/${nextVerse}`;
    if (!prevVerse) {
      prev_url = `/${bookPath}/${prevChapter}/all`;
      if (!prevChapter) {
        prev_url = `/${bookPath}/${currentChapter}/${currentVerse}`;
      }
    }
    if (!nextVerse) {
      next_url = `/${bookPath}/${nextChapter}/all`;
      if (!nextChapter) {
        next_url = `/${bookPath}/${currentChapter}/${currentVerse}`;
      }
    }
  }

  const is_all_view = (currentVerse === 'all');
  const nav_type = is_all_view ? 'Chapter' : 'Verse';
  const chapter_url = `/${bookPath}/${currentChapter}/all`;

  const current_url = is_all_view
    ? `/${bookPath}/${currentChapter}/all`
    : `/${bookPath}/${currentChapter}/${currentVerse}`;

  const prev_disabled = (prev_url === current_url);
  const next_disabled = (next_url === current_url);

  const pageTitle = currentBook + ' ' + currentChapter + (currentVerse !== 'all' ? ':' + currentVerse : '');

  // ---- Render template ----
  const navHtml = renderNav({ current_page: 'bible', has_sidebar: true });
  const html = window.ejs.render(templates['bible-view'], {
    navHtml,
    pageTitle,
    currentBook,
    formattedCurrentBook,
    currentChapter,
    currentVerse,
    currentTestament,
    verses: bibleRows,
    commentaries: commentaryRows,
    old_testament,
    new_testament,
    lookup_chaptertotals,
    formatBookName,
    prev_url,
    next_url,
    prev_disabled,
    next_disabled,
    nav_type,
    is_all_view,
    chapter_url,
    bookPath
  });

  document.getElementById('app').innerHTML = html;
  attachInteractions();
}

// ===================================================================
//  About page
// ===================================================================

async function renderAbout() {
  const navHtml = renderNav({ current_page: 'about', has_sidebar: false });
  const html = window.ejs.render(templates['about'], { navHtml });
  document.getElementById('app').innerHTML = html;

  function displayRandomQuote() {
    const idx = Math.floor(Math.random() * spicy.length);
    const q = spicy[idx];
    document.getElementById('spicyQuote').textContent = q.quote;
    document.getElementById('spicyAttribution').innerHTML =
      '- <strong><a href="' + q.source + '" target="_blank">' + q.father + '</a></strong>';
  }
  document.getElementById('refreshQuote').addEventListener('click', displayRandomQuote);
  displayRandomQuote();
}

// ===================================================================
//  Writings / by_father page
// ===================================================================

async function renderWritings() {
  let menuHtml = '<p>Loading menu...</p>';
  try {
    menuHtml = await fetch('https://historicalchristianfaith.github.io/Writings-Database/menu.html?v=1').then(r => r.text());
  } catch (e) {
    menuHtml = '<p>Error loading menu. Check your internet connection.</p>';
  }
  const navHtml = renderNav({ current_page: 'writings', has_sidebar: true });
  const html = window.ejs.render(templates['by-father'], { navHtml, menuHtml });
  document.getElementById('app').innerHTML = html;
  attachWritingsInteractions();
  initWritingsPage();
}

function initWritingsPage() {
  function getUrlHashFragment() {
    const parts = window.location.href.split('#');
    return (parts.length > 1 && parts[1].length > 0) ? '#' + parts[1] : '#';
  }

  function loadFile(filePath) {
    document.getElementById('content').removeAttribute('srcdoc');
    document.getElementById('content').src =
      'https://historicalchristianfaith.github.io/Writings-Database/' + filePath;
    const url = new URL(window.location);
    url.searchParams.set('file', filePath);
    window.history.pushState({}, '', url);
    $('#sidebarMenu').removeClass('show');
  }

  function loadMetaFile(filePath) {
    document.getElementById('content').removeAttribute('srcdoc');
    document.getElementById('content').src = filePath;
  }

  $('#cfmenu').jstree();

  const urlParams = new URLSearchParams(window.location.search);
  const file = urlParams.get('file');
  if (file) {
    document.getElementById('content').removeAttribute('srcdoc');
    document.getElementById('content').src =
      'https://historicalchristianfaith.github.io/Writings-Database/' + file + getUrlHashFragment();

    const tree = $.jstree.reference('#cfmenu');
    const allNodes = tree.get_json('#', { flat: true });
    const nodeToSelect = allNodes.find(n => n.li_attr && n.li_attr['data-fname'] === file);
    if (nodeToSelect) {
      tree.select_node(nodeToSelect.id);
    }
  }

  $('#cfmenu').on('activate_node.jstree', function (e, data) {
    if (data.node.children.length > 0) {
      $('#cfmenu').jstree(true).toggle_node(data.node);
      if (data.node.li_attr && data.node.li_attr['data-metadataurl']) {
        loadMetaFile(data.node.li_attr['data-metadataurl']);
      }
      return;
    }
    if (data.node.li_attr && data.node.li_attr['data-fname']) {
      loadFile(data.node.li_attr['data-fname']);
    }
  });
}

/**
 * Attach all interactive behaviors for the bible-view page.
 *
 * Mirrors the <script> block from bible-view.php lines 373-419.
 * Should be called each time the bible view is rendered into the DOM.
 */
export function attachInteractions() {
  // --- Tab switching for Old/New Testament sidebar ---
  document.querySelectorAll('.v1-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      const target = tab.dataset.testament;
      document.querySelectorAll('.v1-tab').forEach(t =>
        t.classList.toggle('active', t === tab)
      );
      document.querySelectorAll('.v1-books').forEach(list => {
        list.hidden = list.dataset.testament !== target;
      });
    });
  });

  // --- Verse click navigation ---
  // Uses window.location.href so the link-click interceptor in app.js
  // can catch the navigation and route it through navigate().
  document.querySelectorAll('.verse-flow .v[data-verse]').forEach(el => {
    el.addEventListener('click', () => {
      const book = el.dataset.book;
      const chapter = el.dataset.chapter;
      const verse = el.dataset.verse;
      const url = `/${encodeURIComponent(book)}/${encodeURIComponent(chapter)}/${encodeURIComponent(verse)}`;
      if (window.navigate) {
        window.navigate(url);
      } else {
        window.location.href = url;
      }
    });
  });

  // --- "Read More" toggle for long commentaries ---
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

  // --- Hamburger / sidebar toggle ---
  const hamburger = document.querySelector('.hcf-hamburger');
  const sidebar = document.querySelector('.v1-sidebar');
  const backdrop = document.querySelector('.v1-backdrop');

  if (hamburger && sidebar && backdrop) {
    function setDrawer(open) {
      sidebar.classList.toggle('open', open);
      backdrop.classList.toggle('open', open);
      hamburger.setAttribute('aria-expanded', String(open));
    }
    hamburger.addEventListener('click', () =>
      setDrawer(!sidebar.classList.contains('open'))
    );
    backdrop.addEventListener('click', () => setDrawer(false));
  }
}

/**
 * Attach interactive behaviors for the by_father (writings) page.
 *
 * Mirrors the sidebar toggle from by_father.php lines 131-142.
 * Should be called each time the writings view is rendered into the DOM.
 */
export function attachWritingsInteractions() {
  const hamburger = document.querySelector('.hcf-hamburger');
  const sidebarMenu = document.getElementById('sidebarMenu');
  const backdrop = document.querySelector('.v1-backdrop');

  if (hamburger && sidebarMenu && backdrop) {
    hamburger.addEventListener('click', () => {
      const open = !sidebarMenu.classList.contains('show');
      sidebarMenu.classList.toggle('show', open);
      backdrop.classList.toggle('open', open);
      hamburger.setAttribute('aria-expanded', String(open));
    });

    backdrop.addEventListener('click', () => {
      sidebarMenu.classList.remove('show');
      backdrop.classList.remove('open');
      hamburger.setAttribute('aria-expanded', 'false');
    });
  }
}

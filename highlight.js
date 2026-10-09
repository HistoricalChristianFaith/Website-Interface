/*
 * highlight.js — quote deep-link highlighter for the Writings renderer.
 *
 * Cloned from the Writings-Database repo and refactored for by_father.php's
 * server-rendered, client-swapped page. Two changes from the original:
 *   1. SCOPED: it operates only on the `.writing` article container, never the
 *      whole <body> — so it can't clobber the site header or the jsTree sidebar
 *      (and can't destroy their event bindings).
 *   2. RE-INVOKABLE: it exposes window.highlightWriting(container) instead of
 *      running once inside a load listener, so by_father.php can call it on the
 *      initial page load AND after every client-side article swap.
 *
 * It reads the current location.hash (the shared "#<quote text>" deep link),
 * finds the passage inside the article, wraps its first/last few words in a
 * gradient <span>, and scrolls it to the middle of the viewport.
 */
(function () {
    // Inject the highlight styles once (idempotent across swaps).
    function ensureStyles() {
        if (document.getElementById('hcf-highlight-styles')) return;
        var style = document.createElement('style');
        style.id = 'hcf-highlight-styles';
        style.textContent = `
          .writing .gradient-highlight {
            background: linear-gradient(to right, rgba(173, 216, 230, 0.55), rgba(173, 216, 230, 0));
          }
          .writing .gradient-highlight-backward {
            background: linear-gradient(to left, rgba(173, 216, 230, 0.55), rgba(173, 216, 230, 0));
          }
        `;
        document.head.appendChild(style);
    }

    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function createPattern(words, count) {
        var wordSegment = words.slice(0, count).join(' ');
        return new RegExp(escapeRegExp(wordSegment), 'gi');
    }

    // Wrap the single unique match of `pattern` in `haystack` with a span of
    // `className`. Returns the (possibly modified) haystack.
    function highlightMatches(haystack, pattern, className) {
        var matches = [];
        var match;
        while ((match = pattern.exec(haystack)) !== null) {
            matches.push(match);
        }
        if (matches.length === 1) {
            haystack = haystack.replace(matches[0][0], '<span class="' + className + '">' + matches[0][0] + '</span>');
        }
        return haystack;
    }

    // Highlight the quote named by location.hash inside `container` (a `.writing`
    // element). No hash → clears any prior highlight and returns. Safe to call
    // repeatedly (each call re-derives from the live hash + the current article).
    function highlightWriting(container) {
        if (!container) container = document.querySelector('.writing');
        if (!container) return;

        // Clear any highlight spans left over from a previous invocation so a
        // re-run (e.g. after an article swap) starts from clean markup.
        container.querySelectorAll('span.gradient-highlight, span.gradient-highlight-backward').forEach(function (span) {
            span.replaceWith(document.createTextNode(span.textContent));
        });
        container.normalize();

        if (!window.location.hash) return;

        var textToHighlight = decodeURIComponent(window.location.hash.replace(/\+/g, ' '));
        if (textToHighlight.startsWith('#')) {
            textToHighlight = textToHighlight.substring(1);
        }
        var needle = textToHighlight.trim();
        if (!needle) return;

        // Footnote back-and-forth links inside the fragment use short "#fnN"/"#rN"
        // anchors; those are ordinary in-page jumps, not quote deep-links, so leave
        // them to the browser.
        if (/^[fr]?n?\d+$/i.test(needle)) return;

        ensureStyles();

        var original = container.innerHTML;
        var haystack = original;
        var words = needle.split(' ');

        // Try the first and last 5 words; fall back to 3 if neither anchored.
        haystack = highlightMatches(haystack, createPattern(words, 5), 'gradient-highlight');
        haystack = highlightMatches(haystack, createPattern(words.slice(-5), 5), 'gradient-highlight-backward');

        if (!haystack.includes('gradient-highlight') && !haystack.includes('gradient-highlight-backward')) {
            haystack = highlightMatches(haystack, createPattern(words, 3), 'gradient-highlight');
            haystack = highlightMatches(haystack, createPattern(words.slice(-3), 3), 'gradient-highlight-backward');
        }

        // Nothing matched -> leave the DOM untouched (re-parsing would, e.g.,
        // reload an embedded PDF frame for no reason).
        if (haystack === original) return;

        // Rewrite only the article container (never document.body).
        container.innerHTML = haystack;

        // Scroll the (end of the) match to the middle of the viewport.
        var targetSpan = container.querySelector('span.gradient-highlight-backward')
                      || container.querySelector('span.gradient-highlight');
        if (targetSpan) {
            var rect = targetSpan.getBoundingClientRect();
            var middle = rect.top + window.pageYOffset - (window.innerHeight / 2) + (rect.height / 2);
            window.scrollTo({ top: middle, behavior: 'smooth' });
        }
    }

    window.highlightWriting = highlightWriting;
})();

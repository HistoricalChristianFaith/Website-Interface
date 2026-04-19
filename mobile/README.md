# HistoricalChristian.Faith - Mobile App

Native iOS/Android app version of [historicalchristian.faith](https://historicalchristian.faith), built with Capacitor + EJS templates.

This is a parallel codebase to the PHP web version in the parent directory. Both produce the same UI from the same data -- the mobile version swaps PHP's `<?= ?>` for EJS's `<%= %>` and runs client-side in a Capacitor WebView instead of server-side on Apache.

## How it relates to the PHP version

| PHP (web) | EJS (mobile) | What changed |
|---|---|---|
| `bible-view.php` | `src/app.js` + `src/templates/bible-view.ejs` | Logic in JS, template in EJS |
| `bible-view-helpers.php` | `src/helpers.js` | PHP arrays became JS objects |
| `nav.php` | `src/templates/nav.ejs` | `<?php if(): ?>` became `<% if() { %>` |
| `about.php` | `src/templates/about.ejs` | Same HTML, different delimiters |
| `by_father.php` | `src/templates/by-father.ejs` | Same HTML, jsTree init moved to JS |
| `bible-view.css` | Shared (copied at build time) | No changes |
| `kjv.sqlite` / `data.sqlite` | Bundled in app (renamed to `.db`) | Same databases |
| `.htaccess` URL rewriting | Client-side router in `app.js` | Same URL patterns |

## Project structure

```
mobile/
  capacitor.config.ts     Capacitor app config (app ID, webDir, plugins)
  package.json            Dependencies: Capacitor 6, @capacitor-community/sqlite, EJS
  build.js                Assembles www/ from src/ + parent assets

  src/
    index.html            Shell HTML with all CSS (loaded by Capacitor WebView)
    app.js                Entry point: router, validation, template rendering
    db.js                 SQLite queries (getBibleText, getCommentaries)
    helpers.js            Port of bible-view-helpers.php (book lookups, verse totals)
    interactions.js       UI event handlers (verse clicks, sidebar, read-more)

    templates/
      bible-view.ejs      Bible viewer (sidebar + verses + commentaries + pager)
      nav.ejs             Shared header/navigation bar
      about.ejs           About page with church father quotes
      by-father.ejs       Writings browser (jsTree sidebar + iframe)

  assets/databases/       Created by build.js -- bundled SQLite databases
  www/                    Created by build.js -- served by Capacitor
  ios/                    Created by `npx cap add ios`
  android/                Created by `npx cap add android`
```

## Prerequisites

- Node.js 18+
- Xcode (for iOS builds)
- Android Studio (for Android builds)
- The parent directory must contain `bible-view.css`, `favicon.png`, `kjv.sqlite`, and `data.sqlite`

## Build and run

```bash
# Install dependencies
npm install

# Assemble the www/ directory and copy databases
npm run build

# Add native platforms (first time only)
npx cap add ios
npx cap add android

# Sync web assets + databases to native projects
npx cap sync

# Open in Xcode / Android Studio to build and run
npx cap open ios
npx cap open android
```

After making changes to `src/`, run `npm run build && npx cap sync` to update the native projects.

## How the app works

1. Capacitor loads `index.html` in a native WebView
2. `app.js` initializes: loads EJS templates, opens bundled SQLite databases
3. A client-side router matches URL paths (`/book/chapter/verse`, `/about`, `/by_father`)
4. For the bible view, `app.js` validates the book/chapter/verse, queries both databases, and renders `bible-view.ejs` with the results
5. Link clicks are intercepted for SPA navigation (no page reloads)
6. The Writings page fetches its menu from GitHub Pages (requires internet); everything else works offline

## Databases

The app bundles two SQLite databases from the parent directory:

- `kjv.sqlite` (7 MB) -- KJV Bible text. Table `bible_kjv` with columns `book`, `txt_location`, `txt`. Location encoding: `chapter * 1000000 + verse`.
- `data.sqlite` (104 MB) -- Commentaries from [HistoricalChristianFaith/Commentaries-Database](https://github.com/HistoricalChristianFaith/Commentaries-Database). Table `commentary` joined with `father_meta`.

The build script renames these to `.db` (required by `@capacitor-community/sqlite`). On first launch, `copyFromAssets()` copies them from the app bundle to device storage.

To update the commentary data, replace `data.sqlite` in the parent directory and rebuild.

## Porting changes between PHP and mobile

The two codebases mirror each other closely. When making a change to the PHP version:

1. **Template changes** (HTML structure, CSS classes): apply the same change in the corresponding `.ejs` file, swapping `<?= $var ?>` for `<%= var %>` and `<?php foreach(): ?>` for `<% for (...) { %>`
2. **New books or verse counts**: update both `bible-view-helpers.php` and `src/helpers.js`
3. **Query changes**: update both `bible-view.php` and `src/db.js`
4. **CSS changes**: `bible-view.css` is shared automatically. Page-specific styles live in `index.html`'s `<style>` block.
5. **New commentary data**: just replace `data.sqlite` -- no code changes needed

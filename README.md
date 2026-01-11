# Historical Christian Commentaries Interface

A web application that provides an interactive platform to explore the Bible alongside historical Christian commentaries from the early Church Fathers (Ante-Nicene Fathers and Post-Nicene Fathers collections).

**Live site:** https://historicalchristian.faith/

This is the reference implementation/demo of the SQLite file [compiled](https://github.com/HistoricalChristianFaith/Commentaries-Database/blob/master/compile_data.py) from our [Commentaries-Database](https://github.com/HistoricalChristianFaith/Commentaries-Database). It also contains a frontend interface to our [Writings-Database](https://github.com/HistoricalChristianFaith/Writings-Database).

Any changes made in master branch on this repo will reflect <a href='https://historicalchristian.faith/' target='_blank'>on the website</a> within a couple minutes.

## Features

### Bible Commentary Viewer (`/book/chapter/verse`)
- Browse any Bible passage with associated patristic commentaries
- Supports flexible URL patterns:
  - `/matthew/1/all` - Full chapter view
  - `/matthew/1/5` - Single verse
  - `/luke/3/16-28` - Verse ranges
- Each commentary card displays:
  - Church Father's name (linked to Wikipedia)
  - Year written (AD)
  - Full commentary text with "Read More" for longer passages
  - Source attribution with links to original texts
- Sequential navigation with Previous/Next buttons
- Dropdown menus for book, chapter, and verse selection

### Historical Writings Browser (`/by_father`)
- Browse complete historical Christian writings organized by Church Father
- Hierarchical tree navigation using jsTree
- Two-panel layout with sidebar navigation and content viewer
- Deep-linking support for specific documents and sections

### Landing Page
- Introduction to the project with educational quotes from C.S. Lewis, John Wesley, Martin Luther, Jerome, and Augustine
- Multi-language support via Google Translate (10+ languages)

## Tech Stack

**Backend:**
- PHP
- SQLite3 (two databases: `data.sqlite` for commentaries, `kjv.sqlite` for Bible text)
- Apache with mod_rewrite for clean URLs

**Frontend:**
- HTML5/CSS3/JavaScript
- Bootstrap 5.3
- jQuery
- jsTree (for hierarchical navigation)
- Font Awesome 6.0
- Google Fonts (Lora, Open Sans)

## Project Structure

```
├── index.php                 # Landing page
├── bible-view.php            # Main Bible commentary viewer
├── bible-view-helpers.php    # Helper functions (book normalization, lookups)
├── bible-view.css            # Custom styles for commentary view
├── by_father.php             # Historical writings browser
├── .htaccess                 # URL rewriting rules
├── sitemap_generator.php     # SEO sitemap generation
├── update_db.php             # Database update utility
├── data.sqlite               # Compiled commentaries database
├── kjv.sqlite                # King James Bible text database
└── kjv/
    ├── bible_kjv.csv         # Source CSV for KJV
    └── kjv_sqlite.py         # Script to populate KJV database
```

## URL Routing

The `.htaccess` file rewrites clean URLs to PHP parameters:
- `/matthew/1/5` → `bible-view.php?book=matthew&chapter=1&verse=5`
- Supports extensive book name abbreviations (e.g., "Matt", "Mt", "1Cor", etc.)

## Database Schema

**Commentaries (`data.sqlite`):**
- Location encoding: `chapter * 1,000,000 + verse`
- Commentary entries can span multiple verses (`location_start`, `location_end`)
- Metadata: father name, text, source title, source URL, year

**Bible Text (`kjv.sqlite`):**
- KJV verses with location encoding for efficient range queries

## Build/Deploy Process

1) [Compile](https://github.com/HistoricalChristianFaith/Commentaries-Database/blob/master/compile_data.py) a SQL file from the [Commentaries-Database](https://github.com/HistoricalChristianFaith/Commentaries-Database) [Or just download the [latest SQL file release](https://github.com/HistoricalChristianFaith/Commentaries-Database/releases/tag/latest)]. Rename this file to data.sqlite
2) Move the `data.sqlite` file to this `Website-Interface` directory.
3) Run `kjv/kjv_sqlite.py` to populate the `kjv.sqlite` with the King James Bible so that the relevant Bible verses for a user's query will show (KJV chosen because in the public domain)
4) Now serve the files via a PHP webserver, and it should just work.

## Related Repositories

- [Commentaries-Database](https://github.com/HistoricalChristianFaith/Commentaries-Database) - Source data for patristic commentaries
- [Writings-Database](https://github.com/HistoricalChristianFaith/Writings-Database) - Historical Christian writings (hosted on GitHub Pages)

# Alternatives

- https://catenabible.com
    - The most polished app, and a wonderful bible companion!
    - It's a closed database, which contains data from a wide variety of sources (not just the ANF/NPNF series)
    - Negatives:
        - It identifies the person behind a quote, but not the work in which the quote appears.
        - Its commentaries are tied only to individual verses (and not passages that span multiple verses)
        - Its commentaries from the ANF/NPNF often are lacking context / are cut off.

- https://www.earlychristianwritings.com/e-catena/
    - A wonderful quick reference
    - Only includes citations from the ANF/NPNF
    - But does identify the work in which a quote appears, AND provides a link directly to that work!

- https://www.catholiccrossreference.online/fathers/
    - Similar to earlychristianwritings.com/e-catena
    - Uses citations from the ANF/NPNF
    - Identifies the work in which a quote appears, AND provides a link directly to that work!

- https://www.biblindex.org/
    - The most... scholarly?
    - Laborious to use
    - Requires a bigger brain than I have.

- [Ancient Christian Commentary on Scripture](https://www.logos.com/product/31152/ancient-christian-commentary-on-scripture-complete-set-accs)
    - Contains commentaries from a wide variety of sources, many of which appear to be custom translated just for this product!
    - Identifies the work in which a quote appears, and often provides good historical background!
    - The commentaries shown are not exhaustive, but are curated... usually with just a couple chosen per verse.
    - Costs enough to empty your wallet.

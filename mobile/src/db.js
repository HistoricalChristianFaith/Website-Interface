import { CapacitorSQLite, SQLiteConnection } from '@capacitor-community/sqlite';
import { normalize_verse, format_year } from './helpers.js';

/**
 * Initialize both SQLite databases (KJV bible text and commentary data).
 * Copies bundled .db files from app assets on first run, then opens
 * read-only connections.
 *
 * @returns {Promise<{kjvDb: object, commentaryDb: object}>}
 */
export async function initDatabases() {
  const sqliteConnection = new SQLiteConnection(CapacitorSQLite);

  // Copy bundled databases from assets on first run (no-op if already copied)
  await sqliteConnection.copyFromAssets(false);

  // Open KJV bible database (readonly)
  const kjvDb = await sqliteConnection.createConnection(
    'kjv', false, 'no-encryption', 1, true
  );
  await kjvDb.open();

  // Open commentary database (readonly)
  const commentaryDb = await sqliteConnection.createConnection(
    'data', false, 'no-encryption', 1, true
  );
  await commentaryDb.open();

  return { kjvDb, commentaryDb };
}

/**
 * Fetch bible text rows for a given book, chapter, and verse.
 *
 * Location encoding: chapter * 1000000 + verse
 * When verse is 'all' or falsy, returns all verses in the chapter.
 *
 * @param {object} db - The kjvDb connection
 * @param {string} book - Formatted book name (e.g. 'matthew')
 * @param {number} chapter
 * @param {number|string} verse - Verse number or 'all'
 * @returns {Promise<Array<{book: string, txt_location: number, txt: string}>>}
 */
export async function getBibleText(db, book, chapter, verse) {
  const sql = 'SELECT * FROM bible_kjv WHERE book = ? AND txt_location >= ? AND txt_location < ? ORDER BY txt_location ASC';

  let start, end;
  if (verse && verse !== 'all') {
    start = (chapter * 1000000) + verse;
    end = (chapter * 1000000) + verse + 1;
  } else {
    start = chapter * 1000000;
    end = (chapter + 1) * 1000000;
  }

  const result = await db.query(sql, [book, start, end]);
  return result.values || [];
}

/**
 * Fetch commentary rows for a given book, chapter, and verse.
 *
 * Each returned row is augmented with computed fields:
 *   - verse_string: human-readable verse reference (e.g. "3:16" or "3:16-18")
 *   - formatted_year: display string for the year (e.g. "AD 350" or "65 BC")
 *   - wiki_url: Wikipedia link for the father (may be null)
 *
 * @param {object} db - The commentaryDb connection
 * @param {string} book - Formatted book name (e.g. 'matthew')
 * @param {number} chapter
 * @param {number|string} verse - Verse number or 'all'
 * @param {string} currentBook - Full display name of the book (e.g. 'Matthew')
 * @returns {Promise<Array>}
 */
export async function getCommentaries(db, book, chapter, verse, currentBook) {
  const sql = `SELECT c.*, fm.wiki_url
    FROM commentary c
    LEFT JOIN father_meta fm ON c.father_name = fm.name
    WHERE c.book = ?
      AND c.location_end >= ?
      AND c.location_start < ?
    ORDER BY c.ts ASC`;

  let start, end;
  if (verse && verse !== 'all') {
    start = (chapter * 1000000) + verse;
    end = (chapter * 1000000) + verse + 1;
  } else {
    start = chapter * 1000000;
    end = (chapter + 1) * 1000000;
  }

  const result = await db.query(sql, [book, start, end]);
  const rows = result.values || [];

  return rows.map(row => {
    const chapterStart = Math.floor(row.location_start / 1000000);
    const verseStart = row.location_start - (chapterStart * 1000000);
    const chapterEnd = Math.floor(row.location_end / 1000000);
    const verseEnd = row.location_end - (chapterEnd * 1000000);

    return {
      ...row,
      verse_string: normalize_verse(chapterStart, verseStart, chapterEnd, verseEnd),
      formatted_year: format_year(row.ts),
      wiki_url: row.wiki_url || null
    };
  });
}

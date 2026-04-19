const fs = require('fs');
const path = require('path');

const ROOT = __dirname;
const SRC = path.join(ROOT, 'src');
const WWW = path.join(ROOT, 'www');
const ASSETS_DB = path.join(ROOT, 'assets', 'databases');
const PARENT = path.resolve(ROOT, '..');

// Utility: recursively copy a directory
function copyDirSync(src, dest) {
  fs.mkdirSync(dest, { recursive: true });
  for (const entry of fs.readdirSync(src, { withFileTypes: true })) {
    const srcPath = path.join(src, entry.name);
    const destPath = path.join(dest, entry.name);
    if (entry.isDirectory()) {
      copyDirSync(srcPath, destPath);
    } else {
      fs.copyFileSync(srcPath, destPath);
    }
  }
}

// Utility: copy a file, creating parent dirs as needed
function copyFileSync(src, dest) {
  fs.mkdirSync(path.dirname(dest), { recursive: true });
  fs.copyFileSync(src, dest);
}

console.log('Building HistoricalChristian.Faith mobile app...\n');

// 1. Create www/ directory
fs.mkdirSync(WWW, { recursive: true });
console.log('  [1/7] Created www/');

// 2. Copy index.html from src/ to www/
copyFileSync(path.join(SRC, 'index.html'), path.join(WWW, 'index.html'));
console.log('  [2/7] Copied index.html');

// 3. Copy src/templates/ to www/templates/
copyDirSync(path.join(SRC, 'templates'), path.join(WWW, 'templates'));
console.log('  [3/7] Copied templates/');

// 4. Copy ../bible-view.css to www/css/bible-view.css
copyFileSync(path.join(PARENT, 'bible-view.css'), path.join(WWW, 'css', 'bible-view.css'));
console.log('  [4/7] Copied bible-view.css');

// 5. Copy ../favicon.png to www/favicon.png
copyFileSync(path.join(PARENT, 'favicon.png'), path.join(WWW, 'favicon.png'));
console.log('  [5/7] Copied favicon.png');

// 6. Copy all JS files from src/ to www/
const jsFiles = fs.readdirSync(SRC).filter(f => f.endsWith('.js'));
for (const file of jsFiles) {
  copyFileSync(path.join(SRC, file), path.join(WWW, file));
}
console.log(`  [6/7] Copied ${jsFiles.length} JS file(s)`);

// 7. Copy database files to assets/databases/ (for Capacitor)
fs.mkdirSync(ASSETS_DB, { recursive: true });
const dbFiles = [
  { src: 'kjv.sqlite', dest: 'kjv.db' },
  { src: 'data.sqlite', dest: 'data.db' },
];
let dbCopied = 0;
for (const { src: srcName, dest: destName } of dbFiles) {
  const src = path.join(PARENT, srcName);
  if (fs.existsSync(src)) {
    copyFileSync(src, path.join(ASSETS_DB, destName));
    dbCopied++;
  } else {
    console.warn(`  WARNING: ${srcName} not found at ${src}`);
  }
}
console.log(`  [7/7] Copied ${dbCopied}/${dbFiles.length} database file(s)`);

console.log('\nBuild complete!');

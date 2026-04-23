const path = require('path');
const Database = require('better-sqlite3');

// Read both legacy (2021) and current (2030) mbtiles metadata
const files = [
  { label: 'LEGACY 2021', path: path.join(__dirname, '..', 'public', 'Map', 'DaLat', 'MapFile', 'V2', 'TP DA LAT LD 2021.mbtiles') },
  { label: 'CURRENT V3 2030', path: path.join(__dirname, '..', 'public', 'Map', 'DaLat', 'MapFile', 'V3', 'QH-DaLat-2030.mbtiles') },
];

for (const f of files) {
  console.log(`\n${'='.repeat(60)}`);
  console.log(`FILE: ${f.label}`);
  console.log(`PATH: ${f.path}`);
  console.log(`${'='.repeat(60)}`);
  
  try {
    const fs = require('fs');
    const stat = fs.statSync(f.path);
    console.log(`SIZE: ${(stat.size / 1024 / 1024).toFixed(1)} MB`);
    
    const db = new Database(f.path, { readonly: true });
    
    // Metadata
    console.log('\n--- METADATA ---');
    const meta = db.prepare('SELECT name, value FROM metadata').all();
    for (const row of meta) {
      if (row.name === 'json') {
        console.log(`  ${row.name}: ${row.value.substring(0, 500)}`);
      } else {
        console.log(`  ${row.name}: ${row.value}`);
      }
    }
    
    // Table structure
    console.log('\n--- TABLE STRUCTURE ---');
    const tables = db.prepare("SELECT sql FROM sqlite_master WHERE type='table'").all();
    for (const t of tables) console.log(`  ${t.sql}`);
    
    // Tile counts per zoom
    console.log('\n--- TILE COUNT PER ZOOM ---');
    const tc = db.prepare('SELECT zoom_level, count(*) as cnt FROM tiles GROUP BY zoom_level ORDER BY zoom_level').all();
    for (const row of tc) console.log(`  Zoom ${row.zoom_level}: ${row.cnt} tiles`);
    
    // Sample tile data for first tile to check format (gzip/png/pbf)
    console.log('\n--- SAMPLE TILE FORMAT ---');
    const sampleTile = db.prepare('SELECT zoom_level, tile_column, tile_row, length(tile_data) as size, tile_data FROM tiles LIMIT 1').get();
    if (sampleTile) {
      const buf = Buffer.from(sampleTile.tile_data);
      const isGzip = buf.length >= 2 && buf[0] === 0x1f && buf[1] === 0x8b;
      const isPng = buf.length >= 4 && buf[0] === 0x89 && buf[1] === 0x50 && buf[2] === 0x4e && buf[3] === 0x47;
      console.log(`  Tile z=${sampleTile.zoom_level} x=${sampleTile.tile_column} y=${sampleTile.tile_row}`);
      console.log(`  Size: ${sampleTile.size} bytes`);
      console.log(`  First bytes: 0x${buf.slice(0, 8).toString('hex')}`);
      console.log(`  Is Gzip: ${isGzip}`);
      console.log(`  Is PNG: ${isPng}`);
      console.log(`  Format: ${isPng ? 'PNG (raster)' : isGzip ? 'Gzip-compressed (likely PBF)' : 'Raw PBF or unknown'}`);
    }
    
    db.close();
  } catch (err) {
    console.log(`  ERROR: ${err.message}`);
  }
}

const Database = require('better-sqlite3');
const db = new Database('C:/Users/XuanNguyen/Documents/GitHub/dalatbds_broker_backend/Temp/QH-DaLat-2030.mbtiles', {readonly: true});

const z = 15;
const x = 26254;
const y = 15289;

const maxTileCount = Math.pow(2, z);
const tmsY = maxTileCount - 1 - y;

const stmt = db.prepare('SELECT zoom_level, tile_column, tile_row, LENGTH(tile_data) as size FROM tiles WHERE zoom_level = ? AND tile_column = ? AND tile_row = ?');
const row = stmt.get(z, x, tmsY);

console.log('Tile XYZ:', z, x, y);
console.log('Tile TMS:', z, x, tmsY);
console.log('Result:', row);

// Check overall bounds of the DB
console.log('Metadata:', db.prepare("SELECT * FROM metadata WHERE name IN ('bounds', 'center')").all());

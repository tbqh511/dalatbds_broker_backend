const Database = require('better-sqlite3');
const db = new Database('C:/Users/XuanNguyen/Documents/GitHub/dalatbds_broker_backend/Temp/QH-DaLat-2030.mbtiles', {readonly: true});

const z = 15;
const tmsY = 17478;
const targetX = 26254;

const closest = db.prepare('SELECT zoom_level, tile_column, tile_row, LENGTH(tile_data) as size FROM tiles WHERE zoom_level = ? ORDER BY ABS(tile_column - ?) + ABS(tile_row - ?) ASC LIMIT 5').all(z, targetX, tmsY);
console.log(closest);

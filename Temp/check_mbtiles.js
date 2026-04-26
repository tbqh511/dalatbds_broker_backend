const fs = require('fs');

try {
    const sqlite3 = require('sqlite3').verbose();
    const file = 'public/Map/DaLat/MapFile/V2/TP DA LAT LD 2021.mbtiles';
    const db = new sqlite3.Database(file);
    
    db.get('SELECT value FROM metadata WHERE name = "format"', (err, row) => {
        if (err) console.error('Error reading metadata:', err);
        else console.log('Format from metadata:', row ? row.value : 'not found');
        
        db.get('SELECT hex(substr(tile_data, 1, 8)) as magic FROM tiles LIMIT 1', (err, row) => {
            if (err) console.error('Error reading tiles:', err);
            else {
                console.log('Magic bytes of first tile:', row ? row.magic : 'not found');
                if (row && row.magic.startsWith('89504E47')) {
                    console.log('Tile data is PNG.');
                } else if (row && row.magic.startsWith('1F8B')) {
                    console.log('Tile data is GZIP (likely PBF/MVT).');
                } else {
                    console.log('Tile data format unknown based on magic bytes.');
                }
            }
            db.close();
        });
    });
} catch(e) {
    console.error("sqlite3 module not found, trying better-sqlite3...");
    try {
        const Database = require('better-sqlite3');
        const db = new Database('public/Map/DaLat/MapFile/V2/TP DA LAT LD 2021.mbtiles', { readonly: true });
        const formatRow = db.prepare('SELECT value FROM metadata WHERE name = "format"').get();
        console.log('Format from metadata:', formatRow ? formatRow.value : 'not found');
        
        const tileRow = db.prepare('SELECT hex(substr(tile_data, 1, 8)) as magic FROM tiles LIMIT 1').get();
        console.log('Magic bytes of first tile:', tileRow ? tileRow.magic : 'not found');
        if (tileRow && tileRow.magic.startsWith('89504E47')) {
            console.log('Tile data is PNG.');
        } else if (tileRow && tileRow.magic.startsWith('1F8B')) {
            console.log('Tile data is GZIP (likely PBF/MVT).');
        } else {
            console.log('Tile data format unknown based on magic bytes.');
        }
    } catch (e2) {
        console.error("better-sqlite3 also not found.");
    }
}

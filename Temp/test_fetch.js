const https = require('https');
const fs = require('fs');

const url = 'https://dalatbds.com/map-tiles/dalat/15/26254/15289.pbf';

https.get(url, (res) => {
    console.log('Status Code:', res.statusCode);
    console.log('Headers:', res.headers);
    
    let data = [];
    res.on('data', chunk => data.push(chunk));
    res.on('end', () => {
        const buffer = Buffer.concat(data);
        console.log('Body Length:', buffer.length);
        if (buffer.length > 0) {
            console.log('First 10 bytes (hex):', buffer.slice(0, 10).toString('hex'));
            fs.writeFileSync('temp_tile.pbf', buffer);
            console.log('Saved to temp_tile.pbf');
        } else {
            console.log('Empty body');
        }
    });
}).on('error', err => {
    console.error('Error:', err.message);
});

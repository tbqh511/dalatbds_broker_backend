const Pbf = require('pbf');
const fs = require('fs');
// Create a fake PNG buffer
const pngMagic = Buffer.from([0x89, 0x50, 0x4e, 0x47, 0x0d, 0x0a, 0x1a, 0x0a, 0x00, 0x00, 0x00, 0x0d, 0x49, 0x48, 0x44, 0x52]);
try {
    const pbf = new Pbf(pngMagic);
    pbf.readFields((tag, result, pbf) => {
        console.log('Tag:', tag);
        pbf.skip(tag);
    }, {});
} catch (e) {
    console.log('Error caught:', e.message);
}

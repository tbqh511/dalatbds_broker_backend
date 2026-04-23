/**
 * Extract colors from Legacy 2021 PNG tiles — improved version.
 * Samples many tiles across all zoom levels and analyzes non-background colors.
 */
const path = require('path');
const Database = require('better-sqlite3');
const { createCanvas, loadImage } = require('canvas');

const LEGACY_PATH = path.join(__dirname, '..', 'public', 'Map', 'DaLat', 'MapFile', 'V2', 'TP DA LAT LD 2021.mbtiles');

async function main() {
  const db = new Database(LEGACY_PATH, { readonly: true });

  // Get tiles at all zoom levels, focusing on central area with most data
  const tiles = db.prepare(`
    SELECT zoom_level, tile_column, tile_row, tile_data, length(tile_data) as size
    FROM tiles 
    WHERE length(tile_data) > 500
    ORDER BY length(tile_data) DESC
    LIMIT 50
  `).all();

  console.log(`Sampling ${tiles.length} tiles (largest by data size)`);
  console.log('Tile sizes:');
  tiles.slice(0, 10).forEach(t => console.log(`  z${t.zoom_level} x${t.tile_column} y${t.tile_row}: ${t.size} bytes`));

  const colorCounts = {};
  let totalProcessed = 0;

  for (const tile of tiles) {
    try {
      const buf = Buffer.from(tile.tile_data);
      const img = await loadImage(buf);
      const canvas = createCanvas(img.width, img.height);
      const ctx = canvas.getContext('2d');
      ctx.drawImage(img, 0, 0);
      const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
      const data = imageData.data;

      let tileColors = 0;
      for (let i = 0; i < data.length; i += 4) {
        const r = data[i], g = data[i+1], b = data[i+2], a = data[i+3];
        
        // Skip transparent pixels
        if (a < 50) continue;
        
        // Skip pure white and near-white (background)
        if (r > 248 && g > 248 && b > 248) continue;
        
        // Skip very light gray (near-background)
        if (r > 230 && g > 230 && b > 230 && Math.abs(r-g) < 5 && Math.abs(g-b) < 5) continue;
        
        // Quantize to reduce noise (round to nearest 16 for broader grouping)
        const qr = Math.round(r / 16) * 16;
        const qg = Math.round(g / 16) * 16;
        const qb = Math.round(b / 16) * 16;
        
        const hex = `#${Math.min(255,qr).toString(16).padStart(2,'0')}${Math.min(255,qg).toString(16).padStart(2,'0')}${Math.min(255,qb).toString(16).padStart(2,'0')}`;
        colorCounts[hex] = (colorCounts[hex] || 0) + 1;
        tileColors++;
      }
      totalProcessed++;
      if (tileColors > 0) {
        process.stdout.write(`\r  Processed ${totalProcessed}/${tiles.length} tiles, ${Object.keys(colorCounts).length} unique colors found`);
      }
    } catch (e) {
      console.error(`\n  Error processing tile z${tile.zoom_level}: ${e.message}`);
    }
  }

  process.stdout.write('\n\n');

  // Sort by frequency
  const sorted = Object.entries(colorCounts)
    .sort((a, b) => b[1] - a[1])
    .slice(0, 60);

  const totalPixels = Object.values(colorCounts).reduce((sum, c) => sum + c, 0);

  console.log(`Total non-background pixels analyzed: ${totalPixels.toLocaleString()}`);
  console.log(`Unique colors (quantized): ${Object.keys(colorCounts).length}\n`);

  console.log('=== TOP 60 COLORS IN LEGACY 2021 TILES ===');
  console.log('(quantized to 16-step, excluding transparent/white)\n');
  console.log('Rank | HEX       | RGB              | Count      | %       | Likely Land Use');
  console.log('-----|-----------|------------------|------------|---------|----------------');
  
  sorted.forEach(([hex, count], i) => {
    const pct = (count / totalPixels * 100).toFixed(2);
    const r = parseInt(hex.slice(1,3), 16);
    const g = parseInt(hex.slice(3,5), 16);
    const b = parseInt(hex.slice(5,7), 16);
    const rgb = `(${r},${g},${b})`;
    const category = guessLandUse(r, g, b);
    console.log(`${String(i+1).padStart(4)} | ${hex} | ${rgb.padEnd(16)} | ${String(count).padStart(10)} | ${pct.padStart(6)}% | ${category}`);
  });

  // Group by land use category
  console.log('\n\n=== SUMMARY BY LAND USE CATEGORY ===\n');
  
  const categories = {};
  for (const [hex, count] of Object.entries(colorCounts)) {
    const r = parseInt(hex.slice(1,3), 16);
    const g = parseInt(hex.slice(3,5), 16);
    const b = parseInt(hex.slice(5,7), 16);
    const cat = guessLandUse(r, g, b);
    if (!categories[cat]) categories[cat] = { count: 0, colors: [] };
    categories[cat].count += count;
    categories[cat].colors.push({ hex, count });
  }

  const catSorted = Object.entries(categories).sort((a, b) => b[1].count - a[1].count);
  for (const [cat, data] of catSorted) {
    const pct = (data.count / totalPixels * 100).toFixed(1);
    const topColors = data.colors.sort((a,b) => b.count - a.count).slice(0, 5);
    console.log(`${cat} (${pct}%):`);
    topColors.forEach(c => console.log(`  ${c.hex} — ${c.count.toLocaleString()} px`));
    console.log();
  }

  db.close();
}

function guessLandUse(r, g, b) {
  const max = Math.max(r, g, b);
  const min = Math.min(r, g, b);
  const diff = max - min;
  
  // Near-gray (low saturation)
  if (diff < 30) {
    if (max < 80) return 'Đen/Xám đậm (Viền/Đường)';
    if (max < 160) return 'Xám (Hạ tầng/Viền)';
    return 'Xám nhạt (Nền phụ)';
  }
  
  // Red dominant
  if (r > g && r > b) {
    if (g > 150 && b < 100) return 'Vàng/Cam (Đất ở)';
    if (g > 100 && g < 180 && b < 100) return 'Cam (Đất ở hiện hữu)';
    if (g < 80 && b < 80) return 'Đỏ (Giao thông/Ranh giới)';
    if (b > 100 && g < 100) return 'Hồng/Tím (Đất hỗn hợp)';
    if (g > 80 && b > 80) return 'Hồng nhạt (Dịch vụ)';
    return 'Đỏ/Cam (Chưa rõ)';
  }
  
  // Green dominant  
  if (g > r && g > b) {
    if (r > 150 && b < 100) return 'Xanh lá vàng (Nông nghiệp)';
    if (r < 100 && b < 100) return 'Xanh lá đậm (Rừng/Cây xanh)';
    if (r > 100 && b < 150) return 'Xanh lá nhạt (Công viên/Cây xanh)';
    return 'Xanh lá (Chưa rõ)';
  }
  
  // Blue dominant
  if (b > r && b > g) {
    if (g > 150) return 'Cyan/Xanh ngọc (Mặt nước/Kỹ thuật)';
    if (r > 100) return 'Tím/Lavender (Tôn giáo/VH)';
    if (r < 80 && g < 120) return 'Xanh dương đậm (Mặt nước)';
    return 'Xanh dương (Hạ tầng)';
  }

  return 'Khác';
}

main().catch(err => { console.error(err); process.exit(1); });

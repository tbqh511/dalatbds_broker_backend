const fs = require('fs');
const path = require('path');
const readline = require('readline');
const proj4 = require('proj4');
const Database = require('better-sqlite3');
const { createCanvas } = require('canvas');

// === CONFIG ===
const INPUT_DXF = path.join(__dirname, 'QH-DaLat-2030.dxf');
const OUTPUT_MBTILES = path.join(__dirname, '..', 'public', 'Map', 'DaLat', 'MapFile', 'V3', 'QH-DaLat-2030.mbtiles');
const MIN_ZOOM = 13;
const MAX_ZOOM = 18;
const TILE_SIZE = 256; // DPI 96

// VN-2000 / TM-3 lon_0=108°
const VN2000_TM3 = '+proj=tmerc +lat_0=0 +lon_0=108 +k=0.9999 +x_0=500000 +y_0=0 +ellps=GRS80 +units=m +no_defs';
const WGS84 = 'EPSG:4326';
proj4.defs('VN2000', VN2000_TM3);

// ── QH Color palette (inherited from legacy 2021 + webapp-v2.js) ──
const QH_COLORS = {
  DGT:'#e03010', DCK:'#cc2200', ONT:'#d0b040', ODT:'#d09040',
  TMD:'#e09070', LUA:'#c0e050', HNK:'#c0e060', NKH:'#b0d050',
  CLN:'#90d070', CAN:'#80c060', '3LR':'#509040', DRA:'#407838',
  SON:'#70b0e0', MNC:'#80b8e0', SKC:'#80b0d0', SKN:'#80b0d0',
  SKS:'#70a8d0', SKX:'#70a8d0', DTL:'#78b0d8',
  DGD:'#a878b8', DYT:'#d080a8', DVH:'#a080b8', DTT:'#9078b8', DKV:'#8090d0',
  TSC:'#b8a078', CQP:'#989870', DSH:'#c8a880',
  DTS:'#b88860', TIN:'#b08868', TON:'#b08868', DDL:'#d0a068',
  NTD:'#888888', DBV:'#689850', DKG:'#c89858', DNL:'#d0a848',
  DKH:'#a0a0a0', DXH:'#d0a890', DHT:'#989888', DCH:'#d09068',
  DDT:'#c8a068', CSD:'#d8d8c0', PNK:'#b0b0a0', CDG:'#8898b0',
};

function qhExtractCode(name) {
  if (!name) return null;
  const n = name.toUpperCase();
  if (n.includes('GIAOTHONG') || n.includes('GIAO_THONG')) return 'DGT';
  if (n.includes('NHA_O') || n.includes('DAT O') || n.includes('DAT_O')) return 'ODT';
  if (n.includes('DU_LICH') || n.includes('DU LICH') || n.includes('KDL')) return 'TMD';
  const parts = name.split(/[_ ]+/);
  for (let i = parts.length - 1; i >= 0; i--) {
    const p = parts[i].toUpperCase();
    if (QH_COLORS[p]) return p;
  }
  return null;
}

function reprojectCoord(x, y) {
  try {
    const [lng, lat] = proj4('VN2000', WGS84, [x, y]);
    if (lng >= 100 && lng <= 115 && lat >= 8 && lat <= 16) return [lng, lat];
    return null;
  } catch { return null; }
}

// ── DXF Entity → GeoJSON Features ──
function entityToFeatures(entity) {
  const features = [];
  const props = { layer: entity.layer || 'default', type: entity.type };

  if (entity.type === 'LINE') {
    const c1 = reprojectCoord(entity.vertices[0].x, entity.vertices[0].y);
    const c2 = reprojectCoord(entity.vertices[1].x, entity.vertices[1].y);
    if (c1 && c2) features.push({ type: 'Feature', properties: props, geometry: { type: 'LineString', coordinates: [c1, c2] } });
  } else if (entity.type === 'LWPOLYLINE' || entity.type === 'POLYLINE') {
    const coords = [];
    for (const v of (entity.vertices || [])) { const c = reprojectCoord(v.x, v.y); if (c) coords.push(c); }
    if (coords.length >= 2) {
      const isClosed = entity.shape || entity.closed;
      if (isClosed && coords.length >= 3) {
        features.push({ type: 'Feature', properties: props, geometry: { type: 'Polygon', coordinates: [[...coords, coords[0]]] } });
      } else {
        features.push({ type: 'Feature', properties: props, geometry: { type: 'LineString', coordinates: coords } });
      }
    }
  } else if (entity.type === 'CIRCLE') {
    const center = reprojectCoord(entity.center.x, entity.center.y);
    if (center) {
      const coords = [];
      for (let i = 0; i <= 32; i++) {
        const angle = (2 * Math.PI * i) / 32;
        const c = reprojectCoord(entity.center.x + entity.radius * Math.cos(angle), entity.center.y + entity.radius * Math.sin(angle));
        if (c) coords.push(c);
      }
      if (coords.length >= 4) features.push({ type: 'Feature', properties: props, geometry: { type: 'Polygon', coordinates: [coords] } });
    }
  } else if (entity.type === 'ARC') {
    const center = reprojectCoord(entity.center.x, entity.center.y);
    if (center) {
      const sa = entity.startAngle || 0, ea = entity.endAngle || 2 * Math.PI;
      const coords = [];
      for (let i = 0; i <= 32; i++) {
        const angle = sa + ((ea - sa) * i) / 32;
        const c = reprojectCoord(entity.center.x + entity.radius * Math.cos(angle), entity.center.y + entity.radius * Math.sin(angle));
        if (c) coords.push(c);
      }
      if (coords.length >= 2) features.push({ type: 'Feature', properties: props, geometry: { type: 'LineString', coordinates: coords } });
    }
  } else if (entity.type === 'POINT') {
    const c = reprojectCoord(entity.position.x, entity.position.y);
    if (c) features.push({ type: 'Feature', properties: props, geometry: { type: 'Point', coordinates: c } });
  } else if (entity.type === 'ELLIPSE') {
    const center = reprojectCoord(entity.center.x, entity.center.y);
    if (center) features.push({ type: 'Feature', properties: props, geometry: { type: 'Point', coordinates: center } });
  } else if (entity.type === 'SPLINE') {
    const coords = [];
    for (const v of (entity.controlPoints || entity.fitPoints || [])) { const c = reprojectCoord(v.x, v.y); if (c) coords.push(c); }
    if (coords.length >= 2) features.push({ type: 'Feature', properties: props, geometry: { type: 'LineString', coordinates: coords } });
  } else if (entity.type === 'SOLID' || entity.type === '3DFACE') {
    const coords = [];
    for (const p of (entity.points || [])) { const c = reprojectCoord(p.x, p.y); if (c) coords.push(c); }
    if (coords.length >= 3) { coords.push(coords[0]); features.push({ type: 'Feature', properties: props, geometry: { type: 'Polygon', coordinates: [coords] } }); }
  } else if (entity.type === 'HATCH') {
    if (entity.boundary && entity.boundary.polylines) {
      for (const pl of entity.boundary.polylines) {
        const coords = [];
        for (const v of (pl.vertices || pl)) { const c = reprojectCoord(v.x, v.y); if (c) coords.push(c); }
        if (coords.length >= 3) { coords.push(coords[0]); features.push({ type: 'Feature', properties: props, geometry: { type: 'Polygon', coordinates: [coords] } }); }
      }
    }
  }
  // Skip TEXT, MTEXT, INSERT for raster rendering
  return features;
}

// ── DXF Group Code Handler ──
function applyEntityGroupCode(entity, code, value) {
  if (code === 8) { entity.layer = value; return; }
  const f = parseFloat(value);
  const n = parseInt(value, 10);
  switch (entity.type) {
    case 'LINE':
      if (entity.vertices.length < 2) entity.vertices = [{x:0,y:0},{x:0,y:0}];
      if (code === 10) entity.vertices[0].x = f; else if (code === 20) entity.vertices[0].y = f;
      else if (code === 11) entity.vertices[1].x = f; else if (code === 21) entity.vertices[1].y = f;
      break;
    case 'LWPOLYLINE':
      if (code === 70) { entity.closed = !!(n & 1); entity.shape = !!(n & 1); }
      else if (code === 10) { entity._pv = { x: f, y: 0 }; }
      else if (code === 20) { if (entity._pv) { entity._pv.y = f; entity.vertices.push(entity._pv); entity._pv = null; } }
      break;
    case 'POLYLINE':
      if (code === 70) { entity.closed = !!(n & 1); entity.shape = !!(n & 1); }
      break;
    case 'CIRCLE':
      if (code === 10) { entity.center = entity.center || {x:0,y:0}; entity.center.x = f; }
      else if (code === 20) { entity.center = entity.center || {x:0,y:0}; entity.center.y = f; }
      else if (code === 40) { entity.radius = f; }
      break;
    case 'ARC':
      if (code === 10) { entity.center = entity.center || {x:0,y:0}; entity.center.x = f; }
      else if (code === 20) { entity.center = entity.center || {x:0,y:0}; entity.center.y = f; }
      else if (code === 40) { entity.radius = f; }
      else if (code === 50) { entity.startAngle = f * Math.PI / 180; }
      else if (code === 51) { entity.endAngle = f * Math.PI / 180; }
      break;
    case 'POINT':
      if (code === 10) { entity.position = entity.position || {x:0,y:0}; entity.position.x = f; }
      else if (code === 20) { entity.position = entity.position || {x:0,y:0}; entity.position.y = f; }
      break;
  }
}

// ── Streaming DXF Parser ──
async function parseDxfStreaming(filepath) {
  const features = [];
  const typeCounts = {};
  let inEntities = false, awaitingSectionName = false, currentEntity = null;
  let inPolyline = false, currentVertex = null, isCodeLine = true, currentCode = 0, entityCount = 0;

  function emitEntity(ent) {
    if (!ent) return;
    typeCounts[ent.type] = (typeCounts[ent.type] || 0) + 1;
    entityCount++;
    if (entityCount % 100000 === 0) process.stdout.write(`\r  Streamed ${entityCount} entities, ${features.length} features...`);
    try { features.push(...entityToFeatures(ent)); } catch {}
  }

  function handlePair(code, value) {
    if (code === 0) {
      if (value === 'SECTION') { awaitingSectionName = true; return; }
      if (value === 'ENDSEC') {
        if (inEntities) {
          if (inPolyline) { if (currentVertex) currentEntity.vertices.push(currentVertex); emitEntity(currentEntity); currentEntity = null; inPolyline = false; currentVertex = null; }
          else { emitEntity(currentEntity); currentEntity = null; }
          inEntities = false;
        }
        return;
      }
      if (!inEntities) return;
      if (inPolyline) {
        if (value === 'VERTEX') { if (currentVertex) currentEntity.vertices.push(currentVertex); currentVertex = { x: 0, y: 0 }; return; }
        if (value === 'SEQEND') { if (currentVertex) currentEntity.vertices.push(currentVertex); emitEntity(currentEntity); currentEntity = null; inPolyline = false; currentVertex = null; return; }
        if (currentVertex) currentEntity.vertices.push(currentVertex);
        emitEntity(currentEntity); currentEntity = null; inPolyline = false; currentVertex = null;
      } else {
        emitEntity(currentEntity); currentEntity = null;
      }
      if (value === 'POLYLINE') { currentEntity = { type: 'POLYLINE', layer: '0', vertices: [], closed: false }; inPolyline = true; currentVertex = null; }
      else { currentEntity = { type: value, layer: '0', vertices: [] }; }
      return;
    }
    if (code === 2 && awaitingSectionName) { awaitingSectionName = false; if (value === 'ENTITIES') inEntities = true; return; }
    if (!inEntities) return;
    if (inPolyline && currentVertex !== null) {
      if (code === 10) currentVertex.x = parseFloat(value);
      else if (code === 20) currentVertex.y = parseFloat(value);
    } else if (currentEntity) {
      applyEntityGroupCode(currentEntity, code, value);
    }
  }

  return new Promise((resolve, reject) => {
    const rl = readline.createInterface({ input: fs.createReadStream(filepath, { encoding: 'utf8', highWaterMark: 4 * 1024 * 1024 }), crlfDelay: Infinity });
    rl.on('line', (line) => { const t = line.trim(); if (isCodeLine) { currentCode = parseInt(t, 10); } else { handlePair(currentCode, t); } isCodeLine = !isCodeLine; });
    rl.on('close', () => { process.stdout.write('\n'); console.log(`  Entity types: ${JSON.stringify(typeCounts)}`); console.log(`  Total features: ${features.length}`); resolve(features); });
    rl.on('error', reject);
  });
}

// ── Tile math ──
function lngToTileX(lng, z) { return Math.floor((lng + 180) / 360 * Math.pow(2, z)); }
function latToTileY(lat, z) { const r = lat * Math.PI / 180; return Math.floor((1 - Math.log(Math.tan(r) + 1 / Math.cos(r)) / Math.PI) / 2 * Math.pow(2, z)); }

function tileBounds(x, y, z) {
  const n = Math.pow(2, z);
  const west = x / n * 360 - 180;
  const east = (x + 1) / n * 360 - 180;
  const northRad = Math.atan(Math.sinh(Math.PI * (1 - 2 * y / n)));
  const southRad = Math.atan(Math.sinh(Math.PI * (1 - 2 * (y + 1) / n)));
  return { west, east, north: northRad * 180 / Math.PI, south: southRad * 180 / Math.PI };
}

function bboxOverlap(a, b) {
  return !(a.east < b.west || a.west > b.east || a.north < b.south || a.south > b.north);
}

// ── Feature bbox cache ──
function featureBBox(f) {
  if (f._bbox) return f._bbox;
  let minLng = 180, maxLng = -180, minLat = 90, maxLat = -90;
  const coords = getAllCoords(f.geometry);
  for (const [lng, lat] of coords) {
    if (lng < minLng) minLng = lng; if (lng > maxLng) maxLng = lng;
    if (lat < minLat) minLat = lat; if (lat > maxLat) maxLat = lat;
  }
  f._bbox = { west: minLng, east: maxLng, south: minLat, north: maxLat };
  return f._bbox;
}

function getAllCoords(g) {
  if (g.type === 'Point') return [g.coordinates];
  if (g.type === 'LineString') return g.coordinates;
  if (g.type === 'Polygon') { const r = []; for (const ring of g.coordinates) r.push(...ring); return r; }
  if (g.type === 'MultiPolygon') { const r = []; for (const poly of g.coordinates) for (const ring of poly) r.push(...ring); return r; }
  return [];
}

// ── Render a single tile ──
function renderTile(features, tBounds, zoom) {
  const canvas = createCanvas(TILE_SIZE, TILE_SIZE);
  const ctx = canvas.getContext('2d');

  // Transparent background
  ctx.clearRect(0, 0, TILE_SIZE, TILE_SIZE);

  const dLng = tBounds.east - tBounds.west;
  const dLat = tBounds.north - tBounds.south;

  function toPixel(lng, lat) {
    const px = ((lng - tBounds.west) / dLng) * TILE_SIZE;
    const py = ((tBounds.north - lat) / dLat) * TILE_SIZE;
    return [px, py];
  }

  const lineWidth = zoom >= 17 ? 1.8 : zoom >= 15 ? 1.2 : 0.8;

  for (const f of features) {
    const code = qhExtractCode(f.properties.layer);
    const color = (code && QH_COLORS[code]) ? QH_COLORS[code] : '#888888';
    const fillOpacity = code ? 0.4 : 0.2;
    const g = f.geometry;

    ctx.strokeStyle = color;
    ctx.lineWidth = lineWidth;
    ctx.globalAlpha = 0.85;

    if (g.type === 'Point') {
      const [px, py] = toPixel(g.coordinates[0], g.coordinates[1]);
      ctx.fillStyle = color;
      ctx.globalAlpha = fillOpacity;
      ctx.beginPath();
      ctx.arc(px, py, 2, 0, 2 * Math.PI);
      ctx.fill();
    } else if (g.type === 'LineString') {
      ctx.beginPath();
      for (let i = 0; i < g.coordinates.length; i++) {
        const [px, py] = toPixel(g.coordinates[i][0], g.coordinates[i][1]);
        if (i === 0) ctx.moveTo(px, py); else ctx.lineTo(px, py);
      }
      ctx.stroke();
    } else if (g.type === 'Polygon') {
      for (const ring of g.coordinates) {
        ctx.beginPath();
        for (let i = 0; i < ring.length; i++) {
          const [px, py] = toPixel(ring[i][0], ring[i][1]);
          if (i === 0) ctx.moveTo(px, py); else ctx.lineTo(px, py);
        }
        ctx.closePath();
        // Fill
        ctx.fillStyle = color;
        ctx.globalAlpha = fillOpacity;
        ctx.fill();
        // Stroke
        ctx.globalAlpha = 0.85;
        ctx.stroke();
      }
    }
  }

  ctx.globalAlpha = 1.0;
  return canvas.toBuffer('image/png');
}

// ── MBTiles DB ──
function createMBTilesDB(filepath) {
  if (fs.existsSync(filepath)) fs.unlinkSync(filepath);
  // Also clean up WAL/SHM files
  if (fs.existsSync(filepath + '-wal')) fs.unlinkSync(filepath + '-wal');
  if (fs.existsSync(filepath + '-shm')) fs.unlinkSync(filepath + '-shm');
  const db = new Database(filepath);
  db.pragma('journal_mode = DELETE'); // No WAL for portable MBTiles
  db.exec(`
    CREATE TABLE metadata (name TEXT, value TEXT);
    CREATE TABLE tiles (zoom_level INTEGER, tile_column INTEGER, tile_row INTEGER, tile_data BLOB);
    CREATE UNIQUE INDEX tile_index ON tiles (zoom_level, tile_column, tile_row);
  `);
  return db;
}

// ── Main ──
async function main() {
  console.log('=== DXF → PNG MBTiles Converter ===');
  console.log(`Input:  ${INPUT_DXF}`);
  console.log(`Output: ${OUTPUT_MBTILES}`);
  console.log(`Zoom:   ${MIN_ZOOM}–${MAX_ZOOM}  |  Tile: ${TILE_SIZE}px (DPI 96)`);

  // Step 1: Parse DXF
  const fileSize = fs.statSync(INPUT_DXF).size;
  console.log(`\n[1/3] Streaming DXF file (${(fileSize / 1024 / 1024).toFixed(1)} MB)...`);
  const features = await parseDxfStreaming(INPUT_DXF);
  if (features.length === 0) { console.error('  No features! Aborting.'); process.exit(1); }

  // Pre-compute bboxes
  console.log('  Pre-computing feature bboxes...');
  for (const f of features) featureBBox(f);

  // Global bounds
  let gW = 180, gE = -180, gS = 90, gN = -90;
  for (const f of features) {
    const b = f._bbox;
    if (b.west < gW) gW = b.west; if (b.east > gE) gE = b.east;
    if (b.south < gS) gS = b.south; if (b.north > gN) gN = b.north;
  }
  console.log(`  Bounds: ${gW.toFixed(6)},${gS.toFixed(6)},${gE.toFixed(6)},${gN.toFixed(6)}`);

  // Step 2: Create MBTiles + render tiles
  console.log('\n[2/3] Rendering PNG tiles...');
  const db = createMBTilesDB(OUTPUT_MBTILES);

  // Metadata
  const center = [(gW + gE) / 2, (gS + gN) / 2];
  const meta = {
    name: 'QH-DaLat-2030',
    format: 'png',
    type: 'overlay',
    description: 'Quy hoach Da Lat 2030 - PNG raster tiles from DXF',
    minzoom: String(MIN_ZOOM),
    maxzoom: String(MAX_ZOOM),
    bounds: `${gW},${gS},${gE},${gN}`,
    center: `${center[0]},${center[1]},${MIN_ZOOM}`,
  };
  const insertMeta = db.prepare('INSERT INTO metadata (name, value) VALUES (?, ?)');
  for (const [k, v] of Object.entries(meta)) insertMeta.run(k, v);

  const insertTile = db.prepare('INSERT OR REPLACE INTO tiles (zoom_level, tile_column, tile_row, tile_data) VALUES (?, ?, ?, ?)');
  const insertMany = db.transaction((tiles) => { for (const t of tiles) insertTile.run(t.z, t.x, t.y, t.data); });

  let totalTiles = 0;
  const batch = [];

  for (let z = MIN_ZOOM; z <= MAX_ZOOM; z++) {
    const x0 = lngToTileX(gW, z);
    const x1 = lngToTileX(gE, z);
    const y0 = latToTileY(gN, z); // north → smaller y
    const y1 = latToTileY(gS, z);
    const totalForZoom = (x1 - x0 + 1) * (y1 - y0 + 1);
    let zoomTiles = 0, zoomBytes = 0, checked = 0;

    process.stdout.write(`  Zoom ${z}: ${totalForZoom} candidate tiles...`);

    for (let x = x0; x <= x1; x++) {
      for (let y = y0; y <= y1; y++) {
        checked++;
        if (checked % 500 === 0) process.stdout.write(`\r  Zoom ${z}: ${checked}/${totalForZoom} checked, ${zoomTiles} rendered...`);

        const tB = tileBounds(x, y, z);
        // Filter features that overlap this tile
        const tileFeatures = features.filter(f => bboxOverlap(f._bbox, tB));
        if (tileFeatures.length === 0) continue;

        const png = renderTile(tileFeatures, tB, z);
        // TMS Y flip
        const tmsY = Math.pow(2, z) - 1 - y;
        batch.push({ z, x, y: tmsY, data: png });
        zoomTiles++;
        zoomBytes += png.length;
        if (batch.length >= 200) { insertMany(batch); batch.length = 0; }
      }
    }
    if (batch.length > 0) { insertMany(batch); batch.length = 0; }
    totalTiles += zoomTiles;
    console.log(`\r  Zoom ${z}: ${zoomTiles} tiles (${(zoomBytes / 1024 / 1024).toFixed(1)} MB)                    `);
  }

  db.close();
  const outputSize = fs.statSync(OUTPUT_MBTILES).size;
  console.log(`\n=== DONE ===`);
  console.log(`Total tiles: ${totalTiles}`);
  console.log(`Output: ${OUTPUT_MBTILES}`);
  console.log(`Output size: ${(outputSize / 1024 / 1024).toFixed(1)} MB`);
}

main().catch(err => { console.error('Fatal error:', err); process.exit(1); });

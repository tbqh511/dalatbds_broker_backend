const fs = require('fs');
const path = require('path');
const readline = require('readline');
const proj4 = require('proj4');
const vtpbf = require('vt-pbf');
const Database = require('better-sqlite3');

// === CONFIG ===
const INPUT_DXF = path.join(__dirname, 'QH-DaLat-2030.dxf');
const OUTPUT_MBTILES = path.join(__dirname, 'QH-DaLat-2030.mbtiles');
const MIN_ZOOM = 13;
const MAX_ZOOM = 19; // Reduced from 20 to prevent Out of Memory error
const DPI = 150;

// VN-2000 / TM-3 lon_0=108° (hệ tọa độ chính xác của file DXF Đà Lạt)
// ⚠ KHÔNG dùng UTM zone 48N (lon_0=105°) — sẽ bị dịch ~3° về phía Tây!
const VN2000_TM3 = '+proj=tmerc +lat_0=0 +lon_0=108 +k=0.9999 +x_0=500000 +y_0=0 +ellps=GRS80 +units=m +no_defs';
const WGS84 = 'EPSG:4326';

proj4.defs('VN2000', VN2000_TM3);

function reprojectCoord(x, y) {
  try {
    const [lng, lat] = proj4('VN2000', WGS84, [x, y]);
    if (lng >= 100 && lng <= 115 && lat >= 8 && lat <= 16) return [lng, lat];
    return null;
  } catch { return null; }
}

function entityToFeatures(entity) {
  const features = [];
  const props = { layer: entity.layer || 'default', type: entity.type };

  if (entity.type === 'LINE') {
    const c1 = reprojectCoord(entity.vertices[0].x, entity.vertices[0].y);
    const c2 = reprojectCoord(entity.vertices[1].x, entity.vertices[1].y);
    if (c1 && c2) {
      features.push({ type: 'Feature', properties: props, geometry: { type: 'LineString', coordinates: [c1, c2] } });
    }
  } else if (entity.type === 'LWPOLYLINE' || entity.type === 'POLYLINE') {
    const coords = [];
    for (const v of (entity.vertices || [])) {
      const c = reprojectCoord(v.x, v.y);
      if (c) coords.push(c);
    }
    if (coords.length >= 2) {
      const isClosed = entity.shape || entity.closed;
      if (isClosed && coords.length >= 3) {
        const ring = [...coords, coords[0]];
        features.push({ type: 'Feature', properties: props, geometry: { type: 'Polygon', coordinates: [ring] } });
      } else {
        features.push({ type: 'Feature', properties: props, geometry: { type: 'LineString', coordinates: coords } });
      }
    }
  } else if (entity.type === 'CIRCLE') {
    const center = reprojectCoord(entity.center.x, entity.center.y);
    if (center) {
      const segments = 32;
      const coords = [];
      for (let i = 0; i <= segments; i++) {
        const angle = (2 * Math.PI * i) / segments;
        const dx = entity.radius * Math.cos(angle);
        const dy = entity.radius * Math.sin(angle);
        const c = reprojectCoord(entity.center.x + dx, entity.center.y + dy);
        if (c) coords.push(c);
      }
      if (coords.length >= 4) {
        features.push({ type: 'Feature', properties: props, geometry: { type: 'Polygon', coordinates: [coords] } });
      }
    }
  } else if (entity.type === 'ARC') {
    const center = reprojectCoord(entity.center.x, entity.center.y);
    if (center) {
      const startAngle = (entity.startAngle || 0);
      const endAngle = (entity.endAngle || 2 * Math.PI);
      const segments = 32;
      const coords = [];
      for (let i = 0; i <= segments; i++) {
        const angle = startAngle + ((endAngle - startAngle) * i) / segments;
        const dx = entity.radius * Math.cos(angle);
        const dy = entity.radius * Math.sin(angle);
        const c = reprojectCoord(entity.center.x + dx, entity.center.y + dy);
        if (c) coords.push(c);
      }
      if (coords.length >= 2) {
        features.push({ type: 'Feature', properties: props, geometry: { type: 'LineString', coordinates: coords } });
      }
    }
  } else if (entity.type === 'POINT') {
    const c = reprojectCoord(entity.position.x, entity.position.y);
    if (c) {
      features.push({ type: 'Feature', properties: props, geometry: { type: 'Point', coordinates: c } });
    }
  } else if (entity.type === 'ELLIPSE') {
    const center = reprojectCoord(entity.center.x, entity.center.y);
    if (center) {
      features.push({ type: 'Feature', properties: props, geometry: { type: 'Point', coordinates: center } });
    }
  } else if (entity.type === 'SPLINE') {
    const coords = [];
    const pts = entity.controlPoints || entity.fitPoints || [];
    for (const v of pts) {
      const c = reprojectCoord(v.x, v.y);
      if (c) coords.push(c);
    }
    if (coords.length >= 2) {
      features.push({ type: 'Feature', properties: props, geometry: { type: 'LineString', coordinates: coords } });
    }
  } else if (entity.type === 'TEXT' || entity.type === 'MTEXT') {
    const pos = entity.position || entity.startPoint;
    if (pos) {
      const c = reprojectCoord(pos.x, pos.y);
      if (c) {
        props.text = entity.text || '';
        features.push({ type: 'Feature', properties: props, geometry: { type: 'Point', coordinates: c } });
      }
    }
  } else if (entity.type === 'SOLID' || entity.type === '3DFACE') {
    const coords = [];
    const points = entity.points || [];
    for (const p of points) {
      const c = reprojectCoord(p.x, p.y);
      if (c) coords.push(c);
    }
    if (coords.length >= 3) {
      coords.push(coords[0]);
      features.push({ type: 'Feature', properties: props, geometry: { type: 'Polygon', coordinates: [coords] } });
    }
  } else if (entity.type === 'INSERT') {
    if (entity.position) {
      const c = reprojectCoord(entity.position.x, entity.position.y);
      if (c) {
        props.blockName = entity.name || '';
        features.push({ type: 'Feature', properties: props, geometry: { type: 'Point', coordinates: c } });
      }
    }
  } else if (entity.type === 'HATCH') {
    if (entity.boundary && entity.boundary.polylines) {
      for (const pl of entity.boundary.polylines) {
        const coords = [];
        for (const v of (pl.vertices || pl)) {
          const c = reprojectCoord(v.x, v.y);
          if (c) coords.push(c);
        }
        if (coords.length >= 3) {
          coords.push(coords[0]);
          features.push({ type: 'Feature', properties: props, geometry: { type: 'Polygon', coordinates: [coords] } });
        }
      }
    }
  }

  return features;
}

function createMBTilesDB(filepath) {
  if (fs.existsSync(filepath)) fs.unlinkSync(filepath);
  const db = new Database(filepath);
  db.pragma('journal_mode = WAL');
  db.exec(`
    CREATE TABLE metadata (name TEXT, value TEXT);
    CREATE TABLE tiles (zoom_level INTEGER, tile_column INTEGER, tile_row INTEGER, tile_data BLOB);
    CREATE UNIQUE INDEX tile_index ON tiles (zoom_level, tile_column, tile_row);
  `);
  return db;
}

function calculateBounds(geojson) {
  let minLng = 180, minLat = 90, maxLng = -180, maxLat = -90;
  for (const f of geojson.features) {
    const coords = getAllCoords(f.geometry);
    for (const [lng, lat] of coords) {
      if (lng < minLng) minLng = lng;
      if (lng > maxLng) maxLng = lng;
      if (lat < minLat) minLat = lat;
      if (lat > maxLat) maxLat = lat;
    }
  }
  return { minLng, minLat, maxLng, maxLat };
}

function lngLatToTileXY(lng, lat, z) {
  const n = Math.pow(2, z);
  const x = Math.floor((lng + 180) / 360 * n);
  const latRad = lat * Math.PI / 180;
  const y = Math.floor((1 - Math.log(Math.tan(latRad) + 1 / Math.cos(latRad)) / Math.PI) / 2 * n);
  return { x: Math.max(0, Math.min(n - 1, x)), y: Math.max(0, Math.min(n - 1, y)) };
}

function setMetadata(db, bounds) {
  const { minLng, minLat, maxLng, maxLat } = bounds;
  const center = [(minLng + maxLng) / 2, (minLat + maxLat) / 2];
  const meta = {
    name: 'QH-DaLat-2030',
    format: 'pbf',
    type: 'overlay',
    description: 'Quy hoach Da Lat 2030 - converted from DXF',
    minzoom: String(MIN_ZOOM),
    maxzoom: String(MAX_ZOOM),
    bounds: `${minLng},${minLat},${maxLng},${maxLat}`,
    center: `${center[0]},${center[1]},${MIN_ZOOM}`,
    json: JSON.stringify({ vector_layers: [{ id: 'dxf_layer', fields: { layer: 'String', type: 'String', text: 'String' }, minzoom: MIN_ZOOM, maxzoom: MAX_ZOOM }] })
  };
  const insert = db.prepare('INSERT INTO metadata (name, value) VALUES (?, ?)');
  for (const [k, v] of Object.entries(meta)) insert.run(k, v);
  console.log(`  Bounds: ${meta.bounds}`);
  console.log(`  Center: ${meta.center}`);
}

function getAllCoords(geometry) {
  const coords = [];
  if (geometry.type === 'Point') { coords.push(geometry.coordinates); }
  else if (geometry.type === 'LineString') { coords.push(...geometry.coordinates); }
  else if (geometry.type === 'Polygon') { for (const ring of geometry.coordinates) coords.push(...ring); }
  else if (geometry.type === 'MultiPolygon') { for (const poly of geometry.coordinates) for (const ring of poly) coords.push(...ring); }
  return coords;
}

function applyEntityGroupCode(entity, code, value) {
  if (code === 8) { entity.layer = value; return; }
  const f = parseFloat(value);
  const n = parseInt(value, 10);
  switch (entity.type) {
    case 'LINE':
      if (entity.vertices.length < 2) entity.vertices = [{x:0,y:0},{x:0,y:0}];
      if (code === 10) entity.vertices[0].x = f;
      else if (code === 20) entity.vertices[0].y = f;
      else if (code === 11) entity.vertices[1].x = f;
      else if (code === 21) entity.vertices[1].y = f;
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
    case 'TEXT':
      if (code === 10) { entity.startPoint = entity.startPoint || {x:0,y:0}; entity.startPoint.x = f; }
      else if (code === 20) { entity.startPoint = entity.startPoint || {x:0,y:0}; entity.startPoint.y = f; }
      else if (code === 1) { entity.text = value; }
      break;
    case 'MTEXT':
      if (code === 10) { entity.position = entity.position || {x:0,y:0}; entity.position.x = f; }
      else if (code === 20) { entity.position = entity.position || {x:0,y:0}; entity.position.y = f; }
      else if (code === 1) { entity.text = value; }
      break;
    case 'INSERT':
      if (code === 10) { entity.position = entity.position || {x:0,y:0}; entity.position.x = f; }
      else if (code === 20) { entity.position = entity.position || {x:0,y:0}; entity.position.y = f; }
      else if (code === 2) { entity.name = value; }
      break;
  }
}

async function parseDxfStreaming(filepath) {
  const features = [];
  const typeCounts = {};
  let inEntities = false;
  let awaitingSectionName = false;
  let currentEntity = null;
  let inPolyline = false;
  let currentVertex = null;
  let isCodeLine = true;
  let currentCode = 0;
  let entityCount = 0;

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
          if (inPolyline) { if (currentVertex) { currentEntity.vertices.push(currentVertex); } emitEntity(currentEntity); currentEntity = null; inPolyline = false; currentVertex = null; }
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
    const rl = readline.createInterface({
      input: fs.createReadStream(filepath, { encoding: 'utf8', highWaterMark: 4 * 1024 * 1024 }),
      crlfDelay: Infinity,
    });
    rl.on('line', (line) => {
      const t = line.trim();
      if (isCodeLine) { currentCode = parseInt(t, 10); }
      else { handlePair(currentCode, t); }
      isCodeLine = !isCodeLine;
    });
    rl.on('close', () => {
      process.stdout.write('\n');
      console.log(`  Entity types: ${JSON.stringify(typeCounts)}`);
      console.log(`  Total features: ${features.length}`);
      resolve(features);
    });
    rl.on('error', reject);
  });
}

async function main() {
  console.log('=== DXF to MBTiles Converter ===');
  console.log(`Input:  ${INPUT_DXF}`);
  console.log(`Output: ${OUTPUT_MBTILES}`);

  // Step 1+2: Stream-parse DXF directly to GeoJSON features
  const fileSize = fs.statSync(INPUT_DXF).size;
  console.log(`\n[1/3] Streaming DXF file (${(fileSize / 1024 / 1024).toFixed(1)} MB)...`);
  const features = await parseDxfStreaming(INPUT_DXF);

  if (features.length === 0) {
    console.error('  No features generated! Check coordinate system.');
    process.exit(1);
  }

  const geojson = { type: 'FeatureCollection', features };

  // Step 2: Create vector tiles and MBTiles
  console.log('\n[2/3] Generating vector tiles and writing MBTiles...');
  const bounds = calculateBounds(geojson);
  const db = createMBTilesDB(OUTPUT_MBTILES);
  setMetadata(db, bounds);

  const { default: geojsonvt } = await import('geojson-vt');
  const insertTile = db.prepare('INSERT OR REPLACE INTO tiles (zoom_level, tile_column, tile_row, tile_data) VALUES (?, ?, ?, ?)');
  const insertMany = db.transaction((tiles) => {
    for (const t of tiles) insertTile.run(t.z, t.x, t.y, t.data);
  });

  let totalTiles = 0;
  const batch = [];
  const tolerance = Math.round(3 * 96 / DPI);

  // Process one zoom level at a time with a fresh geojsonvt per pass.
  // This keeps peak memory bounded: old index is GC-eligible before new one is built.
  for (let z = MIN_ZOOM; z <= MAX_ZOOM; z++) {
    process.stdout.write(`  Zoom ${z}: building index...`);
    const tileIndex = geojsonvt(geojson, {
      maxZoom: z,
      indexMaxZoom: z,
      indexMaxPoints: 200,
      tolerance,
      buffer: 64,
      extent: 4096,
    });

    const maxTileCount = Math.pow(2, z);
    const topLeft = lngLatToTileXY(bounds.minLng, bounds.maxLat, z);
    const bottomRight = lngLatToTileXY(bounds.maxLng, bounds.minLat, z);
    let zoomTiles = 0;
    for (let x = topLeft.x; x <= bottomRight.x; x++) {
      for (let y = topLeft.y; y <= bottomRight.y; y++) {
        const tile = tileIndex.getTile(z, x, y);
        if (tile && tile.features && tile.features.length > 0) {
          const pbf = vtpbf.fromGeojsonVt({ dxf_layer: tile }, { version: 2 });
          const tmsY = maxTileCount - 1 - y;
          batch.push({ z, x, y: tmsY, data: Buffer.from(pbf) });
          zoomTiles++;
          if (batch.length >= 500) { insertMany(batch); batch.length = 0; }
        }
      }
    }
    totalTiles += zoomTiles;
    console.log(` ${zoomTiles} tiles`);
    // tileIndex goes out of scope here → GC eligible. Force GC if available (--expose-gc).
    if (typeof global.gc === 'function') global.gc();
  }
  if (batch.length > 0) insertMany(batch);

  db.close();
  const outputSize = fs.statSync(OUTPUT_MBTILES).size;
  console.log(`\n=== DONE ===`);
  console.log(`Total tiles: ${totalTiles}`);
  console.log(`Output: ${OUTPUT_MBTILES}`);
  console.log(`Output size: ${(outputSize / 1024 / 1024).toFixed(1)} MB`);
}


main().catch(err => { console.error('Fatal error:', err); process.exit(1); });

/**
 * Extract all unique DXF layer names from QH-DaLat-2030.dxf (streaming parse).
 * Also counts entity types per layer.
 */
const fs = require('fs');
const readline = require('readline');
const path = require('path');

const DXF_PATH = path.join(__dirname, 'QH-DaLat-2030.dxf');

async function main() {
  console.log(`Parsing DXF: ${DXF_PATH}`);
  console.log(`File size: ${(fs.statSync(DXF_PATH).size / 1024 / 1024).toFixed(1)} MB\n`);

  const layerCounts = {};      // layer → total entity count
  const layerTypes = {};       // layer → { entityType → count }
  let inEntities = false;
  let awaitingSectionName = false;
  let currentLayer = '0';
  let currentType = null;
  let isCodeLine = true;
  let currentCode = 0;
  let entityCount = 0;

  // Also extract LAYER table definitions
  let inTables = false;
  let inLayerTable = false;
  let inLayerEntry = false;
  let currentLayerDef = null;
  const layerDefs = {}; // layer name → { color, lineType }

  const rl = readline.createInterface({
    input: fs.createReadStream(DXF_PATH, { encoding: 'utf8', highWaterMark: 4 * 1024 * 1024 }),
    crlfDelay: Infinity,
  });

  for await (const line of rl) {
    const t = line.trim();
    
    if (isCodeLine) {
      currentCode = parseInt(t, 10);
    } else {
      // Track sections
      if (currentCode === 0) {
        if (t === 'SECTION') { awaitingSectionName = true; }
        else if (t === 'ENDSEC') {
          if (inEntities) {
            // Emit last entity
            if (currentType) {
              layerCounts[currentLayer] = (layerCounts[currentLayer] || 0) + 1;
              if (!layerTypes[currentLayer]) layerTypes[currentLayer] = {};
              layerTypes[currentLayer][currentType] = (layerTypes[currentLayer][currentType] || 0) + 1;
              entityCount++;
            }
            inEntities = false;
          }
          inTables = false;
          inLayerTable = false;
        }
        else if (t === 'TABLE') { /* will check name on code 2 */ }
        else if (t === 'ENDTAB') { inLayerTable = false; inLayerEntry = false; }
        else if (t === 'EOF') { /* done */ }
        else if (inEntities) {
          // New entity — emit previous
          if (currentType) {
            layerCounts[currentLayer] = (layerCounts[currentLayer] || 0) + 1;
            if (!layerTypes[currentLayer]) layerTypes[currentLayer] = {};
            layerTypes[currentLayer][currentType] = (layerTypes[currentLayer][currentType] || 0) + 1;
            entityCount++;
            if (entityCount % 100000 === 0) process.stdout.write(`\r  ${entityCount} entities...`);
          }
          currentType = t;
          currentLayer = '0';
        }
        else if (inTables && inLayerTable) {
          if (t === 'LAYER') {
            // New layer definition entry
            if (currentLayerDef) layerDefs[currentLayerDef.name] = currentLayerDef;
            currentLayerDef = { name: '', color: 7, lineType: 'CONTINUOUS' };
            inLayerEntry = true;
          } else {
            if (currentLayerDef) layerDefs[currentLayerDef.name] = currentLayerDef;
            currentLayerDef = null;
            inLayerEntry = false;
          }
        }
      }
      else if (currentCode === 2 && awaitingSectionName) {
        awaitingSectionName = false;
        if (t === 'ENTITIES') inEntities = true;
        else if (t === 'TABLES') inTables = true;
      }
      else if (currentCode === 2 && inTables && !inLayerTable) {
        if (t === 'LAYER') inLayerTable = true;
      }
      else if (inEntities && currentCode === 8) {
        currentLayer = t;
      }
      // Layer table entries
      else if (inLayerEntry && currentLayerDef) {
        if (currentCode === 2) currentLayerDef.name = t;
        else if (currentCode === 62) currentLayerDef.color = parseInt(t, 10);
        else if (currentCode === 6) currentLayerDef.lineType = t;
      }
    }
    isCodeLine = !isCodeLine;
  }

  process.stdout.write('\n\n');

  // Sort layers by count
  const sorted = Object.entries(layerCounts).sort((a, b) => b[1] - a[1]);

  console.log('=== DXF LAYERS (sorted by entity count) ===');
  console.log(`Total entities: ${entityCount}`);
  console.log(`Total unique layers: ${sorted.length}\n`);
  console.log('Layer Name'.padEnd(40) + ' | Count'.padEnd(10) + ' | AutoCAD Color | Entity Types');
  console.log('-'.repeat(120));

  for (const [layer, count] of sorted) {
    const def = layerDefs[layer];
    const color = def ? def.color : '?';
    const types = layerTypes[layer] || {};
    const typeStr = Object.entries(types).map(([t, c]) => `${t}(${c})`).join(', ');
    console.log(`${layer.padEnd(40)} | ${String(count).padStart(8)} | ${String(color).padStart(13)} | ${typeStr}`);
  }

  // AutoCAD color index to approximate RGB mapping (standard ACI colors)
  console.log('\n\n=== AUTOCAD COLOR INDEX (ACI) → RGB MAPPING ===\n');
  const aciColors = {
    1: '#FF0000', 2: '#FFFF00', 3: '#00FF00', 4: '#00FFFF', 5: '#0000FF',
    6: '#FF00FF', 7: '#FFFFFF', 8: '#808080', 9: '#C0C0C0',
    10: '#FF0000', 11: '#FF7F7F', 12: '#CC0000',
    20: '#FF3F00', 21: '#FF9F7F', 30: '#FF7F00', 31: '#FFBF7F',
    40: '#FFBF00', 41: '#FFDF7F', 42: '#CC9900',
    50: '#FFFF00', 51: '#FFFF7F', 52: '#CCCC00',
    60: '#BFFF00', 61: '#DFFF7F', 70: '#7FFF00', 71: '#BFFF7F',
    80: '#3FFF00', 81: '#9FFF7F', 90: '#00FF00', 91: '#7FFF7F',
    100: '#00FF3F', 110: '#00FF7F', 120: '#00FFBF',
    130: '#00FFFF', 140: '#00BFFF', 150: '#007FFF', 160: '#003FFF',
    170: '#0000FF', 180: '#3F00FF', 190: '#7F00FF', 200: '#BF00FF',
    210: '#FF00FF', 220: '#FF00BF', 230: '#FF007F', 240: '#FF003F',
    250: '#333333', 251: '#505050', 252: '#696969', 253: '#808080',
    254: '#BFBFBF', 255: '#FFFFFF',
  };

  console.log('Layer Name'.padEnd(40) + ' | ACI | Approx RGB | Suggested Category');
  console.log('-'.repeat(100));

  for (const [layer] of sorted) {
    const def = layerDefs[layer];
    const aci = def ? def.color : 7;
    const rgb = aciColors[aci] || lookupACI(aci);
    const cat = categorizeLayer(layer);
    console.log(`${layer.padEnd(40)} | ${String(aci).padStart(3)} | ${(rgb || '?').padEnd(10)} | ${cat}`);
  }
}

function lookupACI(aci) {
  // Simplified ACI lookup for common ranges
  if (aci >= 1 && aci <= 9) {
    const map = {1:'#FF0000',2:'#FFFF00',3:'#00FF00',4:'#00FFFF',5:'#0000FF',6:'#FF00FF',7:'#FFFFFF',8:'#808080',9:'#C0C0C0'};
    return map[aci];
  }
  // Extended colors — approximate
  if (aci >= 10 && aci < 20) return '#FF' + Math.round((aci-10)*25.5).toString(16).padStart(2,'0') + '00';
  if (aci >= 250 && aci <= 255) {
    const gray = Math.round((aci - 250) * 51);
    return '#' + gray.toString(16).padStart(2,'0').repeat(3);
  }
  return '#808080';
}

function categorizeLayer(name) {
  const n = name.toUpperCase();
  if (n.includes('GIAO') || n.includes('DUONG') || n.includes('ROAD')) return 'Giao thông / Đường';
  if (n.includes('DAT_O') || n.includes('DATO') || n.includes('RESIDENTIAL') || n.includes('NHA_O') || n.includes('DAN_CU')) return 'Đất ở / Dân cư';
  if (n.includes('THUONG_MAI') || n.includes('DICHVU') || n.includes('COMMERCIAL')) return 'Thương mại / Dịch vụ';
  if (n.includes('CONG_VIEN') || n.includes('CAY_XANH') || n.includes('PARK') || n.includes('GREEN')) return 'Cây xanh / Công viên';
  if (n.includes('RUNG') || n.includes('FOREST')) return 'Rừng';
  if (n.includes('NUOC') || n.includes('WATER') || n.includes('SONG') || n.includes('HO')) return 'Mặt nước';
  if (n.includes('CONG_NGHIEP') || n.includes('INDUSTRIAL') || n.includes('CN')) return 'Công nghiệp';
  if (n.includes('GIAO_DUC') || n.includes('TRUONG') || n.includes('SCHOOL')) return 'Giáo dục';
  if (n.includes('Y_TE') || n.includes('BENH_VIEN') || n.includes('MEDICAL')) return 'Y tế';
  if (n.includes('TON_GIAO') || n.includes('CHUA') || n.includes('NHA_THO')) return 'Tôn giáo';
  if (n.includes('HANH_CHINH') || n.includes('CO_QUAN') || n.includes('GOVT')) return 'Hành chính';
  if (n.includes('QUY_HOACH') || n.includes('QH') || n.includes('PLAN')) return 'Quy hoạch (chung)';
  if (n.includes('RANH') || n.includes('BOUNDARY') || n.includes('BORDER')) return 'Ranh giới';
  if (n.includes('TEXT') || n.includes('LABEL') || n.includes('CHU')) return 'Nhãn / Text';
  if (n.includes('HATCHING') || n.includes('HATCH')) return 'Pattern / Hatch';
  if (n.includes('DIA_HINH') || n.includes('TOPO')) return 'Địa hình';
  if (n.includes('NONG') || n.includes('AGRI')) return 'Nông nghiệp';
  return 'Chưa phân loại';
}

main().catch(err => { console.error(err); process.exit(1); });

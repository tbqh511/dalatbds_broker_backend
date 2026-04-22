"""
Convert QH-DaLat-2030.dxf → GeoJSON thửa đất tách theo lưới khu vực.

Input:  ../MapFile/V3/QH-DaLat-2030.dxf
Output: v3_parcels/
            index.json          ← danh sách khu vực + bbox + count
            grid_X_Y.geojson    ← polygon thửa đất từng ô lưới 0.05°×0.05°

Hệ tọa độ DXF: VN2000 / TM-3 lon_0=108°, GRS80, false_easting=500000
Phạm vi dữ liệu: lat=[11.84,12.01], lng=[108.56,108.84] (phía đông TP Đà Lạt)
Layer thửa đất: Level 49_NenDiaChinh_16PX (~95,332 polygon VN2000)

Dùng: python3 convert_dxf_v3.py [--test]
  --test  Xử lý 2,000,000 dòng ENTITIES để kiểm tra nhanh

Yêu cầu: pip install pyproj
"""

import json
import os
import sys
import time
from collections import defaultdict

try:
    from pyproj import Transformer, CRS
except ImportError:
    print("Thiếu package. Cài đặt: pip install pyproj")
    sys.exit(1)

# ── Paths ──────────────────────────────────────────────────────────────────────
HERE    = os.path.dirname(os.path.abspath(__file__))
DXF_IN  = os.path.join(HERE, '..', 'MapFile', 'V3', 'QH-DaLat-2030.dxf')
OUT_DIR = os.path.join(HERE, 'v3_parcels')

# ── Projection: VN2000 TM-3 lon_0=108°, GRS80 ─────────────────────────────────
_CRS = CRS.from_proj4(
    '+proj=tmerc +lat_0=0 +lon_0=108 +k=0.9999 +x_0=500000 +y_0=0 +ellps=GRS80 +units=m +no_defs'
)
TRANS = Transformer.from_crs(_CRS, 'EPSG:4326', always_xy=True)

# ── Layer chứa thửa đất địa chính ─────────────────────────────────────────────
PARCEL_LAYER = 'Level 49_NenDiaChinh_16PX'

# ── Lọc tọa độ VN2000 hợp lệ (loại bỏ local CAD space) ───────────────────────
X_MIN = 500000
X_MAX = 650000
Y_MIN = 1280000
Y_MAX = 1380000

# ── Lưới phân ô: 0.05°×0.05° ≈ 5km×5km ───────────────────────────────────────
LNG_STEP  = 0.05
LAT_STEP  = 0.05
LNG_START = 108.55
LAT_START = 11.80


def vn2000_to_wgs84(x: float, y: float):
    lng, lat = TRANS.transform(x, y)
    return round(lng, 8), round(lat, 8)


def centroid(ring):
    n = len(ring)
    if n == 0:
        return None, None
    return sum(p[0] for p in ring) / n, sum(p[1] for p in ring) / n


def grid_slug(lng: float, lat: float) -> str:
    col = int((lng - LNG_START) / LNG_STEP)
    row = int((lat - LAT_START) / LAT_STEP)
    return f"grid_{col}_{row}"


def grid_name(slug: str) -> str:
    _, col_s, row_s = slug.split('_')
    col, row = int(col_s), int(row_s)
    lng_c = LNG_START + (col + 0.5) * LNG_STEP
    lat_c = LAT_START + (row + 0.5) * LAT_STEP
    return f"Khu {lng_c:.3f}°E,{lat_c:.3f}°N"


def bbox_of(features):
    mn_lng = mn_lat = float('inf')
    mx_lng = mx_lat = float('-inf')
    for f in features:
        for ring in f['geometry']['coordinates']:
            for lng, lat in ring:
                if lng < mn_lng: mn_lng = lng
                if lat < mn_lat: mn_lat = lat
                if lng > mx_lng: mx_lng = lng
                if lat > mx_lat: mx_lat = lat
    if mn_lng == float('inf'):
        return None
    return [round(mn_lng, 6), round(mn_lat, 6), round(mx_lng, 6), round(mx_lat, 6)]


def parse_entities(fname: str, test_mode: bool):
    """
    Parse ENTITIES section của DXF bằng raw text.
    Trả về list các polygon đã chuyển sang WGS84.
    """
    parcels = []
    skipped_local = 0
    skipped_small = 0

    # Level 49_NenDiaChinh_16PX bắt đầu ở line 63,539,129
    # Nhảy thẳng tới đó để tiết kiệm thời gian (bỏ qua ~58M dòng ENTITIES trước đó)
    # (63539000 - 5638762) % 2 == 0 → dòng 63539000 là code-line → is_code_line=True
    SKIP_TO_LINE = 63_539_000

    with open(fname, 'r', encoding='utf-8', errors='replace') as f:
        # DXF alternates: code-line, value-line, code-line, value-line...
        in_poly      = False
        layer        = ''
        xs: list     = []
        ys: list     = []
        is_code_line = True
        cur_code     = ''

        line_count   = 0
        entity_count = 0
        test_limit   = 3_000_000 if test_mode else float('inf')

        for i, raw in enumerate(f):
            if i < SKIP_TO_LINE:
                continue

            line_count += 1
            if line_count > test_limit:
                print(f"  [test] dừng sau {test_limit:,} dòng ENTITIES")
                break

            s = raw.strip()

            if is_code_line:
                cur_code = s
                is_code_line = False
            else:
                val = s
                is_code_line = True

                if cur_code == '0':
                    # Start of new entity — flush previous poly first
                    if in_poly:
                        if layer == PARCEL_LAYER and len(xs) >= 3:
                            if X_MIN < xs[0] < X_MAX and Y_MIN < ys[0] < Y_MAX:
                                ring = []
                                for x, y in zip(xs, ys):
                                    ring.append(list(vn2000_to_wgs84(x, y)))
                                if ring[0] != ring[-1]:
                                    ring.append(ring[0])
                                cx, cy = centroid(ring)
                                parcels.append({'ring': ring, 'cx': cx, 'cy': cy})
                            else:
                                skipped_local += 1
                        elif layer == PARCEL_LAYER:
                            skipped_small += 1
                        in_poly = False

                    if val == 'LWPOLYLINE':
                        in_poly = True
                        layer = ''
                        xs = []
                        ys = []
                        entity_count += 1
                    elif val == 'ENDSEC':
                        break

                elif in_poly:
                    if cur_code == '8':
                        layer = val
                    elif cur_code == '10':
                        try:
                            xs.append(float(val))
                        except ValueError:
                            pass
                    elif cur_code == '20':
                        try:
                            ys.append(float(val))
                        except ValueError:
                            pass

    print(f"  Dòng ENTITIES đã đọc: {line_count:,}")
    print(f"  LWPOLYLINE gặp: {entity_count:,}")
    print(f"  Bỏ qua (tọa độ local): {skipped_local:,}")
    print(f"  Bỏ qua (ít điểm):      {skipped_small:,}")
    print(f"  Thửa đất hợp lệ:       {len(parcels):,}")
    return parcels


def main():
    test_mode = '--test' in sys.argv
    os.makedirs(OUT_DIR, exist_ok=True)

    print(f"File: {DXF_IN}")
    if test_mode:
        print("** TEST MODE: chỉ đọc 2,000,000 dòng đầu ENTITIES **")
    print()

    t0 = time.time()

    # ── Parse ──────────────────────────────────────────────────────────────────
    print("Đang parse ENTITIES section...")
    parcels = parse_entities(DXF_IN, test_mode)
    print(f"Thời gian parse: {time.time()-t0:.0f}s\n")

    if not parcels:
        print("Không tìm thấy thửa đất. Kiểm tra lại ENTITIES_START_LINE.")
        sys.exit(1)

    # ── Phân ô lưới ────────────────────────────────────────────────────────────
    print("Phân loại theo lưới khu vực...")
    grid: dict = defaultdict(list)
    for p in parcels:
        slug = grid_slug(p['cx'], p['cy'])
        feature = {
            'type': 'Feature',
            'properties': {'parcel_id': ''},
            'geometry': {'type': 'Polygon', 'coordinates': [p['ring']]},
        }
        grid[slug].append(feature)

    # ── Xuất GeoJSON ───────────────────────────────────────────────────────────
    print("Xuất GeoJSON...")
    index = {}
    total = 0
    for slug, feats in sorted(grid.items()):
        out_path = os.path.join(OUT_DIR, f'{slug}.geojson')
        fc = {'type': 'FeatureCollection', 'features': feats}
        with open(out_path, 'w', encoding='utf-8') as f:
            json.dump(fc, f, ensure_ascii=False, separators=(',', ':'))
        size_kb = os.path.getsize(out_path) // 1024
        bb = bbox_of(feats)
        name = grid_name(slug)
        print(f"  {slug}.geojson  {len(feats):6,} thửa  {size_kb:5} KB  {name}")
        index[slug] = {
            'name':  name,
            'slug':  slug,
            'file':  f'{slug}.geojson',
            'count': len(feats),
            'bbox':  bb,
        }
        total += len(feats)

    idx_path = os.path.join(OUT_DIR, 'index.json')
    with open(idx_path, 'w', encoding='utf-8') as f:
        json.dump(index, f, ensure_ascii=False, indent=2)

    print(f"\n✓ Tổng: {total:,} thửa đất → {len(grid)} file")
    print(f"✓ Thời gian tổng: {time.time()-t0:.0f}s")
    print(f"✓ Index: {idx_path}")


if __name__ == '__main__':
    main()

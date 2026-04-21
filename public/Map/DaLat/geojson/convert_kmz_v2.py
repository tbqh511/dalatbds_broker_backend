"""
Convert TONG 503 DA LAT.kmz (V2) → GeoJSON files for the legal map feature.

Input:  ../MapFile/V2/TONG 503 DA LAT.kmz
Output: v2_zones/dalat_zones.geojson  (all 3028 polygons)
        v2_zones/zone_NN.geojson      (Nghiêm ngặt)
        v2_zones/zone_MT.geojson      (Môi trường)
        v2_zones/zone_SX.geojson      (Sản xuất)
        v2_zones/zone_DD.geojson      (Đặc dụng)
        v2_zones/index.json           (bbox + counts per zone)

Usage: python3 convert_kmz_v2.py
"""
import json
import os
import re
import zipfile

HERE    = os.path.dirname(os.path.abspath(__file__))
KMZ_IN  = os.path.join(HERE, '..', 'MapFile', 'V2', 'TONG 503 DA LAT.kmz')
OUT_DIR = os.path.join(HERE, 'v2_zones')

ZONE_MAP = {
    'Level NN': {'type': 'NN', 'label': 'Nghiêm ngặt',  'color': '#FFFF14'},
    'Level MT': {'type': 'MT', 'label': 'Môi trường',   'color': '#8CFF96'},
    'Level SX': {'type': 'SX', 'label': 'Sản xuất',     'color': '#00FF00'},
    'Level DD': {'type': 'DD', 'label': 'Đặc dụng',     'color': '#FF0000'},
}


def parse_coordinates(coord_text):
    """Parse KML coordinate string → list of [lng, lat] pairs."""
    coords = []
    for token in coord_text.strip().split():
        parts = token.split(',')
        if len(parts) >= 2:
            try:
                lng = float(parts[0])
                lat = float(parts[1])
                coords.append([lng, lat])
            except ValueError:
                continue
    return coords


def kml_polygon_to_geojson(pm_text):
    """Extract GeoJSON Polygon geometry from a KML Placemark string."""
    outer = re.search(
        r'<outerBoundaryIs>.*?<coordinates>(.*?)</coordinates>.*?</outerBoundaryIs>',
        pm_text, re.DOTALL
    )
    if not outer:
        return None

    rings = [parse_coordinates(outer.group(1))]

    for inner in re.finditer(
        r'<innerBoundaryIs>.*?<coordinates>(.*?)</coordinates>.*?</innerBoundaryIs>',
        pm_text, re.DOTALL
    ):
        rings.append(parse_coordinates(inner.group(1)))

    if not rings[0]:
        return None

    return {'type': 'Polygon', 'coordinates': rings}


def bbox_of(features):
    min_lng = min_lat = float('inf')
    max_lng = max_lat = float('-inf')
    for f in features:
        for ring in f['geometry']['coordinates']:
            for lng, lat in ring:
                if lng < min_lng: min_lng = lng
                if lat < min_lat: min_lat = lat
                if lng > max_lng: max_lng = lng
                if lat > max_lat: max_lat = lat
    return [min_lng, min_lat, max_lng, max_lat]


def main():
    os.makedirs(OUT_DIR, exist_ok=True)

    print(f'Reading {KMZ_IN} ...')
    with zipfile.ZipFile(KMZ_IN) as z:
        with z.open('doc.kml') as f:
            kml = f.read().decode('utf-8')

    placemarks = re.findall(r'<Placemark>(.*?)</Placemark>', kml, re.DOTALL)
    print(f'Found {len(placemarks)} Placemarks')

    all_features = []
    zone_buckets = {k: [] for k in ('NN', 'MT', 'SX', 'DD')}
    skipped = 0

    for pm in placemarks:
        desc_m = re.search(r'<description>(.*?)</description>', pm)
        desc   = desc_m.group(1).strip() if desc_m else ''
        info   = ZONE_MAP.get(desc)
        if not info:
            skipped += 1
            continue

        if '<Polygon>' not in pm:
            skipped += 1
            continue

        geom = kml_polygon_to_geojson(pm)
        if not geom:
            skipped += 1
            continue

        feature = {
            'type': 'Feature',
            'properties': {
                'zone_type':  info['type'],
                'zone_label': info['label'],
                'zone_color': info['color'],
            },
            'geometry': geom,
        }
        all_features.append(feature)
        zone_buckets[info['type']].append(feature)

    print(f'Converted: {len(all_features)} features, skipped: {skipped}')

    # Write combined file
    all_path = os.path.join(OUT_DIR, 'dalat_zones.geojson')
    with open(all_path, 'w', encoding='utf-8') as f:
        json.dump({'type': 'FeatureCollection', 'features': all_features},
                  f, ensure_ascii=False, separators=(',', ':'))
    size_kb = os.path.getsize(all_path) // 1024
    print(f'  dalat_zones.geojson  {len(all_features):5d} features  {size_kb} KB')

    # Write per-zone files + build index
    index = {}
    for zone_type, feats in zone_buckets.items():
        fname = f'zone_{zone_type}.geojson'
        fpath = os.path.join(OUT_DIR, fname)
        with open(fpath, 'w', encoding='utf-8') as f:
            json.dump({'type': 'FeatureCollection', 'features': feats},
                      f, ensure_ascii=False, separators=(',', ':'))
        size_kb = os.path.getsize(fpath) // 1024
        info    = next(v for v in ZONE_MAP.values() if v['type'] == zone_type)
        bb      = bbox_of(feats) if feats else None
        print(f'  {fname:20s} {len(feats):5d} features  {size_kb} KB')
        index[zone_type] = {
            'type':          zone_type,
            'label':         info['label'],
            'color':         info['color'],
            'file':          fname,
            'feature_count': len(feats),
            'bbox':          bb,
        }

    idx_path = os.path.join(OUT_DIR, 'index.json')
    with open(idx_path, 'w', encoding='utf-8') as f:
        json.dump(index, f, ensure_ascii=False, indent=2)
    print(f'\nIndex: {idx_path}')
    print('Done.')


if __name__ == '__main__':
    main()

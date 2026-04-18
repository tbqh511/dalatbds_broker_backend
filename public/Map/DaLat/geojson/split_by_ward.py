"""
Split dalat_qhln.geojson into 5 files per the new super-ward structure
(Nghị quyết 1671/NQ-UBTVQH15 ngày 16/6/2025, hiệu lực 01/7/2025).

Output:
  wards/xuan-huong.geojson
  wards/cam-ly.geojson
  wards/lam-vien.geojson
  wards/xuan-truong.geojson
  wards/lang-biang.geojson
  wards_index.json  (bbox + member old-wards per super-ward, for frontend lookup)

Usage: python3 split_by_ward.py
"""
import json
import os

HERE = os.path.dirname(os.path.abspath(__file__))
SRC = os.path.join(HERE, "dalat_qhln.geojson")
OUT_DIR = os.path.join(HERE, "wards")
INDEX = os.path.join(HERE, "wards_index.json")

# old ward (geojson "Xa") -> new super-ward slug
OLD_TO_NEW = {
    "Phường 1":     "xuan-huong",
    "Phường 2":     "xuan-huong",
    "Phường 3":     "xuan-huong",
    "Phường 4":     "xuan-huong",
    "Phường 10":    "xuan-huong",
    "Phường 5":     "cam-ly",
    "Phường 6":     "cam-ly",
    "Tà Nung":      "cam-ly",
    "Phường 8":     "lam-vien",
    "Phường 9":     "lam-vien",
    "Phường 12":    "lam-vien",
    "Phường 11":    "xuan-truong",
    "Xuân Thọ":     "xuan-truong",
    "Xuân Trường":  "xuan-truong",
    "Trạm Hành":    "xuan-truong",
    "Phường 7":     "lang-biang",
    "TT Lạc Dương": "lang-biang",
    "Xã Lát":       "lang-biang",
}

NEW_NAMES = {
    "xuan-huong":   "Xuân Hương - Đà Lạt",
    "cam-ly":       "Cam Ly - Đà Lạt",
    "lam-vien":     "Lâm Viên - Đà Lạt",
    "xuan-truong":  "Xuân Trường - Đà Lạt",
    "lang-biang":   "Lang Biang - Đà Lạt",
}

# Real ward codes from locations_wards table (district_code='672' = Đà Lạt)
# Used as the lookup key for property.ward_code → super-ward GeoJSON file.
WARD_CODES = {
    "xuan-huong":  "24796",
    "cam-ly":      "24790",
    "lam-vien":    "24778",
    "xuan-truong": "24811",
    "lang-biang":  "24769",
}

def coords_iter(geom):
    """Yield (lng, lat) from any Polygon/MultiPolygon."""
    t = geom.get("type")
    coords = geom.get("coordinates", [])
    if t == "Polygon":
        for ring in coords:
            for p in ring:
                yield p[0], p[1]
    elif t == "MultiPolygon":
        for poly in coords:
            for ring in poly:
                for p in ring:
                    yield p[0], p[1]

def main():
    os.makedirs(OUT_DIR, exist_ok=True)
    with open(SRC) as f:
        gj = json.load(f)

    buckets = {slug: [] for slug in NEW_NAMES}
    unmapped = []

    for feat in gj["features"]:
        xa = (feat.get("properties") or {}).get("Xa", "")
        slug = OLD_TO_NEW.get(xa)
        if slug is None:
            unmapped.append(xa)
            continue
        buckets[slug].append(feat)

    index = {}
    for slug, feats in buckets.items():
        # compute bbox
        if feats:
            xs, ys = [], []
            for f in feats:
                for x, y in coords_iter(f["geometry"]):
                    xs.append(x); ys.append(y)
            bbox = [min(xs), min(ys), max(xs), max(ys)]  # [west, south, east, north]
        else:
            bbox = None

        out_path = os.path.join(OUT_DIR, f"{slug}.geojson")
        with open(out_path, "w", encoding="utf-8") as f:
            json.dump(
                {"type": "FeatureCollection", "features": feats},
                f, ensure_ascii=False, separators=(",", ":"),
            )
        size_kb = os.path.getsize(out_path) // 1024
        print(f"  {slug:14s} {len(feats):6d} features  {size_kb:6d} KB  bbox={bbox}")

        index[slug] = {
            "code": WARD_CODES[slug],
            "name": NEW_NAMES[slug],
            "file": f"wards/{slug}.geojson",
            "bbox": bbox,
            "old_wards": [k for k, v in OLD_TO_NEW.items() if v == slug],
            "feature_count": len(feats),
        }

    # Also build a code→slug lookup so frontend can map property.ward_code directly.
    by_code = {WARD_CODES[slug]: slug for slug in NEW_NAMES}
    out_index = {"by_slug": index, "by_code": by_code}

    with open(INDEX, "w", encoding="utf-8") as f:
        json.dump(out_index, f, ensure_ascii=False, indent=2)
    print(f"\nIndex written: {INDEX}")

    if unmapped:
        from collections import Counter
        c = Counter(unmapped)
        print(f"\nUnmapped wards ({sum(c.values())} features):")
        for k, v in c.most_common():
            print(f"  {k!r}: {v}")

if __name__ == "__main__":
    main()

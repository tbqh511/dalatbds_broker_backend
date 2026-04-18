"""
Split wards/xuan-truong.geojson into 3 sub-files to keep each under 3 MB gzipped.

Group A (xuan-truong-a): Phường 11 + Xuân Trường  — urban/semi-urban, city edge  ~1.8MB gz
Group B (xuan-truong-b): Xuân Thọ                 — rural north                   ~2.8MB gz
Group C (xuan-truong-c): Trạm Hành                — rural south                   ~1.3MB gz

Updates wards_index.json: xuan-truong entry gets `files` array (sub-bbox per file)
instead of single `file` string. Frontend picks sub-file whose bbox contains property lat/lng.

Usage: python3 split_xuan_truong.py
"""
import json, os

HERE   = os.path.dirname(os.path.abspath(__file__))
SRC    = os.path.join(HERE, "wards", "xuan-truong.geojson")
INDEX  = os.path.join(HERE, "wards_index.json")

GROUPS = {
    "xuan-truong-a": ["Phường 11", "Xuân Trường"],
    "xuan-truong-b": ["Xuân Thọ"],
    "xuan-truong-c": ["Trạm Hành"],
}

def coords_iter(geom):
    t = geom.get("type")
    c = geom.get("coordinates", [])
    if t == "Polygon":
        for ring in c:
            for p in ring: yield p[0], p[1]
    elif t == "MultiPolygon":
        for poly in c:
            for ring in poly:
                for p in ring: yield p[0], p[1]

def bbox_of(feats):
    xs, ys = [], []
    for f in feats:
        for x, y in coords_iter(f["geometry"]):
            xs.append(x); ys.append(y)
    return [min(xs), min(ys), max(xs), max(ys)]

def main():
    with open(SRC, encoding="utf-8") as f:
        gj = json.load(f)

    buckets = {k: [] for k in GROUPS}
    unmatched = []

    for feat in gj["features"]:
        xa = (feat.get("properties") or {}).get("Xa", "")
        placed = False
        for key, wards in GROUPS.items():
            if xa in wards:
                buckets[key].append(feat)
                placed = True
                break
        if not placed:
            unmatched.append(xa)

    sub_entries = []
    for key, feats in buckets.items():
        out_path = os.path.join(HERE, "wards", f"{key}.geojson")
        with open(out_path, "w", encoding="utf-8") as f:
            json.dump({"type": "FeatureCollection", "features": feats},
                      f, ensure_ascii=False, separators=(",", ":"))
        size_kb = os.path.getsize(out_path) // 1024
        bb = bbox_of(feats)
        print(f"  {key}: {len(feats)} features  {size_kb} KB  bbox={bb}")
        sub_entries.append({
            "file":      f"wards/{key}.geojson",
            "bbox":      bb,
            "old_wards": GROUPS[key],
            "feature_count": len(feats),
        })

    # Update index: replace single `file` with `files` array in xuan-truong entry
    with open(INDEX, encoding="utf-8") as f:
        idx = json.load(f)

    xt = idx["by_slug"]["xuan-truong"]
    xt.pop("file", None)        # remove old single-file key
    xt["files"] = sub_entries   # add sub-file list

    with open(INDEX, "w", encoding="utf-8") as f:
        json.dump(idx, f, ensure_ascii=False, indent=2)
    print(f"\nIndex updated: {INDEX}")

    if unmatched:
        from collections import Counter
        c = Counter(unmatched)
        print(f"\nUnmatched Xa values ({sum(c.values())} features):")
        for k, v in c.most_common(): print(f"  {k!r}: {v}")

if __name__ == "__main__":
    main()

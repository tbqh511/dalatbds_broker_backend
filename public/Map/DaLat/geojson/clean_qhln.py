"""
Clean Dalat_QHLN raw GeoJSON:
1. Filter to Polygon/MultiPolygon (extract polygons from GeometryCollection).
2. Convert TCVN3-encoded Vietnamese text to UTF-8.
3. Drop empty/invalid features.

Usage: python3 clean_qhln.py
Input:  dalat_qhln_raw.geojson
Output: dalat_qhln.geojson
"""
import json
import os

HERE = os.path.dirname(os.path.abspath(__file__))
SRC = os.path.join(HERE, "dalat_qhln_raw.geojson")
DST = os.path.join(HERE, "dalat_qhln.geojson")

TCVN3 = str.maketrans({
    '¸':'á','µ':'à','¶':'ả','·':'ã','¹':'ạ',
    '¨':'ă','¾':'ắ','»':'ằ','¼':'ẳ','½':'ẵ','Æ':'ặ',
    '©':'â','Ê':'ấ','Ç':'ầ','È':'ẩ','É':'ẫ','Ë':'ậ',
    '®':'đ','§':'Đ',
    'Î':'é','Ì':'è','Ø':'ỉ','Ï':'ẽ','Ñ':'ẹ',
    'ª':'ê','Õ':'ế','Ò':'ề','Ó':'ể','Ô':'ễ','Ö':'ệ',
    'Ý':'í','×':'ì','Ü':'ĩ','Þ':'ị',
    'ã':'ó','ß':'ò','á':'ỏ','â':'õ','ä':'ọ',
    '«':'ô','è':'ố','å':'ồ','æ':'ổ','ç':'ỗ','é':'ộ',
    '¬':'ơ','í':'ớ','ê':'ờ','ë':'ở','ì':'ỡ','î':'ợ',
    'ó':'ú','ï':'ù','ñ':'ủ','ò':'ũ','ô':'ụ',
    '­':'ư','ø':'ứ','õ':'ừ','ö':'ử','÷':'ữ','ù':'ự',
    'ý':'ý','ú':'ỳ','û':'ỷ','ü':'ỹ','þ':'ỵ',
})

TEXT_FIELDS = {"Xa", "Khoanh", "Lo", "tobando", "LDLR", "mdsd", "Tenkhu"}

def fix_text(s):
    if isinstance(s, str):
        return s.translate(TCVN3)
    return s

def extract_polygons(geom):
    """Return Polygon/MultiPolygon from any geometry, else None."""
    if geom is None:
        return None
    t = geom.get("type")
    if t in ("Polygon", "MultiPolygon"):
        return geom
    if t == "GeometryCollection":
        polys = []
        for g in geom.get("geometries", []):
            sub = extract_polygons(g)
            if sub is None:
                continue
            if sub["type"] == "Polygon":
                polys.append(sub["coordinates"])
            else:
                polys.extend(sub["coordinates"])
        if not polys:
            return None
        if len(polys) == 1:
            return {"type": "Polygon", "coordinates": polys[0]}
        return {"type": "MultiPolygon", "coordinates": polys}
    return None

def main():
    with open(SRC) as f:
        gj = json.load(f)

    out_features = []
    skipped = 0
    for feat in gj["features"]:
        geom = extract_polygons(feat.get("geometry"))
        if geom is None:
            skipped += 1
            continue
        props = {k: fix_text(v) for k, v in (feat.get("properties") or {}).items()}
        out_features.append({
            "type": "Feature",
            "properties": props,
            "geometry": geom,
        })

    out = {"type": "FeatureCollection", "features": out_features}
    with open(DST, "w", encoding="utf-8") as f:
        json.dump(out, f, ensure_ascii=False, separators=(",", ":"))

    print(f"Input:   {len(gj['features'])} features")
    print(f"Kept:    {len(out_features)} polygon features")
    print(f"Skipped: {skipped} non-polygon features")
    print(f"Output:  {DST}")

if __name__ == "__main__":
    main()

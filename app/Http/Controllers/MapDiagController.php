<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * Temporary diagnostic controller – DELETE after debugging.
 * GET /map-diag
 */
class MapDiagController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $mbtilesPath = config('location.mbtiles_dalat');
        $result = [
            'path'        => $mbtilesPath,
            'file_exists' => file_exists($mbtilesPath),
        ];

        if (!$result['file_exists']) {
            return response()->json($result);
        }

        $result['file_size_mb'] = round(filesize($mbtilesPath) / 1024 / 1024, 1);

        $db = new \SQLite3($mbtilesPath, SQLITE3_OPEN_READONLY);

        // 1. Read all metadata
        $meta = [];
        $r = $db->query("SELECT name, value FROM metadata");
        while ($row = $r->fetchArray(SQLITE3_ASSOC)) {
            $meta[$row['name']] = $row['value'];
        }
        $result['metadata'] = $meta;

        // 2. Tile count per zoom
        $zoomCounts = [];
        $r = $db->query("SELECT zoom_level, COUNT(*) as cnt FROM tiles GROUP BY zoom_level ORDER BY zoom_level");
        while ($row = $r->fetchArray(SQLITE3_ASSOC)) {
            $zoomCounts[(int) $row['zoom_level']] = (int) $row['cnt'];
        }
        $result['tiles_per_zoom'] = $zoomCounts;

        // 3. Sample tile at zoom 15
        $sample = $db->querySingle(
            "SELECT zoom_level, tile_column, tile_row, LENGTH(tile_data) as size FROM tiles WHERE zoom_level = 15 LIMIT 1",
            true
        );
        $result['sample_tile_z15'] = $sample ?: 'none';

        // 4. Check if tile data is gzip
        $r = $db->querySingle("SELECT tile_data FROM tiles LIMIT 1", true);
        if ($r && !empty($r['tile_data'])) {
            $data = $r['tile_data'];
            $result['first_tile_gzip'] = (strlen($data) >= 2 && ord($data[0]) === 0x1f && ord($data[1]) === 0x8b);
            $result['first_tile_size'] = strlen($data);
        }

        // 5. Coordinate check: what Leaflet would request for Đà Lạt center (11.9416, 108.4383)
        $dalatLat = 11.9416;
        $dalatLng = 108.4383;
        $z = 15;
        $n = pow(2, $z);
        $tileX = (int) floor(($dalatLng + 180) / 360 * $n);
        $latRad = $dalatLat * M_PI / 180;
        $tileY = (int) floor((1 - log(tan($latRad) + 1 / cos($latRad)) / M_PI) / 2 * $n);
        $tmsRow = (int) (pow(2, $z) - 1 - $tileY);
        $result['dalat_tile_z15'] = [
            'xyz'  => "$z/$tileX/$tileY",
            'tms_row' => $tmsRow,
        ];

        // Check if this tile exists
        $stmt = $db->prepare("SELECT LENGTH(tile_data) as size FROM tiles WHERE zoom_level = :z AND tile_column = :x AND tile_row = :y LIMIT 1");
        $stmt->bindValue(':z', $z, SQLITE3_INTEGER);
        $stmt->bindValue(':x', $tileX, SQLITE3_INTEGER);
        $stmt->bindValue(':y', $tmsRow, SQLITE3_INTEGER);
        $r = $stmt->execute();
        $row = $r ? $r->fetchArray(SQLITE3_ASSOC) : null;
        $result['dalat_tile_found'] = $row ? true : false;

        // 6. Find actual tile range at zoom 15 to see WHERE the data actually is
        $minX = $db->querySingle("SELECT MIN(tile_column) FROM tiles WHERE zoom_level = 15");
        $maxX = $db->querySingle("SELECT MAX(tile_column) FROM tiles WHERE zoom_level = 15");
        $minY = $db->querySingle("SELECT MIN(tile_row) FROM tiles WHERE zoom_level = 15");
        $maxY = $db->querySingle("SELECT MAX(tile_row) FROM tiles WHERE zoom_level = 15");
        $result['actual_tile_range_z15'] = [
            'x_range' => "$minX - $maxX",
            'y_range_tms' => "$minY - $maxY",
        ];

        // Convert tile range back to lat/lng to see geographic coverage
        if ($minX !== null) {
            $n15 = pow(2, 15);
            // TMS to XYZ for lat calc
            $xyzYmin = $n15 - 1 - $maxY;  // smallest TMS Y → largest XYZ Y → southernmost
            $xyzYmax = $n15 - 1 - $minY;  // largest TMS Y → smallest XYZ Y → northernmost

            $lngMin = $minX / $n15 * 360 - 180;
            $lngMax = ($maxX + 1) / $n15 * 360 - 180;
            $latMax = atan(sinh(M_PI * (1 - 2 * $xyzYmin / $n15))) * 180 / M_PI;
            $latMin = atan(sinh(M_PI * (1 - 2 * ($xyzYmax + 1) / $n15))) * 180 / M_PI;
            $result['actual_geo_coverage_z15'] = [
                'lat' => round($latMin, 4) . " → " . round($latMax, 4),
                'lng' => round($lngMin, 4) . " → " . round($lngMax, 4),
                'expected_dalat' => '~11.94°N, ~108.44°E',
            ];
        }

        $db->close();

        // 7. Projection analysis
        $result['PROJECTION_WARNING'] = [
            'mbtiles_converter_used' => 'UTM zone 48N (lon_0=105°E, k=0.9996, ellps=WGS84) — EPSG:3405',
            'correct_projection'     => 'VN2000 TM-3 (lon_0=108°E, k=0.9999, ellps=GRS80)',
            'note' => 'If the MBTiles conversion script used the wrong projection, data would be shifted ~3° west of the correct location!',
        ];

        return response()->json($result, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

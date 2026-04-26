<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SQLite3;

class MapTileController extends Controller
{
    /**
     * Serve a single PNG tile from the Đà Lạt planning MBTiles file.
     *
     * GET /map-tiles/dalat/{z}/{x}/{y}.png
     *
     * MBTiles uses TMS row convention (Y-flipped relative to XYZ/Leaflet):
     *   tile_row = (2^z − 1) − y_xyz
     */
    public function dalatTile(Request $request, int $z, int $x, int $y): Response
    {
        if ($z < 13 || $z > 17) {
            return $this->emptyTile();
        }

        $mbtilesPath = config('location.mbtiles_dalat');

        if (!file_exists($mbtilesPath)) {
            abort(500, 'MBTiles file not found.');
        }

        $tmsRow = (int) ((2 ** $z - 1) - $y);

        $db   = new SQLite3($mbtilesPath, SQLITE3_OPEN_READONLY);
        $stmt = $db->prepare(
            'SELECT tile_data FROM tiles WHERE zoom_level = :z AND tile_column = :x AND tile_row = :row LIMIT 1'
        );
        $stmt->bindValue(':z',   $z,      SQLITE3_INTEGER);
        $stmt->bindValue(':x',   $x,      SQLITE3_INTEGER);
        $stmt->bindValue(':row', $tmsRow, SQLITE3_INTEGER);

        $result  = $stmt->execute();
        $tileRow = $result ? $result->fetchArray(SQLITE3_ASSOC) : null;
        $db->close();

        if (!$tileRow || empty($tileRow['tile_data'])) {
            return $this->emptyTile();
        }

        $tileData = $tileRow['tile_data'];
        $etag     = '"' . md5($tileData) . '"';

        if ($request->header('If-None-Match') === $etag) {
            return response('', 304);
        }

        return response($tileData, 200, [
            'Content-Type'               => 'image/png',
            'Cache-Control'              => 'public, max-age=2592000, immutable',
            'ETag'                       => $etag,
            'Access-Control-Allow-Origin'=> '*',
        ]);
    }

    /**
     * Return a 1×1 transparent PNG for out-of-bounds or missing tiles.
     * Leaflet shows blank (not broken-image icon) at coverage edges.
     */
    private function emptyTile(): Response
    {
        static $blank = null;
        if ($blank === null) {
            $blank = base64_decode(
                'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
            );
        }

        return response($blank, 200, [
            'Content-Type'  => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}

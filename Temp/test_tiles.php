<?php
$z = 15;
$x = 25890;
$y = 15180; // Example coordinates

$mbtilesPath = __DIR__ . '/public/Map/DaLat/MapFile/V3/QH-DaLat-2030.mbtiles';
if (!file_exists($mbtilesPath)) {
    die("MBTiles not found\n");
}

$tmsRow = (int) ((2 ** $z - 1) - $y);
echo "Looking for Z: $z, X: $x, TMS_Y: $tmsRow\n";

$db = new SQLite3($mbtilesPath, SQLITE3_OPEN_READONLY);

// Let's just count tiles to see what exists at zoom 15
$count = $db->querySingle("SELECT COUNT(*) FROM tiles WHERE zoom_level = 15");
echo "Total tiles at zoom 15: $count\n";

$sample = $db->querySingle("SELECT tile_column || ',' || tile_row FROM tiles WHERE zoom_level = 15 LIMIT 1");
echo "Sample tile at zoom 15 (X,TMS_Y): $sample\n";

$db->close();

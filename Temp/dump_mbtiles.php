<?php
$dbPath = __DIR__ . '/public/Map/DaLat/MapFile/V3/QH-DaLat-2030.mbtiles';
if (!file_exists($dbPath)) {
    die("File not found: " . $dbPath);
}

$db = new SQLite3($dbPath);
$result = $db->query("SELECT * FROM metadata");
echo "--- METADATA ---\n";
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo $row['name'] . ": " . $row['value'] . "\n";
}

$result = $db->query("SELECT * FROM tiles LIMIT 1");
echo "\n--- SAMPLE TILE ---\n";
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "Zoom: " . $row['zoom_level'] . ", X: " . $row['tile_column'] . ", Y: " . $row['tile_row'] . "\n";
    $data = $row['tile_data'];
    $isGzip = (strlen($data) >= 2 && ord($data[0]) === 0x1f && ord($data[1]) === 0x8b) ? 'Yes' : 'No';
    echo "Size: " . strlen($data) . " bytes\n";
    echo "Gzipped: " . $isGzip . "\n";
}
$db->close();

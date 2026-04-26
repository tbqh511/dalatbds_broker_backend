<?php
try {
    $db = new PDO('sqlite:public/Map/DaLat/MapFile/V2/TP DA LAT LD 2021.mbtiles');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $db->query("SELECT value FROM metadata WHERE name='format'");
    $res = $stmt->fetch();
    echo "Format from metadata: " . ($res ? $res['value'] : 'not found') . "\n";
    
    $stmt2 = $db->query("SELECT hex(substr(tile_data, 1, 8)) as magic FROM tiles LIMIT 1");
    $res2 = $stmt2->fetch();
    $magic = $res2 ? $res2['magic'] : 'not found';
    echo "Magic bytes of first tile: " . $magic . "\n";
    
    if (strpos($magic, '89504E47') === 0) {
        echo "Tile data is PNG.\n";
    } elseif (strpos($magic, '1F8B') === 0) {
        echo "Tile data is GZIP (likely PBF/MVT).\n";
    } elseif (strpos($magic, 'FFD8FF') === 0) {
        echo "Tile data is JPEG.\n";
    } else {
        echo "Tile data format unknown.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

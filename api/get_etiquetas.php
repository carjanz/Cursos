<?php
/**
 * API para obtener etiquetas
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once(__DIR__ . '/../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $result = get_all_etiquetas();
    if (!$result) {
        throw new Exception("Error en consulta: " . $GLOBALS['conn']->error);
    }
    
    $etiquetas = array();
    
    while ($row = $result->fetch_assoc()) {
        $etiquetas[] = $row;
    }
    
    echo json_encode($etiquetas, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?>

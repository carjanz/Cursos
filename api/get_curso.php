<?php
/**
 * API para obtener un curso específico por slug
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
    $slug = isset($_GET['slug']) ? $GLOBALS['conn']->real_escape_string(trim($_GET['slug'])) : '';
    
    if (empty($slug)) {
        throw new Exception('Slug no proporcionado');
    }
    
    $curso = get_curso_by_slug($slug);
    
    if (!$curso) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Curso no encontrado'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $curso['etiquetas'] = $curso['etiquetas'] ? explode(',', $curso['etiquetas']) : [];
    
    echo json_encode(['success' => true, 'curso' => $curso], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?>

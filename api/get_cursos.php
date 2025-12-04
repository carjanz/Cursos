<?php
/**
 * API para obtener cursos con filtros
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
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $tags = isset($_GET['tags']) ? $_GET['tags'] : '';
    $search = isset($_GET['search']) ? $conn->real_escape_string(trim($_GET['search'])) : '';
    
    $offset = ($page - 1) * ITEMS_PER_PAGE;
    
    // Construir consulta base
    $query = "SELECT DISTINCT c.id, c.titulo, c.slug, c.descripcion, c.categoria_id, 
              c.instructor, c.imagen, c.duracion, c.nivel, c.precio, 
              c.estudiantes, c.calificacion, cat.nombre as categoria_nombre, 
              cat.slug as categoria_slug, GROUP_CONCAT(e.nombre) as etiquetas
              FROM cursos c
              LEFT JOIN categorias cat ON c.categoria_id = cat.id
              LEFT JOIN curso_etiqueta ce ON c.id = ce.curso_id
              LEFT JOIN etiquetas e ON ce.etiqueta_id = e.id
              WHERE c.estado = 'activo'";
    
    // Filtro por búsqueda
    if (!empty($search)) {
        $query .= " AND (c.titulo LIKE '%{$search}%' OR c.descripcion LIKE '%{$search}%')";
    }
    
    // Filtro por tags
    if (!empty($tags)) {
        $tagArray = array_filter(array_map('intval', explode(',', $tags)));
        if (!empty($tagArray)) {
            $tagList = implode(',', $tagArray);
            $query .= " AND c.id IN (
                SELECT DISTINCT ce.curso_id 
                FROM curso_etiqueta ce 
                WHERE ce.etiqueta_id IN ($tagList)
            )";
        }
    }
    
    $query .= " GROUP BY c.id ";
    
    // Contar total de registros
    $countQuery = "SELECT COUNT(DISTINCT c.id) as total FROM cursos c
                   LEFT JOIN categorias cat ON c.categoria_id = cat.id
                   LEFT JOIN curso_etiqueta ce ON c.id = ce.curso_id
                   LEFT JOIN etiquetas e ON ce.etiqueta_id = e.id
                   WHERE c.estado = 'activo'";
    
    if (!empty($search)) {
        $countQuery .= " AND (c.titulo LIKE '%{$search}%' OR c.descripcion LIKE '%{$search}%')";
    }
    
    if (!empty($tags)) {
        $tagArray = array_filter(array_map('intval', explode(',', $tags)));
        if (!empty($tagArray)) {
            $tagList = implode(',', $tagArray);
            $countQuery .= " AND c.id IN (
                SELECT DISTINCT ce.curso_id 
                FROM curso_etiqueta ce 
                WHERE ce.etiqueta_id IN ($tagList)
            )";
        }
    }
    
    $countResult = $conn->query($countQuery);
    if (!$countResult) {
        throw new Exception("Error en consulta: " . $conn->error);
    }
    
    $countRow = $countResult->fetch_assoc();
    $totalCursos = $countRow['total'] ?? 0;
    $totalPages = ceil($totalCursos / ITEMS_PER_PAGE);
    
    // Agregar paginación
    $query .= " ORDER BY c.fecha_creacion DESC LIMIT {$offset}, " . ITEMS_PER_PAGE;
    
    $result = $conn->query($query);
    if (!$result) {
        throw new Exception("Error en consulta: " . $conn->error);
    }
    
    $cursos = [];
    while ($row = $result->fetch_assoc()) {
        $row['etiquetas'] = $row['etiquetas'] ? explode(',', $row['etiquetas']) : [];
        $cursos[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'cursos' => $cursos,
        'total_cursos' => $totalCursos,
        'total_pages' => $totalPages,
        'current_page' => $page,
        'items_per_page' => ITEMS_PER_PAGE
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>

<?php
/**
 * Configuración de conexión a base de datos
 */

// Variables de configuración
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cursos_db'); // ✅ Debe coincidir con schema.sql
define('DB_CHARSET', 'utf8mb4');

// Crear conexión
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    // Establecer charset
    $conn->set_charset(DB_CHARSET);
    
} catch (Exception $e) {
    die("Error en la conexión a la base de datos: " . $e->getMessage());
}

// Configuración general
define('SITE_NAME', 'CursoHub');
define('SITE_URL', 'http://localhost/Centro/');
define('ADMIN_EMAIL', 'admin@cursohub.com');
define('ITEMS_PER_PAGE', 12);

// Función para sanitizar datos
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(htmlspecialchars(trim($data)));
}

// Función para obtener todos los cursos
function get_all_cursos($limit = ITEMS_PER_PAGE, $offset = 0) {
    global $conn;
    
    $query = "SELECT c.*, cat.nombre as categoria_nombre, cat.slug as categoria_slug,
              GROUP_CONCAT(e.nombre) as etiquetas
              FROM cursos c
              LEFT JOIN categorias cat ON c.categoria_id = cat.id
              LEFT JOIN curso_etiqueta ce ON c.id = ce.curso_id
              LEFT JOIN etiquetas e ON ce.etiqueta_id = e.id
              WHERE c.estado = 'activo'
              GROUP BY c.id
              ORDER BY c.fecha_creacion DESC
              LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    
    return $stmt->get_result();
}

// Función para obtener curso por slug
function get_curso_by_slug($slug) {
    global $conn;
    
    $slug = sanitize($slug);
    $query = "SELECT c.*, cat.nombre as categoria_nombre, cat.slug as categoria_slug,
              GROUP_CONCAT(e.id) as etiqueta_ids,
              GROUP_CONCAT(e.nombre) as etiquetas
              FROM cursos c
              LEFT JOIN categorias cat ON c.categoria_id = cat.id
              LEFT JOIN curso_etiqueta ce ON c.id = ce.curso_id
              LEFT JOIN etiquetas e ON ce.etiqueta_id = e.id
              WHERE c.slug = ? AND c.estado = 'activo'
              GROUP BY c.id";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

// Función para obtener todas las etiquetas
function get_all_etiquetas() {
    global $conn;
    
    $query = "SELECT e.*, COUNT(ce.curso_id) as cantidad_cursos
              FROM etiquetas e
              LEFT JOIN curso_etiqueta ce ON e.id = ce.etiqueta_id
              GROUP BY e.id
              ORDER BY e.nombre ASC";
    
    $result = $conn->query($query);
    return $result;
}

// Función para obtener todas las categorías
function get_all_categorias() {
    global $conn;
    
    $query = "SELECT c.*, COUNT(cu.id) as cantidad_cursos
              FROM categorias c
              LEFT JOIN cursos cu ON c.id = cu.categoria_id AND cu.estado = 'activo'
              GROUP BY c.id
              ORDER BY c.nombre ASC";
    
    $result = $conn->query($query);
    return $result;
}

// Función para contar total de cursos
function count_total_cursos() {
    global $conn;
    
    $query = "SELECT COUNT(*) as total FROM cursos WHERE estado = 'activo'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    
    return $row['total'];
}

// Función para obtener cursos relacionados
function get_cursos_relacionados($curso_id, $limit = 4) {
    global $conn;
    
    $curso_id = (int)$curso_id;
    
    $query = "SELECT c.*, cat.nombre as categoria_nombre,
              GROUP_CONCAT(e.nombre) as etiquetas
              FROM cursos c
              LEFT JOIN categorias cat ON c.categoria_id = cat.id
              LEFT JOIN curso_etiqueta ce ON c.id = ce.curso_id
              LEFT JOIN etiquetas e ON ce.etiqueta_id = e.id
              WHERE c.estado = 'activo' AND c.id != ?
              GROUP BY c.id
              ORDER BY RAND()
              LIMIT ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $curso_id, $limit);
    $stmt->execute();
    
    return $stmt->get_result();
}
?>

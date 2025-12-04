<?php
/**
 * Script de Ejemplo - Cómo Usar la Plataforma
 * 
 * Este archivo muestra ejemplos de cómo trabajar con la API
 * y realizar operaciones comunes en el sistema
 */

require_once(__DIR__ . '/config/db.php');

// ==================================================
// EJEMPLO 1: Obtener todos los cursos
// ==================================================
function ejemplo_obtener_todos_cursos() {
    global $conn;
    
    echo "=== EJEMPLO 1: Obtener Todos los Cursos ===\n\n";
    
    $result = get_all_cursos(5, 0); // 5 cursos, página 1
    
    echo "Cursos obtenidos:\n";
    while ($curso = $result->fetch_assoc()) {
        echo "- " . $curso['titulo'] . " (" . $curso['categoria_nombre'] . ")\n";
    }
    echo "\n";
}

// ==================================================
// EJEMPLO 2: Obtener un curso por slug
// ==================================================
function ejemplo_obtener_curso_por_slug($slug) {
    echo "=== EJEMPLO 2: Obtener Curso por Slug ===\n\n";
    
    $curso = get_curso_by_slug($slug);
    
    if ($curso) {
        echo "Título: " . $curso['titulo'] . "\n";
        echo "Categoría: " . $curso['categoria_nombre'] . "\n";
        echo "Instructor: " . $curso['instructor'] . "\n";
        echo "Precio: $" . $curso['precio'] . "\n";
        echo "Estudiantes: " . $curso['estudiantes'] . "\n";
        echo "Etiquetas: " . $curso['etiquetas'] . "\n";
    } else {
        echo "Curso no encontrado\n";
    }
    echo "\n";
}

// ==================================================
// EJEMPLO 3: Obtener todas las etiquetas
// ==================================================
function ejemplo_obtener_etiquetas() {
    global $conn;
    
    echo "=== EJEMPLO 3: Obtener Todas las Etiquetas ===\n\n";
    
    $result = get_all_etiquetas();
    
    echo "Etiquetas disponibles:\n";
    while ($etiqueta = $result->fetch_assoc()) {
        echo "- " . $etiqueta['nombre'] . " (" . $etiqueta['cantidad_cursos'] . " cursos)\n";
    }
    echo "\n";
}

// ==================================================
// EJEMPLO 4: Obtener todas las categorías
// ==================================================
function ejemplo_obtener_categorias() {
    global $conn;
    
    echo "=== EJEMPLO 4: Obtener Todas las Categorías ===\n\n";
    
    $result = get_all_categorias();
    
    echo "Categorías disponibles:\n";
    while ($categoria = $result->fetch_assoc()) {
        echo "- " . $categoria['nombre'] . " (" . $categoria['cantidad_cursos'] . " cursos)\n";
    }
    echo "\n";
}

// ==================================================
// EJEMPLO 5: Insertar un nuevo curso
// ==================================================
function ejemplo_insertar_curso() {
    global $conn;
    
    echo "=== EJEMPLO 5: Insertar un Nuevo Curso ===\n\n";
    
    // Obtener ID de la primera categoría
    $cat_result = $conn->query("SELECT id FROM categorias LIMIT 1");
    $cat = $cat_result->fetch_assoc();
    $categoria_id = $cat['id'];
    
    $titulo = "Curso de Ejemplo " . date('Y-m-d H:i:s');
    $slug = strtolower(str_replace(' ', '-', $titulo));
    $descripcion = "Este es un curso de ejemplo creado programáticamente";
    $instructor = "Instructor Demo";
    $duracion = 300;
    
    $query = "INSERT INTO cursos (titulo, descripcion, contenido, duracion, nivel, instructor, 
              instructor_email, precio, categoria_id, slug, estado) 
              VALUES (?, ?, ?, ?, 'principiante', ?, 'instructor@demo.com', 29.99, ?, ?, 'activo')";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssisis", $titulo, $descripcion, $descripcion, $duracion, 
                     $instructor, $categoria_id, $slug);
    
    if ($stmt->execute()) {
        $nuevo_id = $conn->insert_id;
        echo "✓ Curso creado exitosamente\n";
        echo "ID del curso: " . $nuevo_id . "\n";
        echo "Slug: " . $slug . "\n";
        echo "\n";
        return $nuevo_id;
    } else {
        echo "✗ Error al crear curso: " . $stmt->error . "\n\n";
        return null;
    }
}

// ==================================================
// EJEMPLO 6: Asociar etiquetas a un curso
// ==================================================
function ejemplo_asociar_etiquetas($curso_id) {
    global $conn;
    
    echo "=== EJEMPLO 6: Asociar Etiquetas a Curso ===\n\n";
    
    // Obtener las primeras 3 etiquetas
    $etiquetas_result = $conn->query("SELECT id FROM etiquetas LIMIT 3");
    
    $asociadas = 0;
    while ($etiqueta = $etiquetas_result->fetch_assoc()) {
        $query = "INSERT INTO curso_etiqueta (curso_id, etiqueta_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $curso_id, $etiqueta['id']);
        
        if ($stmt->execute()) {
            $asociadas++;
        }
    }
    
    echo "✓ " . $asociadas . " etiquetas asociadas al curso\n\n";
}

// ==================================================
// EJEMPLO 7: Actualizar un curso
// ==================================================
function ejemplo_actualizar_curso($curso_id) {
    global $conn;
    
    echo "=== EJEMPLO 7: Actualizar un Curso ===\n\n";
    
    $nuevo_precio = 49.99;
    $nuevos_estudiantes = 150;
    
    $query = "UPDATE cursos SET precio = ?, estudiantes = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("dii", $nuevo_precio, $nuevos_estudiantes, $curso_id);
    
    if ($stmt->execute()) {
        echo "✓ Curso actualizado\n";
        echo "Nuevo precio: $" . $nuevo_precio . "\n";
        echo "Estudiantes: " . $nuevos_estudiantes . "\n";
        echo "\n";
    } else {
        echo "✗ Error: " . $stmt->error . "\n\n";
    }
}

// ==================================================
// EJEMPLO 8: Buscar cursos por término
// ==================================================
function ejemplo_buscar_cursos($termino) {
    global $conn;
    
    echo "=== EJEMPLO 8: Buscar Cursos ===\n\n";
    echo "Buscando: '" . $termino . "'\n\n";
    
    $query = "SELECT c.*, cat.nombre as categoria_nombre 
              FROM cursos c 
              LEFT JOIN categorias cat ON c.categoria_id = cat.id 
              WHERE c.titulo LIKE ? OR c.descripcion LIKE ?
              AND c.estado = 'activo'";
    
    $termino_busqueda = '%' . $termino . '%';
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $termino_busqueda, $termino_busqueda);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Se encontraron " . $result->num_rows . " cursos:\n";
        while ($curso = $result->fetch_assoc()) {
            echo "- " . $curso['titulo'] . " (" . $curso['categoria_nombre'] . ")\n";
        }
    } else {
        echo "No se encontraron cursos\n";
    }
    echo "\n";
}

// ==================================================
// EJEMPLO 9: Obtener cursos por categoría
// ==================================================
function ejemplo_obtener_por_categoria($categoria_id) {
    global $conn;
    
    echo "=== EJEMPLO 9: Obtener Cursos por Categoría ===\n\n";
    
    $query = "SELECT c.*, cat.nombre as categoria_nombre 
              FROM cursos c 
              LEFT JOIN categorias cat ON c.categoria_id = cat.id 
              WHERE c.categoria_id = ? AND c.estado = 'activo'";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $categoria_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Cursos en esta categoría: " . $result->num_rows . "\n";
        while ($curso = $result->fetch_assoc()) {
            echo "- " . $curso['titulo'] . "\n";
        }
    } else {
        echo "No hay cursos en esta categoría\n";
    }
    echo "\n";
}

// ==================================================
// EJEMPLO 10: Estadísticas de la plataforma
// ==================================================
function ejemplo_estadisticas() {
    global $conn;
    
    echo "=== EJEMPLO 10: Estadísticas de la Plataforma ===\n\n";
    
    $query = "SELECT 
        (SELECT COUNT(*) FROM cursos WHERE estado = 'activo') as cursos_activos,
        (SELECT COUNT(*) FROM cursos) as cursos_totales,
        (SELECT COUNT(*) FROM categorias) as total_categorias,
        (SELECT COUNT(*) FROM etiquetas) as total_etiquetas,
        (SELECT COUNT(*) FROM usuarios) as total_usuarios,
        (SELECT SUM(estudiantes) FROM cursos) as total_estudiantes,
        (SELECT AVG(calificacion) FROM cursos) as calificacion_promedio";
    
    $result = $conn->query($query);
    $stats = $result->fetch_assoc();
    
    echo "Cursos Activos: " . $stats['cursos_activos'] . "\n";
    echo "Cursos Totales: " . $stats['cursos_totales'] . "\n";
    echo "Categorías: " . $stats['total_categorias'] . "\n";
    echo "Etiquetas: " . $stats['total_etiquetas'] . "\n";
    echo "Usuarios Registrados: " . $stats['total_usuarios'] . "\n";
    echo "Estudiantes Totales: " . $stats['total_estudiantes'] . "\n";
    echo "Calificación Promedio: " . round($stats['calificacion_promedio'], 2) . "/5\n";
    echo "\n";
}

// ==================================================
// EJECUTAR EJEMPLOS
// ==================================================

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║         EJEMPLOS DE USO - PLATAFORMA DE CURSOS              ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Ejecutar ejemplos
ejemplo_obtener_todos_cursos();
ejemplo_obtener_curso_por_slug('introduccion-diseno-grafico');
ejemplo_obtener_etiquetas();
ejemplo_obtener_categorias();

// Insertar un nuevo curso
$nuevo_id = ejemplo_insertar_curso();

if ($nuevo_id) {
    // Asociar etiquetas al nuevo curso
    ejemplo_asociar_etiquetas($nuevo_id);
    
    // Actualizar el curso
    ejemplo_actualizar_curso($nuevo_id);
}

// Buscar cursos
ejemplo_buscar_cursos('diseño');

// Obtener por categoría
ejemplo_obtener_por_categoria(1);

// Mostrar estadísticas
ejemplo_estadisticas();

echo "═══════════════════════════════════════════════════════════════════\n";
echo "¡Ejemplos completados!\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

?>

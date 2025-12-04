<?php
/**
 * Dashboard - Página principal del admin
 */

$totalCursos = count_total_cursos();

// Obtener estadísticas
$stats_query = "SELECT 
    (SELECT COUNT(*) FROM cursos WHERE estado = 'activo') as cursos_activos,
    (SELECT COUNT(*) FROM categorias) as total_categorias,
    (SELECT COUNT(*) FROM etiquetas) as total_etiquetas,
    (SELECT COUNT(*) FROM usuarios) as total_usuarios,
    (SELECT SUM(estudiantes) FROM cursos) as total_estudiantes";

$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Últimos cursos
$recent_query = "SELECT c.*, cat.nombre as categoria_nombre 
                FROM cursos c 
                LEFT JOIN categorias cat ON c.categoria_id = cat.id 
                ORDER BY c.fecha_creacion DESC 
                LIMIT 5";
$recent_result = $conn->query($recent_query);
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px;">
        <div style="font-size: 12px; opacity: 0.9; margin-bottom: 10px;">CURSOS ACTIVOS</div>
        <div style="font-size: 32px; font-weight: bold;"><?php echo $stats['cursos_activos']; ?></div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 8px;">
        <div style="font-size: 12px; opacity: 0.9; margin-bottom: 10px;">ESTUDIANTES TOTALES</div>
        <div style="font-size: 32px; font-weight: bold;"><?php echo $stats['total_estudiantes'] ?? 0; ?></div>
    </div>
    
    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 8px;">
        <div style="font-size: 12px; opacity: 0.9; margin-bottom: 10px;">CATEGORÍAS</div>
        <div style="font-size: 32px; font-weight: bold;"><?php echo $stats['total_categorias']; ?></div>
    </div>
    
    <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 20px; border-radius: 8px;">
        <div style="font-size: 12px; opacity: 0.9; margin-bottom: 10px;">ETIQUETAS</div>
        <div style="font-size: 32px; font-weight: bold;"><?php echo $stats['total_etiquetas']; ?></div>
    </div>
</div>

<h3 style="margin-top: 40px; margin-bottom: 20px;">Últimos Cursos Agregados</h3>
<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Estudiantes</th>
                <th>Calificación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($curso = $recent_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($curso['titulo']); ?></td>
                <td><?php echo $curso['categoria_nombre']; ?></td>
                <td><?php echo $curso['estudiantes']; ?></td>
                <td><?php echo $curso['calificacion']; ?>/5</td>
                <td>
                    <span style="background: <?php echo $curso['estado'] === 'activo' ? '#d4edda' : '#f8d7da'; ?>; 
                                 color: <?php echo $curso['estado'] === 'activo' ? '#155724' : '#721c24'; ?>; 
                                 padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                        <?php echo ucfirst($curso['estado']); ?>
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-edit" onclick="alert('Funcionalidad próxima: Editar curso')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-delete" onclick="alert('Funcionalidad próxima: Eliminar curso')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

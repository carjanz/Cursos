<?php
/**
 * Gestionar Cursos
 */

// Obtener todos los cursos
$query = "SELECT c.*, cat.nombre as categoria_nombre 
          FROM cursos c 
          LEFT JOIN categorias cat ON c.categoria_id = cat.id 
          ORDER BY c.fecha_creacion DESC";
$result = $conn->query($query);
?>

<div style="margin-bottom: 20px;">
    <a href="#" onclick="alert('Funcionalidad próxima: Crear nuevo curso')" class="btn-primary">
        <i class="fas fa-plus"></i> Nuevo Curso
    </a>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Instructor</th>
                <th>Duración</th>
                <th>Precio</th>
                <th>Estudiantes</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($curso = $result->fetch_assoc()): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($curso['titulo']); ?></strong></td>
                <td><?php echo $curso['categoria_nombre']; ?></td>
                <td><?php echo htmlspecialchars($curso['instructor']); ?></td>
                <td><?php echo $curso['duracion']; ?> min</td>
                <td>$<?php echo number_format($curso['precio'], 2); ?></td>
                <td><?php echo $curso['estudiantes']; ?></td>
                <td>
                    <span style="background: <?php echo $curso['estado'] === 'activo' ? '#d4edda' : '#f8d7da'; ?>; 
                                 color: <?php echo $curso['estado'] === 'activo' ? '#155724' : '#721c24'; ?>; 
                                 padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                        <?php echo ucfirst($curso['estado']); ?>
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="<?php echo SITE_URL; ?>course.php?slug=<?php echo $curso['slug']; ?>" class="btn-edit" target="_blank" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn-edit" onclick="alert('Funcionalidad próxima: Editar')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-delete" onclick="alert('Funcionalidad próxima: Eliminar')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

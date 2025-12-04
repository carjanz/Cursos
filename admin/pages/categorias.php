<?php
/**
 * Gestionar Categorías
 */

$categorias_result = get_all_categorias();
?>

<div style="margin-bottom: 20px;">
    <button onclick="alert('Funcionalidad próxima: Crear nueva categoría')" class="btn-primary">
        <i class="fas fa-plus"></i> Nueva Categoría
    </button>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Slug</th>
                <th>Descripción</th>
                <th>Color</th>
                <th>Cursos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cat = $categorias_result->fetch_assoc()): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($cat['nombre']); ?></strong></td>
                <td><code><?php echo $cat['slug']; ?></code></td>
                <td><?php echo htmlspecialchars($cat['descripcion'] ?? ''); ?></td>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 30px; height: 30px; background: <?php echo $cat['color']; ?>; border-radius: 4px;"></div>
                        <code><?php echo $cat['color']; ?></code>
                    </div>
                </td>
                <td><?php echo $cat['cantidad_cursos']; ?></td>
                <td>
                    <div class="action-buttons">
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

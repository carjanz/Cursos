<?php
/**
 * Gestionar Etiquetas
 */

$etiquetas_result = get_all_etiquetas();
?>

<div style="margin-bottom: 20px;">
    <button onclick="alert('Funcionalidad próxima: Crear nueva etiqueta')" class="btn-primary">
        <i class="fas fa-plus"></i> Nueva Etiqueta
    </button>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Slug</th>
                <th>Descripción</th>
                <th>Cursos Asociados</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($etiqueta = $etiquetas_result->fetch_assoc()): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($etiqueta['nombre']); ?></strong></td>
                <td><code><?php echo $etiqueta['slug']; ?></code></td>
                <td><?php echo htmlspecialchars($etiqueta['descripcion'] ?? ''); ?></td>
                <td>
                    <span style="background: var(--light); padding: 4px 12px; border-radius: 20px;">
                        <?php echo $etiqueta['cantidad_cursos']; ?>
                    </span>
                </td>
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

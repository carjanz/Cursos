<?php
/**
 * Gestionar Usuarios
 */

$query = "SELECT * FROM usuarios ORDER BY fecha_registro DESC";
$result = $conn->query($query);
?>

<div style="margin-bottom: 20px;">
    <button onclick="alert('Funcionalidad próxima: Crear nuevo usuario')" class="btn-primary">
        <i class="fas fa-plus"></i> Nuevo Usuario
    </button>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Fecha de Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result && $result->num_rows > 0) {
                while ($usuario = $result->fetch_assoc()): 
            ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong></td>
                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                <td>
                    <span style="background: var(--light); padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                        <?php echo ucfirst($usuario['rol']); ?>
                    </span>
                </td>
                <td>
                    <span style="background: <?php echo $usuario['estado'] === 'activo' ? '#d4edda' : '#f8d7da'; ?>; 
                                 color: <?php echo $usuario['estado'] === 'activo' ? '#155724' : '#721c24'; ?>; 
                                 padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                        <?php echo ucfirst($usuario['estado']); ?>
                    </span>
                </td>
                <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
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
            <?php 
                endwhile;
            } else {
                echo '<tr><td colspan="6" style="text-align: center; padding: 40px;">Sin usuarios registrados</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

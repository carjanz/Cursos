<?php
require_once(__DIR__ . '/../config/db.php');

// Función para verificar si el usuario está autenticado (simple para esta demo)
function check_admin_access() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    // Para esta versión de demostración, permitimos acceso directo
    // En producción, implementar sistema de autenticación real
    $_SESSION['admin_logged_in'] = true;
}

check_admin_access();

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <i class="fas fa-cogs"></i>
                <span>Admin</span>
            </div>
            
            <ul class="admin-menu">
                <li><a href="index.php?page=dashboard" class="<?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a></li>
                <li><a href="index.php?page=cursos" class="<?php echo $page === 'cursos' ? 'active' : ''; ?>">
                    <i class="fas fa-book"></i> Gestionar Cursos
                </a></li>
                <li><a href="index.php?page=categorias" class="<?php echo $page === 'categorias' ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i> Categorías
                </a></li>
                <li><a href="index.php?page=etiquetas" class="<?php echo $page === 'etiquetas' ? 'active' : ''; ?>">
                    <i class="fas fa-label"></i> Etiquetas
                </a></li>
                <li><a href="index.php?page=usuarios" class="<?php echo $page === 'usuarios' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Usuarios
                </a></li>
                <li><a href="<?php echo SITE_URL; ?>" target="_blank">
                    <i class="fas fa-globe"></i> Ver Sitio
                </a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">
                    <?php 
                    $titles = [
                        'dashboard' => 'Dashboard',
                        'cursos' => 'Gestionar Cursos',
                        'categorias' => 'Categorías',
                        'etiquetas' => 'Etiquetas',
                        'usuarios' => 'Usuarios'
                    ];
                    echo $titles[$page] ?? 'Panel de Administración';
                    ?>
                </h1>
                <div>
                    <span style="color: var(--gray); margin-right: 20px;">Bienvenido Admin</span>
                    <a href="logout.php" class="btn-primary btn-small">Cerrar Sesión</a>
                </div>
            </div>

            <div class="admin-content">
                <?php
                // Incluir página según lo seleccionado
                $pages = [
                    'dashboard' => 'pages/dashboard.php',
                    'cursos' => 'pages/cursos.php',
                    'categorias' => 'pages/categorias.php',
                    'etiquetas' => 'pages/etiquetas.php',
                    'usuarios' => 'pages/usuarios.php'
                ];

                $page_file = isset($pages[$page]) ? $pages[$page] : $pages['dashboard'];
                
                if (file_exists($page_file)) {
                    include($page_file);
                } else {
                    echo '<p>Página no encontrada</p>';
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>

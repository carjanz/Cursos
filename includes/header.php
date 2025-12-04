<?php
// Incluir configuración
require_once(__DIR__ . '/../config/db.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' | ' : ''; echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="navbar-brand">
                    <a href="<?php echo SITE_URL; ?>" class="logo">
                        <i class="fas fa-graduation-cap"></i>
                        <span><?php echo SITE_NAME; ?></span>
                    </a>
                </div>
                
                <div class="navbar-menu">
                    <ul class="nav-links">
                        <li><a href="<?php echo SITE_URL; ?>">Inicio</a></li>
                        <li><a href="<?php echo SITE_URL; ?>?explore=true">Explorar</a></li>
                        <li><a href="#" onclick="alert('Funcionalidad próxima')">Mis Cursos</a></li>
                        <li><a href="#" onclick="alert('Funcionalidad próxima')">Sobre Nosotros</a></li>
                    </ul>
                </div>
                
                <div class="navbar-actions">
                    <button class="btn-icon" id="search-toggle" title="Buscar">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="<?php echo SITE_URL; ?>admin/" class="btn-primary btn-small">Admin</a>
                </div>
                
                <button class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Barra de búsqueda -->
    <div class="search-bar" id="search-bar">
        <div class="container">
            <div class="search-input-group">
                <input type="text" id="search-input" placeholder="Busca cursos..." class="search-input">
                <button class="btn-icon"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <main class="main-content">

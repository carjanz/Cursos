<?php
$page_title = 'Inicio';
include('includes/header.php');

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Aprende Nuevas Habilidades</h1>
            <p>Descubre nuestros cursos de diseño, programación, fotografía y más. Enseñados por expertos de la industria.</p>
            <div class="hero-buttons">
                <button class="btn-hero" onclick="document.querySelector('.filters-section').scrollIntoView({behavior: 'smooth'})">Explorar Cursos</button>
                <button class="btn-hero" style="background: transparent; color: white; border: 2px solid white;" onclick="alert('Funcionalidad próxima')">Crear Cuenta</button>
            </div>
        </div>
    </div>
</section>

<!-- Filtros Section -->
<section class="filters-section">
    <div class="container">
        <h3 class="filters-title">Filtrar por Etiquetas</h3>
        <div class="filters-container">
            <div class="filter-group"></div>
            <button class="btn-clear-filters" onclick="clearFilters()">Limpiar Filtros</button>
        </div>
    </div>
</section>

<!-- Cursos Section -->
<section class="courses-section">
    <div class="container">
        <h2 class="section-title">
            <?php 
            if (!empty($search)) {
                echo 'Resultados para: <strong>' . htmlspecialchars($search) . '</strong>';
            } else {
                echo 'Nuestros Cursos';
            }
            ?>
        </h2>
        
        <div class="courses-grid"></div>
        
        <div class="pagination" style="display: flex; justify-content: center; gap: 10px; margin-top: 30px; flex-wrap: wrap;">
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>

<?php
$page_title = 'Detalles del Curso';
include('includes/header.php');

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$curso = $slug ? get_curso_by_slug($slug) : null;

if (!$curso) {
    header('HTTP/1.0 404 Not Found');
    ?>
    <section class="courses-section">
        <div class="container">
            <div style="text-align: center; padding: 60px 20px;">
                <h1>Curso no encontrado</h1>
                <p>Lo sentimos, el curso que buscas no existe.</p>
                <a href="/" class="btn-primary">Volver a Inicio</a>
            </div>
        </div>
    </section>
    <?php
    include('includes/footer.php');
    exit;
}

// Obtener cursos relacionados
$relacionados = get_cursos_relacionados($curso['id'], 4);
?>

<section class="course-detail">
    <div class="container">
        <!-- Header del Curso -->
        <div class="course-header">
            <div class="course-header-image">
                <i class="fas fa-<?php 
                    $categoryIcons = [
                        'Diseño' => 'palette',
                        'Fotografía' => 'camera',
                        'Programación' => 'code',
                        'Marketing Digital' => 'trending-up',
                        'Vídeo' => 'film',
                        'Animación' => 'zap'
                    ];
                    echo $categoryIcons[$curso['categoria_nombre']] ?? 'book';
                ?>"></i>
            </div>

            <div class="course-header-info">
                <span class="course-category"><?php echo $curso['categoria_nombre']; ?></span>
                
                <h1><?php echo htmlspecialchars($curso['titulo']); ?></h1>
                
                <div class="course-rating-large">
                    <div>
                        <div class="course-rating-text">★★★★★ <?php echo $curso['calificacion']; ?>/5</div>
                        <span class="course-students"><?php echo $curso['estudiantes']; ?> estudiantes</span>
                    </div>
                </div>

                <p><?php echo htmlspecialchars($curso['descripcion']); ?></p>

                <div class="course-meta">
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <div class="meta-label">Duración</div>
                        <div class="meta-value"><?php echo $curso['duracion']; ?> minutos</div>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-chart-line"></i>
                        <div class="meta-label">Nivel</div>
                        <div class="meta-value"><?php echo ucfirst($curso['nivel']); ?></div>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-certificate"></i>
                        <div class="meta-label">Certificado</div>
                        <div class="meta-value">Sí</div>
                    </div>
                </div>

                <div class="course-actions">
                    <button class="btn-primary btn-large" onclick="alert('Funcionalidad próxima: Inscribirse en el curso')">
                        <i class="fas fa-play-circle"></i> Inscribirse Ahora
                    </button>
                    <button class="btn-secondary btn-large" onclick="alert('Funcionalidad próxima: Agregar a favoritos')">
                        <i class="fas fa-heart"></i> Favorito
                    </button>
                </div>

                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                    <strong>Instructor:</strong> <?php echo htmlspecialchars($curso['instructor']); ?><br>
                    <strong>Email:</strong> <?php echo htmlspecialchars($curso['instructor_email']); ?>
                </div>
            </div>
        </div>

        <!-- Descripción del Curso -->
        <div class="course-description">
            <h2>Descripción Completa</h2>
            <p><?php echo nl2br(htmlspecialchars($curso['contenido'] ?? $curso['descripcion'])); ?></p>
        </div>

        <!-- Contenido del Curso -->
        <div class="course-content-section">
            <h3>¿Qué Aprenderás?</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div>
                    <i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i>
                    Conceptos fundamentales del <?php echo strtolower($curso['categoria_nombre']); ?>
                </div>
                <div>
                    <i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i>
                    Técnicas prácticas aplicables
                </div>
                <div>
                    <i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i>
                    Proyecto práctico final
                </div>
                <div>
                    <i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i>
                    Acceso de por vida al material
                </div>
            </div>
        </div>

        <!-- Etiquetas -->
        <?php if ($curso['etiquetas']): ?>
        <div class="course-content-section">
            <h3>Etiquetas</h3>
            <div class="course-tags" style="margin-top: 0;">
                <?php 
                $etiquetas = explode(',', $curso['etiquetas']);
                foreach ($etiquetas as $etiqueta) {
                    echo '<span class="course-tag" style="background: var(--primary); color: white; padding: 8px 16px;">' . trim($etiqueta) . '</span>';
                }
                ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Requisitos -->
        <div class="course-content-section">
            <h3>Requisitos</h3>
            <ul style="padding-left: 20px;">
                <li>Computadora con acceso a internet</li>
                <li>Ganas de aprender y mejorar</li>
                <li>Dedicación y práctica constante</li>
            </ul>
        </div>

        <!-- Cursos Relacionados -->
        <?php if ($relacionados->num_rows > 0): ?>
        <div style="margin-top: 60px;">
            <h2 style="margin-bottom: 30px;">Cursos Relacionados</h2>
            <div class="courses-grid">
                <?php while ($related = $relacionados->fetch_assoc()): ?>
                <div class="course-card">
                    <div class="course-image">
                        <i class="fas fa-<?php 
                            $categoryIcons = [
                                'Diseño' => 'palette',
                                'Fotografía' => 'camera',
                                'Programación' => 'code',
                                'Marketing Digital' => 'trending-up',
                                'Vídeo' => 'film',
                                'Animación' => 'zap'
                            ];
                            echo $categoryIcons[$related['categoria_nombre']] ?? 'book';
                        ?>"></i>
                    </div>
                    <div class="course-body">
                        <span class="course-category"><?php echo $related['categoria_nombre']; ?></span>
                        <h3 class="course-title"><?php echo htmlspecialchars($related['titulo']); ?></h3>
                        <p class="course-instructor"><i class="fas fa-user-circle"></i> <?php echo $related['instructor']; ?></p>
                        
                        <div class="course-info">
                            <div class="course-stats">
                                <div class="course-stat">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo $related['duracion']; ?> min</span>
                                </div>
                                <div class="course-stat">
                                    <i class="fas fa-users"></i>
                                    <span><?php echo $related['estudiantes']; ?></span>
                                </div>
                            </div>
                            <div class="course-rating">
                                <span class="stars">★★★★★</span>
                                <span><?php echo $related['calificacion']; ?></span>
                            </div>
                        </div>

                        <div class="course-footer">
                            <span class="course-price">$<?php echo number_format($related['precio'], 2); ?></span>
                            <a href="course.php?slug=<?php echo $related['slug']; ?>" class="btn-primary btn-small">Ver Curso</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include('includes/footer.php'); ?>

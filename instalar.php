<?php
/**
 * Script de instalación - Crea la base de datos y tablas
 */

// Variables de configuración
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'cursos_db'; // ✅ Debe coincidir con config/db.php y schema.sql

// Crear conexión sin seleccionar BD
$conn = new mysqli($db_host, $db_user, $db_pass);

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

echo "<h2>🔧 Instalación de CursoHub</h2>";

// 1. Crear base de datos
echo "<p>1. Creando base de datos '{$db_name}'...</p>";
$sql = "CREATE DATABASE IF NOT EXISTS {$db_name} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "✅ Base de datos creada/verificada<br>";
} else {
    die("❌ Error al crear BD: " . $conn->error);
}

// 2. Seleccionar la BD
if (!$conn->select_db($db_name)) {
    die("❌ Error al seleccionar BD: " . $conn->error);
}

$conn->set_charset("utf8mb4");

// 3. Crear tablas
echo "<p>2. Creando tablas...</p>";

$tables = [
    "CREATE TABLE IF NOT EXISTS categorias (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nombre VARCHAR(100) NOT NULL UNIQUE,
        slug VARCHAR(100) NOT NULL UNIQUE,
        descripcion TEXT,
        icono VARCHAR(50),
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS etiquetas (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nombre VARCHAR(100) NOT NULL UNIQUE,
        slug VARCHAR(100) NOT NULL UNIQUE,
        descripcion TEXT,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS cursos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        titulo VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        descripcion LONGTEXT,
        categoria_id INT NOT NULL,
        instructor VARCHAR(100),
        imagen_url VARCHAR(255),
        video_intro VARCHAR(255),
        duracion_horas INT,
        nivel VARCHAR(50),
        precio DECIMAL(10,2),
        estudiantes_count INT DEFAULT 0,
        rating DECIMAL(3,2) DEFAULT 0,
        estado VARCHAR(50) DEFAULT 'activo',
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (categoria_id) REFERENCES categorias(id),
        INDEX(slug),
        INDEX(estado),
        INDEX(categoria_id)
    )",
    
    "CREATE TABLE IF NOT EXISTS curso_etiqueta (
        id INT PRIMARY KEY AUTO_INCREMENT,
        curso_id INT NOT NULL,
        etiqueta_id INT NOT NULL,
        FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
        FOREIGN KEY (etiqueta_id) REFERENCES etiquetas(id) ON DELETE CASCADE,
        UNIQUE KEY unique_curso_etiqueta (curso_id, etiqueta_id)
    )",
    
    "CREATE TABLE IF NOT EXISTS usuarios (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255),
        rol VARCHAR(50) DEFAULT 'estudiante',
        estado VARCHAR(50) DEFAULT 'activo',
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS inscripciones (
        id INT PRIMARY KEY AUTO_INCREMENT,
        usuario_id INT NOT NULL,
        curso_id INT NOT NULL,
        progreso INT DEFAULT 0,
        fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
        FOREIGN KEY (curso_id) REFERENCES cursos(id)
    )",
    
    "CREATE TABLE IF NOT EXISTS lecciones (
        id INT PRIMARY KEY AUTO_INCREMENT,
        curso_id INT NOT NULL,
        titulo VARCHAR(255),
        contenido LONGTEXT,
        video_url VARCHAR(255),
        orden INT,
        FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
    )"
];

foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "✅ Tabla creada<br>";
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }
}

// 4. Insertar datos de ejemplo
echo "<p>3. Insertando datos de ejemplo...</p>";

// Categorías
$categorias = [
    ['Diseño', 'diseno', 'Cursos de diseño gráfico y digital', 'fa-palette'],
    ['Fotografía', 'fotografia', 'Aprende técnicas de fotografía profesional', 'fa-camera'],
    ['Programación', 'programacion', 'Desarrollo web y software', 'fa-code'],
    ['Marketing Digital', 'marketing-digital', 'Estrategias de marketing online', 'fa-chart-line'],
    ['Vídeo', 'video', 'Edición de vídeo profesional', 'fa-video'],
    ['Animación', 'animacion', 'Animación 2D y 3D', 'fa-circle-notch']
];

foreach ($categorias as $cat) {
    $stmt = $conn->prepare("INSERT IGNORE INTO categorias (nombre, slug, descripcion, icono) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $cat[0], $cat[1], $cat[2], $cat[3]);
    $stmt->execute();
}
echo "✅ Categorías insertadas<br>";

// Etiquetas
$etiquetas = [
    ['Principiante', 'principiante', 'Curso para principiantes'],
    ['Intermedio', 'intermedio', 'Nivel intermedio'],
    ['Avanzado', 'avanzado', 'Nivel avanzado'],
    ['Proyecto Práctico', 'proyecto-practico', 'Incluye proyecto práctico'],
    ['Certificado', 'certificado', 'Obtén certificado al terminar'],
    ['En Vivo', 'en-vivo', 'Clases en vivo'],
    ['Grabado', 'grabado', 'Contenido grabado'],
    ['Trending', 'trending', 'Tendencia actual'],
    ['Software', 'software', 'Sobre herramientas software'],
    ['Diseño Gráfico', 'diseno-grafico', 'Diseño gráfico específico']
];

foreach ($etiquetas as $etiq) {
    $stmt = $conn->prepare("INSERT IGNORE INTO etiquetas (nombre, slug, descripcion) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $etiq[0], $etiq[1], $etiq[2]);
    $stmt->execute();
}
echo "✅ Etiquetas insertadas<br>";

// Cursos
$cursos = [
    ['Introducción al Diseño Gráfico', 'introduccion-diseno-grafico', 'Aprende los fundamentos del diseño gráfico...', 1, 'Juan García', 'https://via.placeholder.com/300x200?text=Diseño+Gráfico', '', 20, 'Principiante', 49.99],
    ['Fotografía Profesional', 'fotografia-profesional', 'Domina la fotografía digital...', 2, 'María López', 'https://via.placeholder.com/300x200?text=Fotografía', '', 30, 'Intermedio', 79.99],
    ['JavaScript Avanzado', 'javascript-avanzado', 'Aprende JavaScript moderno...', 3, 'Carlos Ruiz', 'https://via.placeholder.com/300x200?text=JavaScript', '', 40, 'Avanzado', 89.99],
    ['Marketing Digital Básico', 'marketing-digital-basico', 'Estrategias de marketing online...', 4, 'Ana Martín', 'https://via.placeholder.com/300x200?text=Marketing', '', 25, 'Principiante', 59.99],
    ['Edición de Vídeo con Adobe', 'edicion-video-adobe', 'Maneja Adobe Premiere Pro...', 5, 'Pedro García', 'https://via.placeholder.com/300x200?text=Video', '', 35, 'Intermedio', 69.99],
    ['Animación 3D con Blender', 'animacion-3d-blender', 'Crea animaciones 3D profesionales...', 6, 'Laura Fernández', 'https://via.placeholder.com/300x200?text=Animación', '', 50, 'Avanzado', 99.99],
    ['UI/UX Design Masterclass', 'uiux-design-masterclass', 'Diseña interfaces modernas...', 1, 'Diego López', 'https://via.placeholder.com/300x200?text=UI+UX', '', 28, 'Intermedio', 79.99],
    ['Composición Fotográfica', 'composicion-fotografica', 'Técnicas de composición avanzada...', 2, 'Sofía García', 'https://via.placeholder.com/300x200?text=Composición', '', 15, 'Intermedio', 49.99],
    ['Python para Ciencia de Datos', 'python-ciencia-datos', 'Python y análisis de datos...', 3, 'Roberto Sánchez', 'https://via.placeholder.com/300x200?text=Python', '', 45, 'Avanzado', 99.99],
    ['SEO y Posicionamiento Web', 'seo-posicionamiento-web', 'Posiciona tu web en Google...', 4, 'Carmen López', 'https://via.placeholder.com/300x200?text=SEO', '', 20, 'Principiante', 59.99]
];

$cursor_etiquetas = [
    [1, 3], [1, 4], [2, 5],
    [2, 3], [3, 6], [3, 7],
    [4, 1], [4, 8], [5, 2],
    [5, 9], [6, 3], [6, 10],
    [7, 4], [7, 1], [8, 2],
    [8, 5], [9, 6], [9, 3],
    [10, 1], [10, 9]
];

foreach ($cursos as $curso) {
    $stmt = $conn->prepare("INSERT IGNORE INTO cursos (titulo, slug, descripcion, categoria_id, instructor, imagen_url, video_intro, duracion_horas, nivel, precio) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisssisi", ...$curso);
    $stmt->execute();
}
echo "✅ Cursos insertados<br>";

// Asociar etiquetas a cursos
foreach ($cursor_etiquetas as $rel) {
    $stmt = $conn->prepare("INSERT IGNORE INTO curso_etiqueta (curso_id, etiqueta_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $rel[0], $rel[1]);
    $stmt->execute();
}
echo "✅ Relaciones curso-etiqueta creadas<br>";

echo "<h3>✅ ¡Instalación completada!</h3>";
echo "<p><strong>Pasos siguientes:</strong></p>";
echo "<ul>";
echo "<li>Abre tu navegador en: <strong>http://localhost/Centro/</strong></li>";
echo "<li>Prueba la API en: <strong>http://localhost/Centro/api_tester.html</strong></li>";
echo "<li>Accede al admin en: <strong>http://localhost/Centro/admin/</strong></li>";
echo "</ul>";

$conn->close();
?>

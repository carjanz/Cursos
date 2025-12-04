-- Base de datos para plataforma de cursos
-- Crear base de datos
CREATE DATABASE IF NOT EXISTS cursos_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cursos_db;

-- Tabla de categorías
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    slug VARCHAR(100) UNIQUE,
    color VARCHAR(7) DEFAULT '#FF6B6B',
    icono VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de etiquetas (tags)
CREATE TABLE IF NOT EXISTS etiquetas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de cursos
CREATE TABLE IF NOT EXISTS cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT NOT NULL,
    contenido LONGTEXT,
    imagen VARCHAR(255),
    duracion INT COMMENT 'Duración en minutos',
    nivel ENUM('principiante', 'intermedio', 'avanzado') DEFAULT 'principiante',
    instructor VARCHAR(150),
    instructor_email VARCHAR(150),
    precio DECIMAL(10, 2) DEFAULT 0,
    estudiantes INT DEFAULT 0,
    calificacion DECIMAL(3, 2) DEFAULT 5.00,
    slug VARCHAR(200) UNIQUE,
    categoria_id INT,
    estado ENUM('activo', 'inactivo', 'borrador') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_categoria (categoria_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de relación cursos-etiquetas (Many-to-Many)
CREATE TABLE IF NOT EXISTS curso_etiqueta (
    curso_id INT NOT NULL,
    etiqueta_id INT NOT NULL,
    PRIMARY KEY (curso_id, etiqueta_id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    FOREIGN KEY (etiqueta_id) REFERENCES etiquetas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255),
    avatar VARCHAR(255),
    rol ENUM('estudiante', 'instructor', 'admin') DEFAULT 'estudiante',
    estado ENUM('activo', 'inactivo', 'pendiente') DEFAULT 'pendiente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de inscripciones
CREATE TABLE IF NOT EXISTS inscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    curso_id INT NOT NULL,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    progreso INT DEFAULT 0 COMMENT 'Porcentaje de progreso (0-100)',
    completado BOOLEAN DEFAULT FALSE,
    fecha_completado DATETIME,
    UNIQUE KEY unique_inscripcion (usuario_id, curso_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_curso (curso_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de lecciones
CREATE TABLE IF NOT EXISTS lecciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    contenido LONGTEXT,
    video_url VARCHAR(500),
    numero_leccion INT,
    duracion INT COMMENT 'Duración en minutos',
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    orden INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    INDEX idx_curso (curso_id),
    INDEX idx_orden (orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar categorías de ejemplo
INSERT INTO categorias (nombre, slug, descripcion, color, icono) VALUES
('Diseño', 'diseno', 'Diseño gráfico, UI/UX, ilustración', '#FF6B6B', 'palette'),
('Fotografía', 'fotografia', 'Fotografía, edición, composición', '#4ECDC4', 'camera'),
('Programación', 'programacion', 'Desarrollo web, backend, frontend', '#45B7D1', 'code'),
('Marketing Digital', 'marketing-digital', 'SEO, publicidad, redes sociales', '#96CEB4', 'trending-up'),
('Vídeo', 'video', 'Edición de vídeo, cinematografía, animación', '#FFEAA7', 'film'),
('Animación', 'animacion', 'Animación 2D, 3D, efectos especiales', '#DDA0DD', 'zap');

-- Insertar etiquetas de ejemplo
INSERT INTO etiquetas (nombre, slug, descripcion) VALUES
('Principiante', 'principiante', 'Ideal para comenzar sin experiencia'),
('Intermedio', 'intermedio', 'Para quienes tienen experiencia básica'),
('Avanzado', 'avanzado', 'Para profesionales experimentados'),
('Proyecto Práctico', 'proyecto-practico', 'Incluye proyecto práctico final'),
('Certificado', 'certificado', 'Obtén un certificado al completarlo'),
('En Vivo', 'en-vivo', 'Clases en vivo con instructor'),
('Grabado', 'grabado', 'Contenido pregrabado a tu ritmo'),
('Trending', 'trending', 'Cursos populares en tendencia'),
('Software', 'software', 'Uso de software específico'),
('Diseño Gráfico', 'diseno-grafico', 'Técnicas de diseño gráfico');

-- Insertar cursos de ejemplo
INSERT INTO cursos (titulo, descripcion, contenido, duracion, nivel, instructor, precio, estudiantes, calificacion, slug, categoria_id, estado) VALUES
('Introducción al Diseño Gráfico', 'Aprende los fundamentos del diseño gráfico desde cero', 'Este curso te enseñará los conceptos básicos del diseño gráfico, incluyendo tipografía, color, composición y más.', 480, 'principiante', 'Juan García', 29.99, 1250, 4.8, 'introduccion-diseno-grafico', 1, 'activo'),
('Fotografía Profesional Avanzada', 'Domina la fotografía profesional con técnicas avanzadas', 'Aprende a capturar momentos profesionales con técnicas de iluminación, composición y post-producción.', 720, 'avanzado', 'María López', 49.99, 856, 4.9, 'fotografia-profesional-avanzada', 2, 'activo'),
('Desarrollo Web Fullstack', 'Conviértete en desarrollador fullstack moderno', 'Aprende HTML, CSS, JavaScript, PHP y MySQL para crear aplicaciones web profesionales.', 1200, 'intermedio', 'Carlos Rodríguez', 79.99, 2105, 4.7, 'desarrollo-web-fullstack', 3, 'activo'),
('Social Media Marketing Estratégico', 'Domina las estrategias de social media para tu negocio', 'Aprende a crear estrategias efectivas en redes sociales para aumentar tu marca.', 360, 'principiante', 'Ana Martínez', 39.99, 1890, 4.6, 'social-media-marketing', 4, 'activo'),
('Edición de Vídeo Profesional', 'Crea vídeos profesionales con Adobe Premiere', 'Aprende a editar vídeos de forma profesional usando herramientas de la industria.', 600, 'intermedio', 'Pedro Sánchez', 59.99, 743, 4.5, 'edicion-video-profesional', 5, 'activo'),
('Animación 3D con Blender', 'Crea animaciones 3D impresionantes desde cero', 'Domina Blender y crea animaciones 3D profesionales desde la conceptualización hasta la producción final.', 900, 'avanzado', 'Laura Gómez', 69.99, 542, 4.8, 'animacion-3d-blender', 6, 'activo'),
('Branding y Identidad Visual', 'Diseña marcas profesionales que impacten', 'Aprende a crear identidades visuales sólidas y coherentes para empresas.', 420, 'intermedio', 'David Torres', 44.99, 678, 4.7, 'branding-identidad-visual', 1, 'activo'),
('Fotografía Callejera Moderna', 'Captura la esencia de la calle con tu cámara', 'Técnicas especializadas en fotografía callejera y retrato urbano.', 300, 'principiante', 'Sofia Ruiz', 24.99, 1456, 4.6, 'fotografia-callejera', 2, 'activo'),
('SEO y Posicionamiento Web', 'Posiciona tu sitio web en Google', 'Estrategias efectivas de SEO para aumentar el tráfico orgánico a tu sitio.', 480, 'intermedio', 'Miguel Ángel', 39.99, 987, 4.5, 'seo-posicionamiento', 4, 'activo'),
('Ilustración Digital', 'Crea ilustraciones profesionales con Procreate', 'Domina la ilustración digital y crea obras profesionales.', 540, 'principiante', 'Valentina Espinoza', 34.99, 1123, 4.8, 'ilustracion-digital', 1, 'activo');

-- Relacionar cursos con etiquetas
INSERT INTO curso_etiqueta (curso_id, etiqueta_id) VALUES
-- Curso 1: Introducción al Diseño Gráfico
(1, 1), (1, 5), (1, 8), (1, 10),
-- Curso 2: Fotografía Profesional Avanzada
(2, 3), (2, 5), (2, 9),
-- Curso 3: Desarrollo Web Fullstack
(3, 2), (3, 5), (3, 9),
-- Curso 4: Social Media Marketing
(4, 1), (4, 5), (4, 8),
-- Curso 5: Edición de Vídeo
(5, 2), (5, 5), (5, 9),
-- Curso 6: Animación 3D
(6, 3), (6, 5),
-- Curso 7: Branding
(7, 1), (7, 5), (7, 10),
-- Curso 8: Fotografía Callejera
(8, 1), (8, 5), (8, 8),
-- Curso 9: SEO
(9, 2), (9, 5),
-- Curso 10: Ilustración Digital
(10, 1), (10, 5), (10, 10);

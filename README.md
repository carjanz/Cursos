# CursoHub - Plataforma de Cursos Online

Una plataforma funcional y atractiva inspirada en Domestika para mostrar y gestionar cursos en línea. Construida con HTML, CSS, JavaScript, PHP y MySQL.

## 🎯 Características

✨ **Interfaz Moderna**
- Diseño responsive inspirado en Domestika
- Navegación intuitiva y amigable
- Animaciones suaves y transiciones

🔍 **Sistema de Filtros Avanzado**
- Filtrar cursos por etiquetas dinámicamente
- Búsqueda de cursos en tiempo real
- Paginación automática

📚 **Gestión Completa de Cursos**
- Catálogo de cursos organizado por categorías
- Información detallada de cada curso
- Sistema de calificaciones y comentarios

👨‍💼 **Panel de Administración**
- Dashboard con estadísticas
- Gestión de cursos, categorías y etiquetas
- Administración de usuarios
- Fácil de usar y expandible

📱 **Diseño Responsivo**
- Mobile-first approach
- Funciona perfectamente en todos los dispositivos
- Experiencia de usuario optimizada

## 🛠️ Requisitos Previos

- **PHP 7.4 o superior**
- **MySQL 5.7 o superior**
- **Servidor Web (Apache, Nginx, etc.)**
- **Navegador moderno**

## 📦 Instalación

### 1. Preparación de la Base de Datos

1. Abre tu cliente MySQL (phpMyAdmin, MySQL Workbench, etc.)
2. Crea una nueva base de datos:
   ```sql
   CREATE DATABASE cursos_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. Ejecuta el script SQL para crear las tablas:
   - Abre el archivo `database/schema.sql`
   - Copia todo el contenido
   - Ejecuta en tu cliente MySQL
   - O importa el archivo directamente en phpMyAdmin

### 2. Configuración de PHP

1. Abre `config/db.php`
2. Actualiza los siguientes parámetros con tus credenciales MySQL:
   ```php
   define('DB_HOST', 'localhost');    // Tu host MySQL
   define('DB_USER', 'root');         // Tu usuario MySQL
   define('DB_PASS', '');             // Tu contraseña MySQL
   define('DB_NAME', 'cursos_db');    // Nombre de la base de datos
   ```

3. Actualiza la URL del sitio si es necesario:
   ```php
   define('SITE_URL', 'http://localhost/cursos-centro/');
   ```

### 3. Estructura de Carpetas

La estructura del proyecto es la siguiente:
```
cursos-centro/
├── admin/                 # Panel de administración
│   ├── index.php         # Página principal admin
│   ├── pages/            # Páginas del admin
│   │   ├── dashboard.php
│   │   ├── cursos.php
│   │   ├── categorias.php
│   │   ├── etiquetas.php
│   │   └── usuarios.php
│   └── logout.php
├── api/                   # Endpoints de API
│   ├── get_cursos.php    # Obtener cursos con filtros
│   ├── get_etiquetas.php # Obtener etiquetas
│   └── get_curso.php     # Obtener curso por slug
├── assets/
│   ├── css/
│   │   └── style.css     # Estilos principales
│   ├── js/
│   │   ├── main.js       # Funcionalidad general
│   │   └── filters.js    # Sistema de filtros
│   └── images/           # Imágenes del sitio
├── config/
│   └── db.php            # Configuración de base de datos
├── database/
│   └── schema.sql        # Script de base de datos
├── includes/
│   ├── header.php        # Encabezado común
│   └── footer.php        # Pie de página común
├── index.php             # Página principal
├── course.php            # Página de detalles del curso
└── README.md             # Este archivo
```

## 🚀 Uso

### Acceso al Sitio Principal
```
http://localhost/cursos-centro/
```

### Acceso al Panel de Administración
```
http://localhost/cursos-centro/admin/
```

### Funcionalidades Principales

**Página Principal**
- Vista de todos los cursos activos
- Filtros por etiquetas
- Barra de búsqueda
- Paginación

**Página de Detalles del Curso**
- Información completa del curso
- Instructor y contacto
- Cursos relacionados
- Etiquetas asociadas

**Panel de Administración**
- Dashboard con estadísticas
- Gestión CRUD de cursos
- Gestión de categorías
- Gestión de etiquetas
- Gestión de usuarios

## 📊 Datos de Ejemplo

La base de datos incluye automáticamente:
- **10 Cursos** de ejemplo en diferentes categorías
- **6 Categorías** (Diseño, Fotografía, Programación, Marketing, Vídeo, Animación)
- **10 Etiquetas** para clasificar los cursos
- Relaciones Curso-Etiqueta completadas

## 🔧 Personalización

### Cambiar Colores
Edita las variables CSS en `assets/css/style.css`:
```css
:root {
    --primary: #FF6B6B;           /* Color principal */
    --secondary: #4ECDC4;         /* Color secundario */
    --tertiary: #45B7D1;          /* Color terciario */
    /* ... más variables */
}
```

### Agregar Nuevas Categorías
En la base de datos, inserta en la tabla `categorias`:
```sql
INSERT INTO categorias (nombre, slug, descripcion, color) 
VALUES ('Nueva Categoría', 'nueva-categoria', 'Descripción', '#XXXXXX');
```

### Agregar Nuevas Etiquetas
```sql
INSERT INTO etiquetas (nombre, slug, descripcion) 
VALUES ('Nueva Etiqueta', 'nueva-etiqueta', 'Descripción');
```

### Agregar Nuevos Cursos
```sql
INSERT INTO cursos 
(titulo, descripcion, duracion, nivel, instructor, precio, categoria_id, estado, slug) 
VALUES 
('Título del Curso', 'Descripción...', 600, 'principiante', 'Instructor Nombre', 49.99, 1, 'activo', 'titulo-del-curso');
```

## 🔐 Seguridad (Próximas Mejoras)

⚠️ **IMPORTANTE**: Esta es una versión de demostración. Para producción, implementa:

- ✅ Sistema de autenticación seguro
- ✅ Validación y sanitización de entrada mejorada
- ✅ Protección CSRF
- ✅ Encriptación de contraseñas
- ✅ Control de acceso basado en roles (RBAC)
- ✅ HTTPS obligatorio
- ✅ Logging de actividades

## 📝 API Endpoints

### Obtener Cursos
```
GET /api/get_cursos.php?page=1&tags=1,2,3&search=diseño
```

Parámetros:
- `page`: Número de página (default: 1)
- `tags`: IDs de etiquetas separadas por coma (opcional)
- `search`: Término de búsqueda (opcional)

Respuesta:
```json
{
  "cursos": [...],
  "total_pages": 3,
  "current_page": 1,
  "total_cursos": 25
}
```

### Obtener Etiquetas
```
GET /api/get_etiquetas.php
```

### Obtener Curso por Slug
```
GET /api/get_curso.php?slug=titulo-del-curso
```

## 🎨 Tecnologías Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Base de Datos**: MySQL/MariaDB
- **Iconos**: Font Awesome 6.4
- **Diseño**: Responsivo, Mobile-first

## 📈 Próximas Características

- 🔐 Sistema de autenticación de usuarios
- 💳 Pasarela de pago integrada
- 💬 Sistema de comentarios y reseñas
- 📹 Reproductor de vídeo integrado
- 🎥 Cargas de vídeo
- 📧 Sistema de notificaciones por email
- 🌍 Soporte multiidioma
- 📊 Reportes y análiticas
- 🏆 Sistema de certificados digitales

## 🐛 Solución de Problemas

### Error: "Error de conexión a la base de datos"
- Verifica que MySQL está corriendo
- Comprueba las credenciales en `config/db.php`
- Verifica que la base de datos existe

### Error: "Página no encontrada"
- Asegúrate que todos los archivos .php existen
- Verifica que la URL es correcta
- Comprueba los permisos de carpetas

### Los filtros no funcionan
- Verifica que los datos están en la base de datos
- Abre la consola del navegador (F12) para ver errores
- Comprueba que los archivos en `api/` son accesibles

## 📞 Soporte

Para reportar problemas o sugerencias, por favor contacta al equipo de desarrollo.

## 📄 Licencia

Este proyecto está bajo licencia MIT. Siéntete libre de usarlo, modificarlo y distribuirlo.

## 👥 Créditos

Desarrollado como una solución completa para plataformas de cursos online.
Inspirado en diseño de Domestika y mejores prácticas de desarrollo web.

---

**¡Disfruta creando contenido educativo!** 🎓

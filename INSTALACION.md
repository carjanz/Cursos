# Instrucciones de Instalación Rápida - CursoHub

## ⚡ Instalación en 5 Pasos

### Paso 1: Clonar/Descargar el Proyecto
```bash
# El proyecto ya está en tu carpeta
cd c:\Users\carlo\OneDrive\Escritorio\workana\Centro
```

### Paso 2: Crear la Base de Datos

**Opción A: Usando phpMyAdmin**
1. Abre phpMyAdmin en tu navegador (http://localhost/phpmyadmin)
2. Haz clic en "Nueva" o "Create new database"
3. Nombre: `cursos_db`
4. Collation: `utf8mb4_unicode_ci`
5. Clic en "Crear"
6. Selecciona la base de datos `cursos_db`
7. Ve a la pestaña "SQL"
8. Abre el archivo `database/schema.sql`
9. Copia TODO el contenido y pégalo en phpMyAdmin
10. Ejecuta

**Opción B: Usando MySQL desde línea de comandos**
```bash
mysql -u root -p < database/schema.sql
```

Si no tienes contraseña:
```bash
mysql -u root < database/schema.sql
```

### Paso 3: Configurar la Conexión a Base de Datos

1. Abre el archivo: `config/db.php`
2. Verifica/Modifica estas líneas según tu configuración MySQL:

```php
define('DB_HOST', 'localhost');      // Tu host (localhost es lo común)
define('DB_USER', 'root');           // Tu usuario MySQL
define('DB_PASS', '');               // Tu contraseña (vacío si no tienes)
define('DB_NAME', 'cursos_db');      // Nombre de la BD
```

**Ejemplo si tienes contraseña:**
```php
define('DB_PASS', 'micontraseña123');
```

### Paso 4: Configurar tu Servidor Web

**Para Apache:**
1. Asegúrate que Apache está corriendo
2. Coloca el proyecto en tu raíz web (htdocs, www, etc.)
3. Accede vía: `http://localhost/Centro/`

**Para Nginx:**
1. Configura un virtual host que apunte a la carpeta del proyecto
2. Reinicia Nginx

**Para PHP Built-in Server (fácil para desarrollo):**
```bash
cd c:\Users\carlo\OneDrive\Escritorio\workana\Centro
php -S localhost:8000
```
Luego accede a: `http://localhost:8000`

### Paso 5: Verifica la Instalación

1. Abre tu navegador
2. Ve a: `http://localhost/Centro/` (o la URL que configuraste)
3. Deberías ver la página principal con cursos
4. Ve al admin: `http://localhost/Centro/admin/`
5. Verifica que ves el dashboard con estadísticas

## ✅ Checklist de Instalación

- [ ] Base de datos `cursos_db` creada
- [ ] Tabla `cursos` y relacionadas existen
- [ ] Archivo `config/db.php` configurado correctamente
- [ ] Servidor web está corriendo
- [ ] Puedes acceder a http://localhost/Centro/
- [ ] Los cursos de ejemplo aparecen en la página principal
- [ ] Puedes ver el panel admin

## 🧪 Prueba las Características

### Página Principal
- ✓ Abre http://localhost/Centro/
- ✓ Verifica que aparecen los 10 cursos de ejemplo
- ✓ Prueba filtrar por etiquetas
- ✓ Prueba la búsqueda de cursos

### Detalles del Curso
- ✓ Haz clic en un curso
- ✓ Verifica que carga la información completa
- ✓ Ve los cursos relacionados al final

### Panel Admin
- ✓ Accede a http://localhost/Centro/admin/
- ✓ Verifica el dashboard
- ✓ Ve las tablas de cursos, categorías, etc.

## 🆘 Solución de Problemas

### "Error: No se puede conectar a la base de datos"
**Solución:**
1. Verifica que MySQL está corriendo
2. Comprueba las credenciales en `config/db.php`
3. Abre phpMyAdmin y confirma que existe la BD `cursos_db`

### "No aparecen los cursos"
**Solución:**
1. Abre phpMyAdmin
2. Selecciona BD `cursos_db`
3. Haz clic en tabla `cursos`
4. Deberías ver 10 registros
5. Si no hay, vuelve a ejecutar el SQL del archivo `schema.sql`

### "Error 404 - Página no encontrada"
**Solución:**
1. Verifica la URL correcta (con la ruta completa)
2. Asegúrate que todos los archivos .php están presentes
3. Comprueba que configuraste bien el SITE_URL en `config/db.php`

### "Blanco en la página / No carga nada"
**Solución:**
1. Abre la consola del navegador (F12 → Console)
2. Verifica si hay errores JavaScript
3. Comprueba el error en `php error_log` si existe
4. Intenta acceder directamente a: `http://localhost/Centro/api/get_etiquetas.php`

## 🔧 Personalización Rápida

### Cambiar nombre del sitio
En `config/db.php`:
```php
define('SITE_NAME', 'Mi Plataforma de Cursos');
```

### Cambiar email de administrador
```php
define('ADMIN_EMAIL', 'admin@midominio.com');
```

### Cambiar URL del sitio
```php
define('SITE_URL', 'http://mi-dominio.com/cursos/');
```

### Cambiar color primario
En `assets/css/style.css` (línea ~25):
```css
--primary: #FF6B6B;  /* Cambia este color hex */
```

## 📱 Prueba Responsivo

1. Abre la página en tu navegador
2. Presiona F12 para abrir Developer Tools
3. Haz clic en el icono de dispositivo móvil
4. Prueba diferentes tamaños de pantalla
5. Verifica que todo se ve bien

## 🚀 Próximos Pasos

Después de verificar que todo funciona:

1. **Agregar más cursos** - Ve a admin y crea nuevos cursos
2. **Personalizar categorías** - Modifica los colores y nombres
3. **Crear etiquetas propias** - Según tus necesidades
4. **Implementar autenticación** - Sistema de login
5. **Agregar más funciones** - Según lo necesites

## 📞 Ayuda Adicional

- Lee el archivo `README.md` para más información
- Consulta la documentación de PHP
- Verifica los comentarios en el código

¡Listo! Tu plataforma de cursos está funcionando. ¡A disfrutar! 🎉

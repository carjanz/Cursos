// filters.js - Sistema de filtros por etiquetas

let activeTags = [];
let currentPage = 1;

document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
    loadCourses();
});

// Inicializar sistema de filtros
function initializeFilters() {
    // Cargar etiquetas disponibles
    fetch('api/get_etiquetas.php')
        .then(response => response.json())
        .then(data => {
            renderFilterTags(data);
        })
        .catch(error => console.error('Error:', error));
}

// Renderizar tags de filtro
function renderFilterTags(tags) {
    const filterGroup = document.querySelector('.filter-group');
    
    if (!filterGroup) {
        console.log('Container de filtros no encontrado, creando...');
        const filtersSection = document.querySelector('.filters-section');
        if (filtersSection) {
            const filterContainer = document.createElement('div');
            filterContainer.className = 'filters-container';
            filterContainer.innerHTML = '<div class="filter-group"></div>';
            filtersSection.appendChild(filterContainer);
        }
        return;
    }

    filterGroup.innerHTML = '';

    tags.forEach(tag => {
        const tagElement = document.createElement('button');
        tagElement.className = 'filter-tag';
        tagElement.textContent = `${tag.nombre} (${tag.cantidad_cursos})`;
        tagElement.dataset.tagId = tag.id;
        
        tagElement.addEventListener('click', function() {
            toggleTag(tag.id, this);
        });

        filterGroup.appendChild(tagElement);
    });
}

// Alternar tag activo
function toggleTag(tagId, element) {
    const index = activeTags.indexOf(tagId);
    
    if (index > -1) {
        activeTags.splice(index, 1);
        element.classList.remove('active');
    } else {
        activeTags.push(tagId);
        element.classList.add('active');
    }

    currentPage = 1;
    loadCourses();
}

// Cargar cursos con filtros
function loadCourses() {
    let url = 'api/get_cursos.php?page=' + currentPage;
    
    if (activeTags.length > 0) {
        url += '&tags=' + activeTags.join(',');
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            renderCourses(data.cursos);
            renderPagination(data.total_pages);
        })
        .catch(error => console.error('Error:', error));
}

// Renderizar cursos
function renderCourses(courses) {
    const grid = document.querySelector('.courses-grid');
    
    if (!grid) {
        console.error('Grid de cursos no encontrado');
        return;
    }

    grid.innerHTML = '';

    if (courses.length === 0) {
        grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 2rem;">No se encontraron cursos con los filtros seleccionados.</p>';
        return;
    }

    courses.forEach((course, index) => {
        const card = createCourseCard(course);
        card.style.setProperty('--index', index);
        grid.appendChild(card);
    });
}

// Crear tarjeta de curso
function createCourseCard(course) {
    const card = document.createElement('div');
    card.className = 'course-card';
    
    const etiquetasHTML = course.etiquetas ? 
        course.etiquetas.split(',').slice(0, 3).map(tag => 
            `<span class="course-tag">${tag.trim()}</span>`
        ).join('') : '';

    card.innerHTML = `
        <div class="course-image">
            <i class="fas fa-${getIconForCategory(course.categoria_nombre)}"></i>
        </div>
        <div class="course-body">
            <span class="course-category">${course.categoria_nombre || 'General'}</span>
            <h3 class="course-title">${course.titulo}</h3>
            <p class="course-instructor"><i class="fas fa-user-circle"></i> ${course.instructor}</p>
            
            <div class="course-info">
                <div class="course-stats">
                    <div class="course-stat">
                        <i class="fas fa-clock"></i>
                        <span>${course.duracion} min</span>
                    </div>
                    <div class="course-stat">
                        <i class="fas fa-users"></i>
                        <span>${course.estudiantes}</span>
                    </div>
                </div>
                <div class="course-rating">
                    <span class="stars">★★★★★</span>
                    <span>${course.calificacion}</span>
                </div>
            </div>

            <div class="course-tags">
                ${etiquetasHTML}
            </div>

            <div class="course-footer">
                <span class="course-price">$${parseFloat(course.precio).toFixed(2)}</span>
                <a href="course.php?slug=${course.slug}" class="btn-primary btn-small">Ver Curso</a>
            </div>
        </div>
    `;

    return card;
}

// Obtener icono según categoría
function getIconForCategory(category) {
    const iconMap = {
        'Diseño': 'palette',
        'Fotografía': 'camera',
        'Programación': 'code',
        'Marketing Digital': 'trending-up',
        'Vídeo': 'film',
        'Animación': 'zap'
    };
    return iconMap[category] || 'book';
}

// Renderizar paginación
function renderPagination(totalPages) {
    const paginationContainer = document.querySelector('.pagination');
    
    if (!paginationContainer) {
        return;
    }

    paginationContainer.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.className = 'pagination-btn' + (i === currentPage ? ' active' : '');
        
        button.addEventListener('click', function() {
            currentPage = i;
            loadCourses();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        paginationContainer.appendChild(button);
    }
}

// Limpiar filtros
function clearFilters() {
    activeTags = [];
    currentPage = 1;
    
    document.querySelectorAll('.filter-tag').forEach(tag => {
        tag.classList.remove('active');
    });

    loadCourses();
}

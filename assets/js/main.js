// main.js - Funcionalidad principal

document.addEventListener('DOMContentLoaded', function() {
    initializeSearch();
    initializeHamburger();
    initializeNewsletterForm();
});

// Funcionalidad de búsqueda
function initializeSearch() {
    const searchToggle = document.getElementById('search-toggle');
    const searchBar = document.getElementById('search-bar');
    const searchInput = document.getElementById('search-input');

    if (searchToggle) {
        searchToggle.addEventListener('click', function() {
            searchBar.classList.toggle('active');
            if (searchBar.classList.contains('active')) {
                searchInput.focus();
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch(this.value);
            }
        });
    }
}

// Buscar cursos
function performSearch(query) {
    if (!query.trim()) {
        alert('Por favor ingresa un término de búsqueda');
        return;
    }
    
    window.location.href = `/?search=${encodeURIComponent(query)}`;
}

// Hamburger menu
function initializeHamburger() {
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.querySelector('.navbar-menu');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex';
            hamburger.classList.toggle('active');
        });

        // Cerrar menú al hacer clic en un enlace
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', function() {
                navMenu.style.display = 'none';
                hamburger.classList.remove('active');
            });
        });
    }
}

// Newsletter
function initializeNewsletterForm() {
    const form = document.getElementById('newsletter-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            alert(`¡Gracias por suscribirte! Confirmación enviada a ${email}`);
            this.reset();
        });
    }
}

// Scroll suave
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Lazy loading para imágenes
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img.lazy').forEach(img => imageObserver.observe(img));
}

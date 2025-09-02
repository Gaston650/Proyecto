document.addEventListener("DOMContentLoaded", function() {
    const links = document.querySelectorAll('.nav-links a');
    const current = window.location.pathname.split('/').pop();

    links.forEach(link => {
        // Extrae solo el archivo del href (por si es relativo o absoluto)
        const hrefFile = link.getAttribute('href').split('/').pop();
        if(hrefFile === current && current !== '' && hrefFile !== '#') {
            link.classList.add('active');
        }
    });
});
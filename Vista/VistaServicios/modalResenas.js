// modalResenas.js

// Referencias del DOM
const modal = document.getElementById('modal-resenas');
const contenidoResenas = document.getElementById('contenido-resenas');
const cerrar = modal.querySelector('.close');

// Función para abrir el modal con las reseñas del servicio
function abrirModalResenas(id_servicio) {
    contenidoResenas.innerHTML = ''; // Limpiar contenido

    const resenas = resenasData[id_servicio];

    if (!resenas || resenas.length === 0) {
        contenidoResenas.innerHTML = '<p>No hay reseñas para este servicio.</p>';
    } else {
        resenas.forEach(r => {
            const div = document.createElement('div');
            div.classList.add('resena');

            // Estrellas
            let estrellas = '';
            for (let i = 0; i < r.calificacion; i++) {
                estrellas += '⭐';
            }

            div.innerHTML = `
                <p><strong>${r.cliente_nombre}</strong></p>
                <p>${estrellas}</p>
                <p>${r.comentario}</p>
                <hr>
            `;
            contenidoResenas.appendChild(div);
        });
    }

    modal.style.display = 'block';
}

// Función para cerrar el modal
function cerrarModalResenas() {
    modal.style.display = 'none';
}

// Asignar evento al botón de cerrar
cerrar.addEventListener('click', cerrarModalResenas);

// Cerrar modal al hacer click fuera del contenido
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        cerrarModalResenas();
    }
});

// Asignar evento a todos los botones de ver reseñas
document.querySelectorAll('.btn-ver-resenas').forEach(btn => {
    btn.addEventListener('click', () => {
        const idServicio = btn.getAttribute('data-id-servicio');
        abrirModalResenas(idServicio);
    });
});

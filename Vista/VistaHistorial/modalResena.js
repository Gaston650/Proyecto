function mostrarModal() {
    const modal = document.getElementById('modal-reseña');
    modal.style.display = 'flex';
}

document.addEventListener('DOMContentLoaded', () => {
    const btnCerrar = document.getElementById('cerrar-modal');
    const modal = document.getElementById('modal-reseña');
    btnCerrar.addEventListener('click', () => {
        modal.style.display = 'none';
        window.history.replaceState(null, null, window.location.pathname); // elimina el parámetro
    });
});

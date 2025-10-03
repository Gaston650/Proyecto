document.addEventListener('DOMContentLoaded', () => {
    const modalExito = document.getElementById('modal-exito');
    const cerrarExito = modalExito.querySelector('.cerrar-exito');

    // Mostrar modal si viene mensaje de éxito en URL
    const params = new URLSearchParams(window.location.search);
    const mensaje = params.get('mensaje');
    if (mensaje && mensaje.includes('Reserva creada con éxito')) {
        modalExito.style.display = 'block';
        // Limpiar URL para que no vuelva a aparecer al recargar
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Cerrar modal al hacer click en la X
    cerrarExito.addEventListener('click', () => {
        modalExito.style.display = 'none';
    });

});


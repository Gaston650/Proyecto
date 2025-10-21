// --- Mostrar modal de confirmación si el reporte fue exitoso ---
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const modalConfirmacion = document.getElementById('modal-confirmacion');

    if (urlParams.get('reporte') === 'ok') {
        modalConfirmacion.style.display = 'block';
        setTimeout(() => {
            modalConfirmacion.style.display = 'none';
            // Limpiar parámetro de la URL sin recargar
            window.history.replaceState({}, document.title, window.location.pathname);
        }, 2500); // se cierra en 2.5 segundos
    } else if (urlParams.get('reporte') === 'error') {
        alert("❌ Ocurrió un error al enviar el reporte. Intenta nuevamente.");
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});


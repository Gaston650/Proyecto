document.addEventListener('DOMContentLoaded', function() {
    // Modal Reportar
    const modalReportar = document.getElementById('modal-reportar');
    const btnReportar = document.querySelectorAll('.btn-reportar');
    const spanClose = modalReportar.querySelector('.close');
    const inputId = document.getElementById('input-id-servicio');
    const tituloSpan = document.getElementById('titulo-servicio');

    btnReportar.forEach(btn => {
        btn.addEventListener('click', () => {
            const idServicio = btn.getAttribute('data-id-servicio');
            const titulo = btn.getAttribute('data-titulo-servicio');
            inputId.value = idServicio;
            tituloSpan.textContent = titulo;
            modalReportar.style.display = 'block';
        });
    });

    spanClose.addEventListener('click', () => {
        modalReportar.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target == modalReportar) {
            modalReportar.style.display = 'none';
        }
    });
});
let idReservaCancelar = null;

function abrirModal(id_reserva) {
    idReservaCancelar = id_reserva;
    document.getElementById('modalCancelar').style.display = 'block';
}

function cerrarModal() {
    idReservaCancelar = null;
    document.getElementById('modalCancelar').style.display = 'none';
}

// Cerrar modal al hacer clic fuera del contenido
window.onclick = function(event) {
    const modal = document.getElementById('modalCancelar');
    if (event.target === modal) cerrarModal();
}

// Confirmar cancelación con AJAX
document.getElementById('confirmarCancelar').addEventListener('click', function() {
    if (!idReservaCancelar) return;

    fetch(`../../Controlador/minisControlador/cancelarReserva.php?id=${idReservaCancelar}`, {
        method: 'GET'
    })
    .then(response => response.text())
    .then(data => {
        // Actualizar tarjeta
        const tarjeta = document.querySelector(`.reserva-card[data-id='${idReservaCancelar}']`);
        tarjeta.querySelector('.estado').textContent = 'Cancelada';
        tarjeta.querySelector('.estado').className = 'estado cancelada';
        // Quitar botones Reprogramar y Cancelar
        const acciones = tarjeta.querySelector('.acciones');
        const botones = acciones.querySelectorAll('.btn-reprogramar, .btn-cancelar');
        botones.forEach(b => b.remove());
        cerrarModal();
    })
    .catch(err => {
        alert('Ocurrió un error al cancelar la reserva');
        console.error(err);
        cerrarModal();
    });
});

// Filtro de estado dinámico
function filtrarEstado(estado) {
    const cards = document.querySelectorAll('.reserva-card');
    cards.forEach(card => {
        const estadoCard = card.querySelector('.estado').textContent.toLowerCase();
        card.style.display = (!estado || estadoCard === estado) ? 'block' : 'none';
    });
}
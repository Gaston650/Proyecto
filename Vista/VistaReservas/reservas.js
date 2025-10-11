// Modal Cancelar
let modalCancelar = document.getElementById('modalCancelar');
let confirmarCancelar = document.getElementById('confirmarCancelar');
let reservaAEliminar = null;

function abrirModalCancelar(idReserva) {
    reservaAEliminar = idReserva;
    modalCancelar.classList.add('show');
    confirmarCancelar.href = `../../Controlador/minisControlador/cancelarReserva.php?id=${idReserva}`;
}

function cerrarModal() {
    modalCancelar.classList.remove('show');
    reservaAEliminar = null;
}

// Modal Reprogramar
let modalReprogramar = document.getElementById('modalReprogramar');
let confirmarReprogramar = document.getElementById('confirmarReprogramar');
let reservaAReprogramar = null;

function abrirModalReprogramar(idReserva) {
    reservaAReprogramar = idReserva;
    modalReprogramar.classList.add('show');
    limpiarErrorReprogramar();
    // Establecer fecha mínima como hoy
    const inputFecha = document.getElementById('nuevaFecha');
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
    const dd = String(hoy.getDate()).padStart(2, '0');
    inputFecha.min = `${yyyy}-${mm}-${dd}`;
}

function cerrarModalReprogramar() {
    modalReprogramar.classList.remove('show');
    reservaAReprogramar = null;
    limpiarErrorReprogramar();
}

// Mensaje de error dentro del modal
function mostrarErrorReprogramar(msg) {
    let errorDiv = document.getElementById('errorReprogramar');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = 'errorReprogramar';
        errorDiv.style.color = 'red';
        errorDiv.style.margin = '10px 0';
        modalReprogramar.querySelector('.modal-contenido').insertBefore(errorDiv, confirmarReprogramar);
    }
    errorDiv.textContent = msg;
}

function limpiarErrorReprogramar() {
    let errorDiv = document.getElementById('errorReprogramar');
    if (errorDiv) errorDiv.remove();
}

// Evento para reprogramar
confirmarReprogramar.addEventListener('click', () => {
    const fecha = document.getElementById('nuevaFecha').value;
    const hora = document.getElementById('nuevaHora').value;

    if (!fecha || !hora) {
        mostrarErrorReprogramar("Debes seleccionar fecha y hora.");
        return;
    }

    // Comparamos fechas ignorando horas
    const hoy = new Date();
    hoy.setHours(0,0,0,0);

    const fechaSeleccionada = new Date(fecha);
    fechaSeleccionada.setHours(0,0,0,0);

    if (fechaSeleccionada < hoy) {
        mostrarErrorReprogramar("No se puede reprogramar a una fecha pasada.");
        return;
    }

    // Redirige al controlador con fecha y hora
    window.location.href = `../../Controlador/minisControlador/reprogramarReserva.php?id=${reservaAReprogramar}&fecha=${fecha}&hora=${hora}`;
});

// Cerrar modal al hacer click fuera del contenido
window.onclick = function(event) {
    if (event.target == modalCancelar) cerrarModal();
    if (event.target == modalReprogramar) cerrarModalReprogramar();
}

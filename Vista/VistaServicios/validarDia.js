document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('modal-fecha');
    const formContratar = document.getElementById('form-contratar');

    // 1️⃣ Limitar el input date a hoy o fechas futuras
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, '0'); // meses empiezan en 0
    const dd = String(hoy.getDate()).padStart(2, '0');
    const fechaMin = `${yyyy}-${mm}-${dd}`;
    fechaInput.min = fechaMin;

    // 2️⃣ Validación al enviar el formulario
    formContratar.addEventListener('submit', function(e) {
        const fechaSeleccionada = new Date(fechaInput.value);
        fechaSeleccionada.setHours(0,0,0,0); // ignorar hora para la comparación
        const fechaHoy = new Date();
        fechaHoy.setHours(0,0,0,0);

        if (fechaSeleccionada < fechaHoy) {
            e.preventDefault();
            alert('No puedes seleccionar una fecha que ya pasó.'); 
            fechaInput.focus();
        }
    });
});
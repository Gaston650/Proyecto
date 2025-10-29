// Función para establecer el mínimo de las fechas
function configurarFechas() {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const hoy = `${yyyy}-${mm}-${dd}`;

    // Inputs de crear promoción
    const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
    const fechaFin = document.querySelector('input[name="fecha_fin"]');
    if(fechaInicio) fechaInicio.min = hoy;
    if(fechaFin) fechaFin.min = hoy;

    // Inputs de editar promoción
    const editarFechaInicio = document.getElementById('editar_fecha_inicio');
    const editarFechaFin = document.getElementById('editar_fecha_fin');
    if(editarFechaInicio) editarFechaInicio.min = hoy;
    if(editarFechaFin) editarFechaFin.min = hoy;

    // Ajustar fecha fin automáticamente si fecha inicio cambia
    if(fechaInicio && fechaFin) {
        fechaInicio.addEventListener('change', () => {
            if(fechaFin.value < fechaInicio.value) {
                fechaFin.value = fechaInicio.value;
            }
            fechaFin.min = fechaInicio.value;
        });
    }

    if(editarFechaInicio && editarFechaFin) {
        editarFechaInicio.addEventListener('change', () => {
            if(editarFechaFin.value < editarFechaInicio.value) {
                editarFechaFin.value = editarFechaInicio.value;
            }
            editarFechaFin.min = editarFechaInicio.value;
        });
    }
}

// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', configurarFechas);
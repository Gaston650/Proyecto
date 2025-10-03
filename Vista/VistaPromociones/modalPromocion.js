// ===== MODALES =====

// Modal Crear Promoción
const modalCrear = document.getElementById("modalPromocion");
const servicioSelect = document.getElementById("servicio_select");
const idServicioInput = document.getElementById("id_servicio_input");
const cerrarCrear = modalCrear.querySelector(".close");

// Abrir modal Crear al seleccionar un servicio
servicioSelect.addEventListener("change", () => {
    if (servicioSelect.value !== "") {
        idServicioInput.value = servicioSelect.value;
        modalCrear.style.display = "flex";
    }
});

// Cerrar modal Crear
cerrarCrear.addEventListener("click", () => {
    modalCrear.style.display = "none";
});

// Modal Editar Promoción
const modalEditar = document.getElementById("modalEditar");
const cerrarEditar = modalEditar.querySelector(".close");
const cancelarEditar = document.getElementById("cancelarEditar");

document.querySelectorAll(".btn-editar").forEach(btn => {
    btn.addEventListener("click", () => {
        // Llenar campos del modal
        document.getElementById("editar_id_promocion").value = btn.dataset.id;
        document.getElementById("editar_id_servicio").value = btn.dataset.servicio;
        document.getElementById("editar_porcentaje").value = btn.dataset.porcentaje;
        document.getElementById("editar_fecha_inicio").value = btn.dataset.fecha_inicio;
        document.getElementById("editar_fecha_fin").value = btn.dataset.fecha_fin;
        document.getElementById("editar_condiciones").value = btn.dataset.condiciones;

        modalEditar.style.display = "flex"; // mostrar centrado
    });
});

cerrarEditar.addEventListener("click", () => modalEditar.style.display = "none");
cancelarEditar.addEventListener("click", () => modalEditar.style.display = "none");

// Modal Eliminar Promoción
const modalEliminar = document.getElementById("modalEliminar");
const cerrarEliminar = modalEliminar.querySelector(".close");
const cancelarEliminar = document.getElementById("cancelarEliminar");
const idEliminarInput = document.getElementById("id_promocion_eliminar");

document.querySelectorAll(".btn-eliminar").forEach(btn => {
    btn.addEventListener("click", () => {
        idEliminarInput.value = btn.dataset.id;
        modalEliminar.style.display = "flex";
    });
});

cerrarEliminar.addEventListener("click", () => modalEliminar.style.display = "none");
cancelarEliminar.addEventListener("click", () => modalEliminar.style.display = "none");
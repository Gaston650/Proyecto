const modalEditar = document.getElementById("modalEditar");
const btnsEditar = document.querySelectorAll(".btn-editar");
const cerrarEditar = document.getElementById("cerrarEditar");
const cancelarEditar = document.getElementById("cancelarEditar");

btnsEditar.forEach(btn => {
    btn.addEventListener("click", () => {
        modalEditar.style.display = "flex";

        // Precargar datos
        document.getElementById("editarIdServicio").value = btn.dataset.id;
        document.getElementById("editarTitulo").value = btn.dataset.titulo;
        document.getElementById("editarDescripcion").value = btn.dataset.descripcion;
        document.getElementById("editarUbicacion").value = btn.dataset.ubicacion;
        document.getElementById("editarPrecio").value = btn.dataset.precio;
        document.getElementById("editarDisponibilidad").value = btn.dataset.disponibilidad;

        // CORRECCIÓN: seleccionar correctamente la opción del select
        const estadoSelect = document.getElementById("editarEstado");
        const estado = btn.dataset.estado.trim(); // quitar espacios
        for (let i = 0; i < estadoSelect.options.length; i++) {
            if (estadoSelect.options[i].value === estado) {
                estadoSelect.selectedIndex = i;
                break;
            }
        }
    });
});

// Cerrar modal
cerrarEditar.addEventListener("click", () => modalEditar.style.display = "none");
cancelarEditar.addEventListener("click", () => modalEditar.style.display = "none");

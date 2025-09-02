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
        document.getElementById("editarEstado").value = btn.dataset.estado;
    });
});

// Cerrar modal
cerrarEditar.addEventListener("click", () => modalEditar.style.display = "none");
cancelarEditar.addEventListener("click", () => modalEditar.style.display = "none");

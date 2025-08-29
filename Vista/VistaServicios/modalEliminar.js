const modalEliminar = document.getElementById("modalEliminar");
const btnsEliminar = document.querySelectorAll(".btn-eliminar"); // botones de las tarjetas
const cerrarEliminar = document.getElementById("cerrarEliminar");
const cancelarEliminar = document.getElementById("cancelarEliminar");
const inputIdServicio = document.getElementById("eliminarIdServicio");

// Abrir modal al hacer click en cualquier botón de las tarjetas
btnsEliminar.forEach(btn => {
    btn.addEventListener("click", () => {
        inputIdServicio.value = btn.dataset.id; // ponemos el id en el input hidden
        modalEliminar.style.display = "flex";
    });
});

// Cerrar modal
cerrarEliminar.addEventListener("click", () => modalEliminar.style.display = "none");
cancelarEliminar.addEventListener("click", () => modalEliminar.style.display = "none");

// Cerrar modal si se hace click fuera
window.addEventListener("click", e => {
    if (e.target === modalEliminar) modalEliminar.style.display = "none";
});

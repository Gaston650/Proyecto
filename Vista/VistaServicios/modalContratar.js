document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modal-contratar");
    const inputIdServicio = document.getElementById("modal-id-servicio");
    const btns = document.querySelectorAll(".btn-contratar[data-id-servicio]");
    const closeBtn = modal.querySelector(".close");
    const formContratar = document.getElementById("form-contratar");

    // Abrir modal
    btns.forEach(btn => {
        btn.addEventListener("click", () => {
            inputIdServicio.value = btn.dataset.idServicio;
            modal.style.display = "block";
        });
    });

    // Cerrar modal al click en "X"
    closeBtn.addEventListener("click", () => modal.style.display = "none");

    // Cerrar modal si hace click fuera
    window.addEventListener("click", e => {
        if (e.target === modal) modal.style.display = "none";
    });

});

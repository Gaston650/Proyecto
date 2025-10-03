document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modal");
    const modalText = document.getElementById("modal-text");
    const closeModal = document.querySelector(".modal-close");

    closeModal.onclick = () => { modal.style.display = "none"; };
    window.onclick = e => { if(e.target == modal) modal.style.display = "none"; };

    document.querySelectorAll(".btn.actualizar").forEach(button => {
        button.addEventListener("click", () => {
            const row = button.closest("tr");
            const id_reserva = row.dataset.id;
            const select = row.querySelector(".estado-select");
            const estado = select.value;

            fetch("../../Controlador/minisControlador/validarReserva.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id_reserva=${id_reserva}&estado=${estado}`
            })
            .then(res => res.text())
            .then(() => {
                const estadoSpan = row.querySelector(".estado");
                estadoSpan.textContent = estado;
                estadoSpan.className = "estado " + estado.toLowerCase();

                // Deshabilitar Pendiente si el estado ya no es Pendiente
                row.querySelector(".estado-select option[value='Pendiente']").disabled = estado !== 'Pendiente';

                modalText.textContent = `Estado actualizado a "${estado}"`;
                modal.style.display = "block";
            });
        });
    });
});
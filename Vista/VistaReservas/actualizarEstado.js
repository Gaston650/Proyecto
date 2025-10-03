document.querySelectorAll('.btn.actualizar').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        const id = btn.dataset.id;
        const estado = document.querySelector(`.estado-select[data-id='${id}']`).value;

        fetch('../../Controlador/minisControlador/validarReserva.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id_reserva=${id}&estado=${estado}`
        })
        .then(res => res.text())
        .then(data => {
            alert('Estado actualizado correctamente');
        })
        .catch(err => {
            alert('Error al actualizar estado');
            console.error(err);
        });
    });
});
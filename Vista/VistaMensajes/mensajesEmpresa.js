document.getElementById('formMensaje')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const contenido = this.contenido.value.trim();
    if(!contenido) return;

    const params = new URLSearchParams({
        cliente: '<?= $id_cliente ?>',
        reserva: '<?= $id_reserva ?>',
        contenido
    });

    fetch('enviarMensajeEmpresa.php?' + params.toString())
        .then(res => res.json())
        .then(data => {
            if(data.exito) location.reload();
        });
});
document.addEventListener('DOMContentLoaded', () => {
    const campana = document.getElementById('campana-notificaciones');

    if (campana) {
        campana.addEventListener('click', (e) => {
            fetch('/ClickSoft/Vista/VistaPrincipal/marcarNotificacionesLeidas.php')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const contador = campana.querySelector('.contador');
                        if (contador) contador.remove();
                    }
                })
                .catch(err => console.error('Error al marcar notificaciones:', err));
        });
    }
});

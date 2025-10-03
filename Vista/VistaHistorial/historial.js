document.addEventListener('DOMContentLoaded', () => {
    const botones = document.querySelectorAll('.ver-acciones-btn');
    botones.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const idFila = btn.getAttribute('data-id');
            const fila = document.getElementById(idFila);
            if(fila.style.display === 'table-row') {
                fila.style.display = 'none';
            } else {
                fila.style.display = 'table-row';
            }
        });
    });
});

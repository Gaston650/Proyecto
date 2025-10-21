let modalEliminar = document.getElementById('modalEliminar');
modalEliminar.addEventListener('show.bs.modal', function(event) {
    let button = event.relatedTarget;
    let id = button.getAttribute('data-id');
    modalEliminar.querySelector('#eliminar-id').value = id;
});
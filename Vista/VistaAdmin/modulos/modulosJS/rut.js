document.querySelector('#modalEditarEmpresa form').addEventListener('submit', function(e) {
    let rutInput = document.getElementById('edit-empresa-rut').value.trim();
    if (rutInput !== '' && !/^\d{12}$/.test(rutInput)) {
        e.preventDefault();
        alert('RUT inválido. Debe tener 12 dígitos numéricos');
    }
});
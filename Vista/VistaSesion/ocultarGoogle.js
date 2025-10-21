// Obtener elementos
const radioCliente = document.querySelector('input[name="tipo_usuario"][value="cliente"]');
const radioEmpresa = document.querySelector('input[name="tipo_usuario"][value="empresa"]');
const radioAdmin = document.querySelector('input[name="tipo_usuario"][value="administrador"]');
const botonGoogle = document.getElementById('botonGoogle');
const conecta = document.getElementById('conecta');

function actualizarVisibilidadGoogle() {
  // Si el tipo seleccionado es empresa o administrador → ocultar Google
  if (radioEmpresa.checked || radioAdmin.checked) {
    botonGoogle.style.display = 'none';
    conecta.style.display = 'none';
  } else {
    botonGoogle.style.display = 'block';
    conecta.style.display = 'block';
  }
}

// Ejecutar al cargar para setear estado inicial
actualizarVisibilidadGoogle();

// Escuchar cambios en los radios
radioCliente.addEventListener('change', actualizarVisibilidadGoogle);
radioEmpresa.addEventListener('change', actualizarVisibilidadGoogle);
radioAdmin.addEventListener('change', actualizarVisibilidadGoogle);
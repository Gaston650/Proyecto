  const radios = document.querySelectorAll('input[name="tipo_usuario"]');
    const linkOlvido = document.getElementById('linkOlvido');

    function actualizarOlvido() {
      const tipoSeleccionado = document.querySelector('input[name="tipo_usuario"]:checked').value;
      if (tipoSeleccionado === 'administrador') {
        linkOlvido.style.display = 'none';
      } else {
        linkOlvido.style.display = 'inline';
      }
    }

    radios.forEach(radio => radio.addEventListener('change', actualizarOlvido));

    // Inicializar al cargar la página
    actualizarOlvido();
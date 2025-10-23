 // Detectar cambio de tipo de usuario
const radios = document.querySelectorAll('input[name="tipo_usuario"]');
const linkOlvido = document.getElementById('linkOlvido');
radios.forEach(radio => {
  radio.addEventListener('change', () => {
    if (radio.value === 'empresa') {
      // Si es empresa → redirigir a otra vista
      linkOlvido.href = '../VistaReset/reset_request_empresa.php';
    } else if (radio.value === 'administrador') {
      // Si es administrador → otra vista si querés
      linkOlvido.href = '../VistaReset/reset_admin.php';
    } else {
      // Si es cliente → vista normal
      linkOlvido.href = '../VistaReset/reset_request.php';
    }
  });
});


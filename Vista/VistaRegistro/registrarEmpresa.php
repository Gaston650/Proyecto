<?php
session_start();

// Obtener mensajes desde la sesión si existen
$mensaje = $_SESSION['mensaje_registro'] ?? '';
$tipo = $_SESSION['tipo_mensaje'] ?? '';

// Limpiar variables de sesión después de mostrarlas
unset($_SESSION['mensaje_registro'], $_SESSION['tipo_mensaje']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crear Cuenta</title>
  <link rel="stylesheet" href="Empresa.css" />
</head>
<body>
  <div class="contenedor">
    <div class="formulario">

      <!-- Mensajes de error o éxito -->
      <?php if (!empty($mensaje)): ?>
        <div style="text-align: center; margin-bottom: 15px; color: <?php echo $tipo === 'error' ? 'red' : 'green'; ?>;">
          <?php echo htmlspecialchars($mensaje); ?>
        </div>
      <?php endif; ?>

      <h1>CREA UNA CUENTA</h1>

      <!-- Corregido el enctype del form -->
      <form action="../../Controlador/minisControlador/validarRegistroEmpresa.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="registrar_empresa" value="1" />

        <label for="nombre_empresa">Nombre Empresa</label>
        <input type="text" id="nombre_empresa" name="nombre_empresa" placeholder="Nombre de la Empresa" required />

        <label for="correo_empresa">Correo Electronico Empresarial</label>
        <input type="email" id="correo_empresa" name="correo_empresa" placeholder="Correo Electronico Empresarial" required />

        <label for="zona_cobertura">Zona de cobertura</label>
        <input type="text" id="zona_cobertura" name="zona_cobertura" placeholder="Zona de cobertura" required />

        <label for="telefono">Telefono</label>
        <input type="text" id="telefono" name="telefono" placeholder="Telefono" required />

        <label for="logo">Logo de la Empresa</label>
        <div class="grupo-botones-archivo">
          <label class="label-file" for="logo">Seleccionar archivo</label>
          <button type="button" onclick="borrarLogo()">Borrar archivo</button>
        </div>
        <input type="file" id="logo" name="logo" required onchange="previewLogo(event)" />
        <img id="logo-preview" src="#" alt="Vista previa del logo" style="display:none;" />

        <label for="rut">RUT</label>
        <input type="text" id="rut" name="rut" placeholder="RUT" required />

        <label for="contrasena">Contraseña</label>
        <div class="input-con-ico">
          <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required />
          <span class="icono-ojo" onclick="togglePassword('contrasena', this)">
            <ion-icon name="eye-off-outline"></ion-icon>
          </span>
        </div>

        <label for="confirmar_contrasena">Confirmar Contraseña</label>
        <div class="input-con-ico">
          <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" placeholder="Confirmar Contraseña" required />
          <span class="icono-ojo" onclick="togglePassword('confirmar_contrasena', this)">
            <ion-icon name="eye-off-outline"></ion-icon>
          </span>
        </div>

        <div class="botones">
          <button type="submit" class="btn">REGISTRARTE</button>
        </div>

        <p class="condiciones">
          Al registrarte, aceptas nuestras condiciones de uso y nuestra política de privacidad.
        </p>

        <label class="checkbox">
          <input type="checkbox" name="terminos" required /> Aceptar los términos y condiciones
        </label>

        <p class="login">
          ¿Ya tienes una cuenta? <a href="../VistaSesion/inicioSesion.php">Iniciar Sesión</a>
        </p>
      </form>
    </div>
    <div class="imagen"></div>
  </div>

  <script src="../../Ojo.js"></script>
  <script src="mostrarVista.js"></script>
  <script src="borrarLogo.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

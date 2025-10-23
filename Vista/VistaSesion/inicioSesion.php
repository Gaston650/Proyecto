<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="sesion.css" />
</head>
<body>
  <div class="contenedor">
    <div class="formulario">

      <!-- Mostrar error si existe -->
      <?php if (isset($_GET['error'])): ?>
        <div style="color: red; text-align: center; margin-bottom: 15px;">
          <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
      <?php endif; ?>

       <!-- Mostrar mensaje si existe -->
      <?php if (isset($_GET['msg'])): ?>
        <div style="color: green; text-align: center; margin-bottom: 15px;">
          <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
      <?php endif; ?>

      <h1>INICIAR SESIÓN</h1>  

      <form action="../../Controlador/minisControlador/validarSesion.php" method="POST">
        <input type="hidden" name="iniciar_sesion" value="1" />

        <!-- Selección de tipo de usuario -->
        <div class="tipo-usuario" style="margin-bottom: 15px;">
          <label>
            <input type="radio" name="tipo_usuario" value="cliente" checked> Cliente
          </label>
          <label style="margin-left: 15px;">
            <input type="radio" name="tipo_usuario" value="empresa"> Empresa
          </label>
          <label style="margin-left: 15px;">
            <input type="radio" name="tipo_usuario" value="administrador"> Administrador
          </label>
        </div>

        <!-- Email -->
        <label>Correo Electrónico</label>
        <input type="email" name="correo" placeholder="Correo Electrónico" required />

        <!-- Contraseña -->
        <div class="fila">
          <label>Contraseña</label>
          <a href="../VistaReset/reset_request.php" class="olvido" id="linkOlvido">¿Olvidaste la contraseña?</a>
        </div>
        
        <div class="input-con-ico">
          <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required />
          <span class="icono-ojo" onclick="togglePassword('contrasena', this)">
           <ion-icon name="eye-off-outline"></ion-icon>
          </span>
        </div>

        <!-- Botones -->
        <div class="botones">
          <button type="submit" class="btn">INICIAR SESIÓN</button>
          <a href="../VistaRegistro/registrarCliente.php" class="btn">REGISTRARME</a>
        </div>
      </form>

      <p class="conecta" id="conecta">O CONÉCTATE CON</p>
      <div class="redes" id="botonGoogle">
        <a href="../../loginGoogle/index.php" class="red google">
          <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google" />
          Google
        </a>
      </div>
      
    </div>
    <div class="imagen"></div>
  </div>

  <!-- Scripts -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="../../Ojo.js"></script>
  <script src="ocultarGoogle.js"></script>
  <script src="contrasenaOlvido.js"></script>
</body>
</html>
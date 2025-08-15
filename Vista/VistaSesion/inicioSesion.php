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

      <?php if (isset($_GET['error'])): ?>
        <div style="color: red; text-align: center; margin-bottom: 15px;">
          <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
      <?php endif; ?>

      <h1>INICIAR SESIÓN</h1>  
      <form action="../../Controlador/minisControlador/validarSesion.php" method="POST">
        <input type="hidden" name="iniciar_sesion" value="1" />

        <div class="tipo-usuario" style="margin-bottom: 15px;">
          <label>
            <input type="radio" name="tipo_usuario" value="cliente" checked> Cliente
          </label>
          <label style="margin-left: 15px;">
            <input type="radio" name="tipo_usuario" value="empresa"> Empresa
          </label>
        </div>

        <label>Correo Electronico</label>
        <input type="email" name="correo" placeholder="Correo Electronico" required />

        <div class="fila">
          <label>Contraseña</label>
          <a href="#" class="olvido">¿Olvidaste la contraseña?</a>
        </div>
        
        <div class="input-con-ico">
          <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required />
          <span class="icono-ojo" onclick="togglePassword('contrasena', this)">
           <ion-icon name="eye-off-outline"></ion-icon>
          </span>
        </div>

        <div class="botones">
          <button type="submit" class="btn">INICIAR SESION</button>
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

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="../../Ojo.js"></script>
  <script src="ocultarGoogle.js"></script>
</body>
</html>
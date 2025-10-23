<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crear Cuenta</title>
  <link rel="stylesheet" href="Cliente.css" />
</head>
<body>

   
   
  <div class="contenedor">
    <div class="formulario">

     <?php if (isset($_GET['exito']) && $_GET['exito'] == 1): ?>
    <div style="color: green; text-align: center; margin-bottom: 15px;">
        ¡Registro exitoso! Ahora puedes iniciar sesión.
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div style="color: red; text-align: center; margin-bottom: 15px;">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

     
    
    
      <h1>CREA UNA CUENTA</h1>
      <form action= "/ClickSoft/Controlador/minisControlador/validarRegistro.php" method="POST">
        
        <input type="hidden" name="guardar" value="1" />
        
        <label for="nombre">Nombre Completo</label>
        <input type="text" id="nombre" name="nombre" placeholder="Nombre Completo" required />
        
        <label for="correo">Correo Electronico</label>
        <input type="email" id="correo" name="correo" placeholder="Correo Electronico" required />
        
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

        <p class="login">
          ¿Ya tienes una cuenta? <a href="../VistaSesion/inicioSesion.php">Iniciar Sesión</a>
        </p>
      </form>
    </div>
    <div class="imagen"></div>
  </div>

    
    <script src="../../Ojo.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
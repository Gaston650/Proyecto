<?php
session_start();
$img = (isset($_SESSION['user_image']) && !empty($_SESSION['user_image']))
    ? $_SESSION['user_image']
    : 'perfil-vacio.png';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil - Cliente</title>
    <link rel="stylesheet" href="editarPerfil.css">
</head>
<body>

  <div class="container">
    <h2>Editar Perfil</h2>

    <form action="editarPerfilCliente.php" method="POST">
      
      <!-- Imagen de perfil -->
    <div class="profile-pic">
        <img src="<?php echo htmlspecialchars($img); ?>" alt="Foto de perfil">
        <input type="file" name="imagenPerfil" accept="image/*">
    </div>

      <!-- Nombre -->
      <div class="form-group">
        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre">
      </div>

     <!-- Email -->
    <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>" disabled>
    </div>

      <!-- Teléfono -->
      <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input type="text" id="telefono" name="telefono">
      </div>

      <!-- Dirección -->
      <div class="form-group">
        <label for="direccion">Dirección</label>
        <textarea id="direccion" name="direccion" rows="2"></textarea>
      </div>

      <!-- Preferencias -->
      <div class="form-group">
        <label>Notificaciones</label>
        <div class="checkbox-group">
          <input type="checkbox" id="notif-email" name="notif_email" checked>
          <label for="notif-email">Recibir notificaciones por correo</label>
        </div>
      </div>

      <!-- Método de pago -->
      <div class="form-group">
        <label for="metodo_pago">Método de pago predeterminado</label>
        <select id="metodo_pago" name="metodo_pago">
          <option value="tarjeta" selected>Tarjeta</option>
          <option value="efectivo">Efectivo</option>
          <option value="paypal">PayPal</option>
        </select>
      </div>

      <!-- Contraseña -->
      <div class="form-group">
        <label for="password">Cambiar contraseña</label>
        <input type="password" id="password" name="password" placeholder="Nueva contraseña">
      </div>

      <!-- Acciones -->
      <div class="actions">
        <button type="reset" class="btn-cancel">Cancelar</button>
        <button type="submit" class="btn-save">Guardar cambios</button>
      </div>

    </form>

     <div class="volver-inicio">
      <a href="../VistaPrincipal/home.php" class="btn-volver">Volver al inicio</a>
    </div>
  </div>

</body>
</html>

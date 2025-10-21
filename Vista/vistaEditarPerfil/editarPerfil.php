<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';
require_once __DIR__ . '/../../Modelo/modeloRegistro.php'; // agregamos el modelo

$perfilWrapper = new perfilControladorWrapper();
$perfil = $perfilWrapper->obtenerPerfil($_SESSION['user_id']) ?? [];

// Imagen de perfil
if (isset($_SESSION['user_image']) && !empty($_SESSION['user_image'])) {
    // Si inició sesión con Google
    $img = $_SESSION['user_image'];
} elseif (!empty($perfil['foto_perfil'])) {
    // Si tiene imagen guardada en la base de datos
    $img = $perfil['foto_perfil'];
} else {
    // Imagen por defecto
    $img = '../../IMG/perfil-vacio.png';
}


// Método de pago
$metodoPagoActual = $perfilWrapper->obtenerMetodoPago($_SESSION['user_id']) ?? 'tarjeta';

// ✅ Obtenemos el correo desde el modelo
$usuarioModelo = new usuarioModelo();
$usuario = $usuarioModelo->obtenerUsuarioPorId($_SESSION['user_id']);
$email = $usuario['email'] ?? '';
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

    <?php if (isset($_GET['exito'])): ?>
      <div class="mensaje-exito">
        Tus cambios se guardaron correctamente.
      </div>
    <?php elseif (isset($_GET['error'])): ?>
      <div class="mensaje-error">
        <?php echo htmlspecialchars($_GET['error']); ?>
      </div>
    <?php endif; ?>

    <form action="../../Controlador/minisControlador/validarEditarPerfil.php" method="POST" enctype="multipart/form-data">
      
      <!-- Imagen de perfil -->
      <div class="profile-pic">
        <img src="<?php echo htmlspecialchars($img); ?>" alt="Foto de perfil">
        <input type="file" name="imagenPerfil" accept="image/*">
      </div>

      <!-- Nombre -->
      <div class="form-group">
        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre"
          value="<?php echo isset($_SESSION['user_nombre']) ? htmlspecialchars($_SESSION['user_nombre']) : ''; ?>" disabled>
      </div>
<!-- Email (no editable) -->
<div class="form-group">
    <label for="email">Correo electrónico</label>
    <input type="email" id="email" name="email" 
        value="<?php echo htmlspecialchars($email); ?>" 
        disabled>
</div>




      <!-- Dirección -->
      <div class="form-group">
        <label for="direccion">Dirección</label>
        <textarea id="direccion" name="direccion" rows="2"><?php echo htmlspecialchars($perfil['direccion'] ?? ''); ?></textarea>
      </div>

      <!-- Ciudad -->
      <div class="form-group">
        <label for="ciudad">Ciudad</label>
        <input type="text" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($perfil['ciudad'] ?? ''); ?>">
      </div>

      <!-- Biografía -->
      <div class="form-group">
        <label for="biografia">Biografía</label>
        <textarea id="biografia" name="biografia" rows="4"><?php echo htmlspecialchars($perfil['biografia'] ?? ''); ?></textarea>
      </div>

      <!-- Método de pago -->
      <div class="form-group">
        <label for="metodo_pago">Método de pago predeterminado</label>
        <select id="metodo_pago" name="metodo_pago">
          <option value="tarjeta" <?php echo ($metodoPagoActual == 'tarjeta') ? 'selected' : ''; ?>>Tarjeta</option>
          <option value="efectivo" <?php echo ($metodoPagoActual == 'efectivo') ? 'selected' : ''; ?>>Efectivo</option>
          <option value="paypal" <?php echo ($metodoPagoActual == 'paypal') ? 'selected' : ''; ?>>PayPal</option>
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
      <a href="../../Controlador/cerrar_sesion.php" class="btn-cerrar-sesion">Cerrar Sesión</a>
    </div>
  </div>

</body>
</html>

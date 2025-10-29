<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';
require_once __DIR__ . '/../../Modelo/modeloRegistro.php'; // modelo
require_once __DIR__ . '/../../Controlador/minisControlador/autologin.php';
require_once __DIR__ . '/../../Modelo/modeloPerfil.php'; // modelo de perfil
require_once __DIR__ . '/../../conexion.php';

// ✅ Verificar que el usuario esté logueado
if (empty($_SESSION['user_id'])) {
    // No hay sesión activa, redirigir al login
    header('Location: ../../index.php');
    exit();
}

// Conexión y modelo de perfil
$conexion = new conexion();
$conn = $conexion->conectar();
$perfilModelo = new perfilModelo($conn);

// Obtener datos de perfil
$perfilWrapper = new perfilControladorWrapper();
$perfil = $perfilWrapper->obtenerPerfil($_SESSION['user_id']) ?? [];

// 🖼️ Determinar imagen de perfil (prioridad: Google > BD en sesión > tabla perfil > default)
$fotoPerfil = '../../IMG/perfil-vacio.png'; // default

if (!empty($_SESSION['imagen']) && str_starts_with($_SESSION['imagen'], 'https://')) {
    // 1️⃣ Imagen de Google
    $fotoPerfil = $_SESSION['imagen'];
} elseif (!empty($_SESSION['user_image']) && $_SESSION['user_image'] !== '../../IMG/perfil-vacio.png') {
    // 2️⃣ Imagen guardada en sesión desde BD
    $fotoPerfil = $_SESSION['user_image'];
} elseif (!empty($perfil['foto_perfil'])) {
    // 3️⃣ Imagen desde la tabla perfil
    $fotoPerfil = $perfil['foto_perfil'];
}

$img = $fotoPerfil;

// Método de pago
$metodoPagoActual = $perfilWrapper->obtenerMetodoPago($_SESSION['user_id']) ?? 'tarjeta';

// Obtener correo del usuario
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

      <!-- Acciones -->
      <div class="actions">
        <button type="reset" class="btn-cancel">Cancelar</button>
        <button type="submit" class="btn-save">Guardar cambios</button>
      </div>

    </form>

    <div class="volver-inicio">
      <a href="../VistaPrincipal/home.php" class="btn-volver">Volver al inicio</a>
      <a href="../../cerrar_sesion.php" class="btn-cerrar-sesion">Cerrar Sesión</a>
    </div>
  </div>

</body>
</html>

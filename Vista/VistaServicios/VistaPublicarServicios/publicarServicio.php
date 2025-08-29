<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$mensaje = $_GET['mensaje'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Publicar Servicio</title>
<link rel="stylesheet" href="publicarServicio.css">
</head>
<body>
<div class="container">
    <h1>Publicar nuevo servicio</h1>

    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <form method="POST" action="../../../Controlador/minisControlador/validarPublicarServicio.php">
        <label>Título</label>
        <input type="text" name="titulo" placeholder="Nombre del servicio" required>

        <label>Descripción</label>
        <textarea name="descripcion" placeholder="Detalles del servicio" required></textarea>

        <label>Ubicación</label>
        <input type="text" name="ubicacion" placeholder="Ciudad o zona" required>

        <label>Precio</label>
        <input type="number" step="0.01" name="precio" placeholder="Precio en $" required>

        <label>Disponibilidad</label>
        <input type="text" name="disponibilidad" placeholder="Días y horarios disponibles" required>

        <label>Estado</label>
        <select name="estado" required>
            <option value="Disponible">Disponible</option>
            <option value="Ocupado">Ocupado</option>
            <option value="Cancelado">Cancelado</option>
        </select>

        <div class="botones">
            <button type="submit">Publicar Servicio</button>
            <a href="../../VistaPrincipal/homeEmpresa.php">← Volver</a>
        </div>
    </form>
</div>
</body>
</html>

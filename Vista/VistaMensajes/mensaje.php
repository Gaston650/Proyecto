<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$id_emisor = $_SESSION['user_id'];
$tipo_emisor = 'usuario';
$id_receptor = $_GET['proveedor'] ?? null;
$tipo_receptor = 'empresa';
$id_reserva  = $_GET['reserva'] ?? null;

$controlador = new mensajeControlador();
$data = $controlador->manejarConversacion($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva);

$mensajes = $data['mensajes'];
$exito = $data['exito'];
$error = $data['error'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mensajes</title>
<link rel="stylesheet" href="mensaje.css">
</head>
<body>
<header>
<h2>💬 Chat con el proveedor</h2>
<a href="../VistaReservas/reservas.php" class="volver">← Volver</a>
</header>

<main>
<?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

<div class="chat-box">
<?php if ($mensajes): ?>
    <?php while($m = $mensajes->fetch_assoc()): ?>
        <div class="mensaje <?= ($m['id_emisor'] == $id_emisor && $m['tipo_emisor'] == 'usuario') ? 'emisor' : 'receptor' ?>">
            <strong><?= ($m['id_emisor'] == $id_emisor && $m['tipo_emisor'] == 'usuario') ? 'Tú' : 'Proveedor' ?>:</strong> <?= htmlspecialchars($m['contenido']) ?>
            <span class="fecha"><?= date('d/m/Y H:i', strtotime($m['fecha_envio'])) ?></span>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
</div>

<?php if ($id_receptor && $id_reserva): ?>
<form method="POST">
    <input type="text" name="contenido" placeholder="Escribe tu mensaje..." required>
    <button type="submit">Enviar</button>
</form>
<?php endif; ?>
</main>
</body>
</html>
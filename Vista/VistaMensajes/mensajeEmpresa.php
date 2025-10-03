<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión como empresa.");
    exit();
}

$id_empresa = $_SESSION['user_id'];
$id_cliente = (isset($_GET['cliente']) && (int)$_GET['cliente'] > 0) ? (int)$_GET['cliente'] : null;
$id_reserva = (isset($_GET['reserva']) && (int)$_GET['reserva'] > 0) ? (int)$_GET['reserva'] : null;

$mensajesWrapper = new mensajesControladorWrapper();

if ($id_cliente !== null && $id_reserva !== null) {
    $mensajes = $mensajesWrapper->obtenerConversacion($id_cliente, $id_empresa, $id_reserva);
    $mensajesWrapper->marcarMensajesLeidos($id_empresa, $id_cliente, $id_reserva);
    // DEBUG:
    // var_dump($mensajes->fetch_all(MYSQLI_ASSOC));
} else {
    $mensajes = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chat con Cliente</title>
<link rel="stylesheet" href="mensajeEmpresa.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <h2>💬 Chat con Cliente</h2>
    <a href="../VistaNotificaciones/notificacionesEmpresa.php" class="volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>
</header>

<main>
    <div class="chat-box">
        <?php if (!empty($mensajes) && $mensajes instanceof mysqli_result && $mensajes->num_rows > 0): ?>
        <?php while ($m = $mensajes->fetch_assoc()) : ?>
    <div class="mensaje <?= ($m['id_emisor'] == $id_empresa && $m['tipo_emisor'] == 'empresa') ? 'emisor' : 'receptor' ?>">
        <strong>
            <?= ($m['id_emisor'] == $id_empresa && $m['tipo_emisor'] == 'empresa') ? 'Tú' : htmlspecialchars($m['nombre_cliente']) ?>:
        </strong>
        <p><?= htmlspecialchars($m['contenido']) ?></p>
        <span class="fecha"><?= date('d/m/Y H:i', strtotime($m['fecha_envio'])) ?></span>
    </div>
<?php endwhile; ?>
        <?php elseif ($id_cliente === null || $id_reserva === null): ?>
            <p>Faltan datos para mostrar la conversación.</p>
        <?php else: ?>
            <p>No hay mensajes con este cliente.</p>
        <?php endif; ?>
    </div>

    <?php if ($id_cliente): ?>
 <form method="POST" action="../../Controlador/minisControlador/enviarMensajeEmpresa.php">
    <input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">
    <input type="hidden" name="id_reserva" value="<?= $id_reserva ?>">
    <input type="text" name="contenido" placeholder="Escribe tu mensaje..." required>
    <button type="submit">Enviar</button>
</form>
    <?php endif; ?>
</main>

<script src="mensajesEmpresa.js"></script>

</body>
</html>
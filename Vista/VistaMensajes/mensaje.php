<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';
require_once __DIR__ . '/../../Controlador/minisControlador/procesarMensajes.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$id_cliente = $_SESSION['user_id'];
$id_empresa  = $_GET['proveedor'] ?? null;
$id_reserva  = $_GET['reserva'] ?? null;

if (!$id_empresa || !$id_reserva) die("Error: faltan parámetros de la conversación.");

$wrapper = new mensajesClienteWrapper();

// ✅ Marcar como leídos los mensajes de la empresa hacia el cliente
$wrapper->marcarMensajesLeidos($id_cliente, $id_empresa, $id_reserva);

// Enviar mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['contenido'])) {
    $contenido = trim($_POST['contenido']);
    $wrapper->enviarMensaje($id_cliente, $id_empresa, $id_reserva, $contenido);
    header("Location: mensaje.php?proveedor=$id_empresa&reserva=$id_reserva");
    exit();
}

// Obtener mensajes
$conversation = $wrapper->obtenerConversacion($id_cliente, $id_empresa, $id_reserva)['mensajes'] ?? [];

// Procesar para obtener nombre de la empresa
$nombre_empresa = 'Proveedor';
foreach ($conversation as $m) {
    if ($m['tipo_emisor'] === 'empresa') {
        $nombre_empresa = $m['nombre_emisor'] ?? 'Proveedor';
        break;
    }
}
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
    <h2>💬 Chat con <?= htmlspecialchars($nombre_empresa) ?></h2>
    <a href="../VistaNotificaciones/notificaciones.php" class="volver">← Volver</a>
</header>

<main>
<div class="chat-box">
<?php if (!empty($conversation)): ?>
    <?php foreach ($conversation as $m): ?>
        <div class="mensaje <?= ($m['id_emisor'] == $id_cliente && $m['tipo_emisor'] == 'usuario') ? 'emisor' : 'receptor' ?>">
            <strong><?= ($m['id_emisor'] == $id_cliente) ? 'Tú' : htmlspecialchars($nombre_empresa) ?>:</strong>
            <?= htmlspecialchars($m['contenido']) ?>
            <span class="fecha"><?= date('d/m/Y H:i', strtotime($m['fecha_envio'])) ?></span>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="vacio">No hay mensajes en esta conversación.</p>
<?php endif; ?>
</div>

<form method="POST" class="form-mensaje">
    <input type="text" name="contenido" placeholder="Escribe tu mensaje..." required>
    <button type="submit">Enviar</button>
</form>
</main>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/../../Controlador/minisControlador/controladorMensajesEmpresa.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión como empresa.");
    exit();
}

$id_empresa = $_SESSION['user_id'];
$id_cliente = $_GET['cliente'] ?? null;
$id_reserva = $_GET['reserva'] ?? null;

if (!$id_cliente || !$id_reserva) {
    die("Error: faltan parámetros de la conversación.");
}

$mensajeWrapper = new mensajeControladorEmpresa();

// Marcar mensajes como leídos
$mensajeWrapper->marcarMensajesLeidos($id_empresa, $id_cliente, $id_reserva);

// Procesar envío de mensaje si hay POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenido = trim($_POST['contenido'] ?? '');
    if (!empty($contenido)) {
        $mensajeWrapper->insertarMensaje($id_empresa, 'empresa', $id_cliente, 'usuario', $id_reserva, $contenido);
        header("Location: mensajeEmpresa.php?cliente=$id_cliente&reserva=$id_reserva");
        exit();
    } else {
        $error = "⚠️ El mensaje no puede estar vacío.";
    }
}

// Obtener la conversación completa
$mensajes = $mensajeWrapper->obtenerConversacion($id_cliente, $id_empresa, $id_reserva);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mensajes - Empresa</title>
<link rel="stylesheet" href="mensajeEmpresa.css">
</head>
<body>
<header>
    <h2>💬 Chat con el cliente</h2>
    <a href="../VistaNotificaciones/notificacionesEmpresa.php" class="volver">← Volver</a>
</header>

<main>
<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div class="chat-box">
<?php if ($mensajes): ?>
    <?php foreach ($mensajes as $m): ?>
        <div class="mensaje <?= ($m['id_emisor'] == $id_empresa && $m['tipo_emisor'] == 'empresa') ? 'emisor' : 'receptor' ?>">
            <strong><?= ($m['id_emisor'] == $id_empresa && $m['tipo_emisor'] == 'empresa') ? 'Tú' : 'Cliente' ?>:</strong>
            <?= htmlspecialchars($m['contenido']) ?>
            <span class="fecha"><?= date('d/m/Y H:i', strtotime($m['fecha_envio'])) ?></span>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="vacio">No hay mensajes en esta conversación.</p>
<?php endif; ?>
</div>

<?php if ($id_cliente && $id_reserva): ?>
<form method="POST" class="form-mensaje">
    <input type="text" name="contenido" placeholder="Escribe tu mensaje..." required>
    <button type="submit">Enviar</button>
</form>
<?php endif; ?>
</main>
</body>
</html>

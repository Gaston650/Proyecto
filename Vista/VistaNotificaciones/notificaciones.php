<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';
require_once __DIR__ . '/../../Controlador/minisControlador/procesarMensajes.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$id_cliente = $_SESSION['user_id'];
$wrapper = new mensajesClienteWrapper();

$mensajes_raw = $wrapper->obtenerMensajesCliente($id_cliente);
$mensajes = procesarMensajesCliente($mensajes_raw);

uasort($mensajes, fn($a, $b) => strtotime($b['fecha_envio']) - strtotime($a['fecha_envio']));
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notificaciones</title>
<link rel="stylesheet" href="notificaciones.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<header>
    <h2>🔔 Notificaciones</h2>
</header>
<a href="../VistaReservas/reservas.php" class="volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>

<div class="container">
    <ul class="chat-list">
        <?php if (empty($mensajes)): ?>
            <li class="chat-item">No hay mensajes nuevos.</li>
        <?php else: ?>
            <?php foreach ($mensajes as $empresa_id => $m): ?>
                <li class="chat-item" onclick="window.location.href='../VistaMensajes/mensaje.php?proveedor=<?= $empresa_id ?>&reserva=<?= $m['id_reserva'] ?>'">
                    <div class="chat-info">
                        <span class="chat-nombre"><?= htmlspecialchars($m['nombre_empresa']) ?></span>
                        <span class="chat-ultimo"><?= htmlspecialchars($m['ultimo_mensaje']) ?></span>
                    </div>
                    <div class="chat-meta">
                        <span class="chat-fecha"><?= date('d/m H:i', strtotime($m['fecha_envio'])) ?></span>
                        <?php if ($m['no_leidos'] > 0): ?>
                            <span class="chat-badge"><?= $m['no_leidos'] ?></span>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>
</body>
</html>

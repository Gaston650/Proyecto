<?php
session_start();
require_once __DIR__ . '/../../Controlador/minisControlador/controladorMensajesEmpresa.php';
require_once __DIR__ . '/../../Controlador/minisControlador/procesarMensajeEmpresa.php';
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión como empresa.");
    exit();
}

$id_empresa = $_SESSION['user_id'];

// --- Mensajes con clientes ---
$mensajeWrapper = new mensajesEmpresaWrapper();
$mensajes_raw = $mensajeWrapper->obtenerMensajes($id_empresa);
$mensajes = procesarMensajesEmpresa($mensajes_raw);
uasort($mensajes, fn($a, $b) => strtotime($b['fecha_envio']) - strtotime($a['fecha_envio']));

// --- Notificaciones ---
$notifWrapper = new notificacionControladorWrapper();
$notificaciones = $notifWrapper->obtenerTodas($id_empresa);

// Clasificar notificaciones según tipo
foreach ($notificaciones as &$n) {
    if ($n['tipo'] === 'alerta') {
        if (strpos($n['mensaje'], 'ha reprogramado') !== false) {
            $n['clase'] = 'alerta reprogramacion';
        } elseif (strpos($n['mensaje'], 'ha cancelado') !== false) {
            $n['clase'] = 'alerta cancelacion';
        } else {
            $n['clase'] = 'alerta';
        }
    } else {
        $n['clase'] = $n['estado'] === 'no leída' ? 'no-leida' : 'leida';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notificaciones Empresa</title>
<link rel="stylesheet" href="notificacionesEmpresa.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<header>
    <h2>🔔 Notificaciones</h2>
</header>
<a href="../VistaPrincipal/homeEmpresa.php" class="volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>

<div class="container">

    <!-- Notificaciones del sistema y alertas -->
    <h3 class="titulo-seccion">📢 Actividad del sistema</h3>
    <?php if(empty($notificaciones)): ?>
        <p class="vacio">No tienes notificaciones.</p>
    <?php else: ?>
        <ul class="notif-list">
            <?php foreach($notificaciones as $n): ?>
                <li class="notif-item <?= $n['clase'] ?>">
                    <div class="notif-info">
                        <p><?= htmlspecialchars($n['mensaje']) ?></p>
                        <span class="fecha"><?= date('d/m/Y H:i', strtotime($n['fecha_envio'])) ?></span>
                        <?php if($n['estado'] === 'no leída'): ?>
                            <span class="notif-badge">!</span>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Mensajes con clientes -->
    <h3 class="titulo-seccion">💬 Conversaciones con clientes</h3>
    <?php if(empty($mensajes)): ?>
        <p class="vacio">No hay mensajes nuevos.</p>
    <?php else: ?>
        <ul class="chat-list">
            <?php foreach($mensajes as $cliente_id => $m): ?>
                <li class="chat-item" onclick="window.location.href='../VistaMensajes/mensajeEmpresa.php?cliente=<?= $cliente_id ?>&reserva=<?= $m['id_reserva'] ?>'">
                    <div class="chat-info">
                        <span class="chat-nombre"><?= htmlspecialchars($m['nombre_cliente'] ?? 'Cliente') ?></span>
                        <span class="chat-ultimo"><?= htmlspecialchars($m['ultimo_mensaje'] ?? '') ?></span>
                    </div>
                    <div class="chat-meta">
                        <span class="chat-fecha"><?= isset($m['fecha_envio']) ? date('d/m H:i', strtotime($m['fecha_envio'])) : '' ?></span>
                        <?php if(!empty($m['no_leidos']) && $m['no_leidos'] > 0): ?>
                            <span class="chat-badge"><?= $m['no_leidos'] ?></span>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>
</body>
</html>

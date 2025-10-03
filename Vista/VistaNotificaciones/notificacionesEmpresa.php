<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';
require_once __DIR__ . '/../../Controlador/minisControlador/procesarMensajes.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión como empresa.");
    exit();
}

$id_empresa = $_SESSION['user_id'];

$mensajeWrapper = new mensajesControladorWrapper();
$mensajes_raw = $mensajeWrapper->obtenerMensajesEmpresa($id_empresa);
$mensajes = procesarMensajes($mensajes_raw);

// Ordenar mensajes por fecha descendente SIN perder las claves
uasort($mensajes, function($a, $b) {
    return strtotime($b['fecha_envio']) - strtotime($a['fecha_envio']);
});
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notificaciones - ClickSoft</title>
<link rel="stylesheet" href="notificacionesEmpresa.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<header>
    <h2>🔔 Notificaciones</h2>
</header>
<a href="../VistaPrincipal/homeEmpresa.php" class="volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>

<div class="container">
    <ul class="chat-list">
        <?php if(empty($mensajes)): ?>
            <li class="chat-item">No hay mensajes nuevos.</li>
        <?php else: ?>
            <?php foreach($mensajes as $cliente_id => $m): ?>
                <li class="chat-item" onclick="window.location.href='../VistaMensajes/mensajeEmpresa.php?cliente=<?= $cliente_id ?>&reserva=<?= $m['id_reserva'] ?>'">
                    <div class="chat-info">
                        <span class="chat-nombre"><?= htmlspecialchars($m['nombre_cliente']) ?></span>
                        <span class="chat-ultimo"><?= htmlspecialchars($m['ultimo_mensaje']) ?></span>
                    </div>
                    <div class="chat-meta">
                        <span class="chat-fecha"><?= date('d/m H:i', strtotime($m['fecha_envio'])) ?></span>
                        <?php if($m['no_leidos'] > 0): ?>
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
<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

// Instanciar wrapper de reservas
$reservasWrapper = new reservasControladorWrapper();
$id_cliente = $_SESSION['user_id'];
$reservas = $reservasWrapper->verReservasCliente($id_cliente);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="reservas.css">
</head>
<body>
<header>
    <nav>
        <div class="usuario-info">
            <?php 
                $img = !empty($_SESSION['user_image']) ? $_SESSION['user_image'] : '../../img/perfil-vacio.png';
            ?>
            <a href="../vistaEditarPerfil/editarPerfil.php">
                <div class="foto-perfil" style="background-image: url(<?= htmlspecialchars($img) ?>);"></div>
            </a>
            <span class="nombre-usuario">
                <?= htmlspecialchars($_SESSION['user_nombre'] ?? 'Usuario') ?>
            </span>
        </div>
        <div class="nav-links">
            <ul>
                <li><a href="../VistaPrincipal/home.php">Inicio</a></li>
                <li><a href="../VistaServicios/servicios.php">Servicios</a></li>
                <li><a href="../VistaHistorial/historial.php">Historial</a></li>
                <li><a href="reservas.php" class="active">Reservas</a></li>
            </ul>
        </div>
    </nav>
</header>

<main>
<h2 class="titulo">📅 Mis Reservas Activas</h2>
<p class="subtitulo">Aquí puedes gestionar tus reservas pendientes y confirmadas.</p>

<div class="filtros">
    <select class="filtro-estado" onchange="filtrarEstado(this.value)">
        <option value="">Todos los estados</option>
        <option value="pendiente">Pendiente</option>
        <option value="confirmada">Confirmada</option>
        <option value="cancelada">Cancelada</option>
        <option value="completada">Completada</option>
    </select>
</div>

<div class="reservas-grid">
    <?php while($r = $reservas->fetch_assoc()): ?>
        <div class="reserva-card estado-<?= strtolower($r['estado_reserva']) ?>" data-id="<?= $r['id_reserva'] ?>">
            <h3><?= htmlspecialchars($r['nombre_servicio']) ?></h3>
            <p><strong>Proveedor:</strong> <?= htmlspecialchars($r['nombre_proveedor']) ?></p>
            <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($r['fecha_reserva'])) ?> - <?= date('H:i', strtotime($r['hora_reserva'])) ?> hs</p>
            <p><strong>Estado:</strong> <span class="estado <?= strtolower($r['estado_reserva']) ?>"><?= ucfirst($r['estado_reserva']) ?></span></p>
            <p><strong>Monto:</strong> $<?= number_format($r['monto'] ?? 0, 0, ',', '.') ?></p>
            <div class="acciones">
               <a href="../VistaMensajes/mensaje.php?proveedor=<?= $r['id_proveedor'] ?>&reserva=<?= $r['id_reserva'] ?>" class="btn-mensaje">Enviar Mensaje</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</main>

<div id="modalCancelar" class="modal">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal()">&times;</span>
        <h3>Cancelar Reserva</h3>
        <p>¿Estás seguro que deseas cancelar esta reserva?</p>
        <button class="btn-confirmar" id="confirmarCancelar">Sí, cancelar</button>
        <button class="btn-cerrar" onclick="cerrarModal()">Cancelar</button>
    </div>
</div>

<script src="cancelar.js"></script>
<script src="../VistaPrincipal/verPagina.js"></script>   
</body>
</html>

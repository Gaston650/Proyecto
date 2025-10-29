<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';
require_once __DIR__ . '/../../Controlador/minisControlador/autologin.php';


// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$id_cliente = $_SESSION['user_id'];
$reservasWrapper = new reservasControladorWrapper();
$reservas = $reservasWrapper->verReservasCliente($id_cliente);

// Contador de mensajes
$mensajesWrapper = new mensajesClienteWrapper();
$mensajesNoLeidos = $mensajesWrapper->contarMensajesNoLeidos($id_cliente);

// Wrapper de pagos
$pagoWrapper = new pagoControladorWrapper();

$conn = (new Conexion())->conectar();

// 🖼️ Determinar imagen de perfil
$fotoPerfil = '../../IMG/perfil-vacio.png'; // Imagen por defecto

// Prioridad: Google > BD > default
if (!empty($_SESSION['imagen']) && str_starts_with($_SESSION['imagen'], 'https://')) {
    // 1️⃣ Imagen de Google
    $fotoPerfil = $_SESSION['imagen'];
} elseif (!empty($_SESSION['user_image']) && $_SESSION['user_image'] !== '../../IMG/perfil-vacio.png') {
    // 2️⃣ Imagen de BD
    $fotoPerfil = $_SESSION['user_image'];
} else {
    // 3️⃣ Intentar obtener desde la tabla perfil por si no se guardó en sesión
    if (isset($_SESSION['user_id'])) {
        $perfil = $perfilModelo->obtenerPerfil($_SESSION['user_id']);
        if ($perfil && !empty($perfil['foto_perfil'])) {
            $fotoPerfil = $perfil['foto_perfil'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mis Reservas</title>
<link rel="stylesheet" href="reservas.css">
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
<header>
    <nav>
       <div class="usuario-info">
               <a href="../vistaEditarPerfil/editarPerfil.php" title="Editar perfil">
                   <div class="foto-perfil"
                        style="background-image: url('<?php echo htmlspecialchars($fotoPerfil, ENT_QUOTES, 'UTF-8'); ?>');">
                   </div>
               </a>
               <span class="nombre-usuario">
                   <?php
                       if (isset($_SESSION['user_nombre'])) {
                           echo htmlspecialchars($_SESSION['user_nombre']);
                       } elseif (isset($_SESSION['nombre_empresa'])) {
                           echo htmlspecialchars($_SESSION['nombre_empresa']);
                       } else {
                           echo 'Usuario';
                       }
                   ?>
               </span>
           </div>

        <div class="notificaciones">
            <a href="../VistaNotificaciones/notificaciones.php" title="Ver Notificaciones">
                <ion-icon name="notifications-outline"></ion-icon>
                <span class="contador"><?= $mensajesNoLeidos ?></span>
            </a>
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

<div class="reservas-grid">
<?php while($r = $reservas->fetch_assoc()): ?>
    <?php
        // Verificamos si la reserva tiene un pago realizado
        $pagosReserva = $pagoWrapper->obtenerPagosPorReserva($r['id_reserva']);
        if (!is_array($pagosReserva)) $pagosReserva = [];
        $pagado = false;
        foreach ($pagosReserva as $pago) {
            if (isset($pago['metodo_pago']) && $pago['metodo_pago'] === 'realizado') {
                $pagado = true;
                break;
            }
        }
    ?>
    <div class="reserva-card estado-<?= strtolower($r['estado_reserva']) ?>" data-id="<?= $r['id_reserva'] ?>">
        <h3><?= htmlspecialchars($r['nombre_servicio']) ?></h3>
        <p><strong>Proveedor:</strong> <?= htmlspecialchars($r['nombre_proveedor']) ?></p>
        <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($r['fecha_reserva'])) ?> - <?= date('H:i', strtotime($r['hora_reserva'])) ?> hs</p>
        <p><strong>Estado:</strong> <span class="estado <?= strtolower($r['estado_reserva']) ?>"><?= ucfirst($r['estado_reserva']) ?></span></p>
        <p><strong>Monto:</strong> $<?= number_format($r['monto'] ?? 0, 0, ',', '.') ?></p>

        <?php if (!$pagado): ?>
        <div class="acciones">
            <a href="../VistaMensajes/mensaje.php?proveedor=<?= $r['id_proveedor'] ?>&reserva=<?= $r['id_reserva'] ?>" class="btn-mensaje">Enviar Mensaje</a>

            <?php if (strtolower($r['estado_reserva']) === 'completada'): ?>
                <a href="../VistaPagos/checkout.php?title=<?= urlencode($r['nombre_servicio']) ?>&price=<?= $r['monto'] ?>&id_reserva=<?= $r['id_reserva'] ?>" class="btn-pagar">💳 Pagar</a>
            <?php elseif (strtolower($r['estado_reserva']) !== 'cancelada'): ?>
                <button class="btn-reprogramar" onclick="abrirModalReprogramar(<?= $r['id_reserva'] ?>)">Reprogramar</button>
                <button class="btn-cancelar" onclick="abrirModalCancelar(<?= $r['id_reserva'] ?>)">Cancelar</button>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
<?php endwhile; ?>
</div>
</main>

<!-- Modal Cancelar Reserva -->
<div id="modalCancelar" class="modal">
    <div class="modal-contenido">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <h2>Cancelar Reserva</h2>
        <p>¿Estás seguro que deseas cancelar esta reserva?</p>
        <div class="acciones-modal">
            <a id="confirmarCancelar" href="#" class="btn-confirmar">Sí, cancelar</a>
            <button onclick="cerrarModal()" class="btn-cancelar">No</button>
        </div>
    </div>
</div>

<!-- Modal Reprogramar Reserva -->
<div id="modalReprogramar" class="modal">
    <div class="modal-contenido">
        <span class="close" onclick="cerrarModalReprogramar()">&times;</span>
        <h2>Reprogramar Reserva</h2>
        <p>Selecciona nueva fecha y hora:</p>
        <form id="formReprogramar" onsubmit="return false;">
            <label for="nuevaFecha">Fecha:</label>
            <input type="date" id="nuevaFecha" required>
            <label for="nuevaHora">Hora:</label>
            <input type="time" id="nuevaHora" required>
            <div class="acciones-modal">
                <button id="confirmarReprogramar" class="btn-confirmar">Guardar</button>
                <button type="button" onclick="cerrarModalReprogramar()" class="btn-cancelar">Cancelar</button>
            </div>
        </form>
    </div>
</div>


<script src="reservas.js"></script>
<script src="../VistaPrincipal/verPagina.js"></script>
</body>
</html>
                
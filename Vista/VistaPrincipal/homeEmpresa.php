<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

// Verificar sesión de empresa
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión como empresa.");
    exit();
}

$empresa_id = $_SESSION['user_id'];
$logo = '/ClickSoft/IMG/empresas/' . ($_SESSION['user_image'] ?? 'default.png');

// --------------------------
// VENTAS DEL MES
// --------------------------
$pagoWrapper = new pagoControladorWrapper();
$total_ventas_mes = $pagoWrapper->obtenerVentasDelMes($empresa_id);

// --------------------------
// HISTORIAL / RESERVAS
// --------------------------
$historialWrapper = new historialControladorWrapper();
$reservas_proximas = $historialWrapper->listarReservasEmpresa($empresa_id);
$pendientes = array_filter($reservas_proximas, fn($r) => $r['estado_reserva'] === 'pendiente');
$total_reservas = count($pendientes);

// --------------------------
// MENSAJES NO LEÍDOS
// --------------------------
$mensajeWrapper = new mensajesEmpresaWrapper();
$mensajes_raw = $mensajeWrapper->obtenerMensajes($empresa_id);
$mensajes_no_leidos = 0;
foreach ($mensajes_raw as $m) {
    $mensajes_no_leidos += isset($m['leido']) && $m['leido'] == 0 ? 1 : 0;
}

// --------------------------
// NOTIFICACIONES NO LEÍDAS
// --------------------------
$notifWrapper = new notificacionControladorWrapper();
$notificaciones_no_leidas = $notifWrapper->contarNoLeidas($empresa_id);

// --------------------------
// TOTAL NOTIFICACIONES
// --------------------------
$total_notificaciones = $total_reservas + $mensajes_no_leidos + $notificaciones_no_leidas;

// --------------------------
// MARCAR COMO LEÍDAS AL VISITAR
// --------------------------
$notifWrapper->marcarLeidas($empresa_id);

// Marcar mensajes como leídos
foreach ($mensajes_raw as $m) {
    if (isset($m['leido']) && $m['leido'] == 0) {
        $mensajeWrapper->marcarMensajesLeidos($empresa_id, $m['id_emisor'], $m['id_reserva']);
    }
}

// --------------------------
// CALIFICACIÓN PROMEDIO
// --------------------------
$resenaWrapper = new resenaControladorWrapper();
$promedio_calificacion = $resenaWrapper->obtenerPromedioPorEmpresa($empresa_id);
$promedio_calificacion = $promedio_calificacion ?? 0; // si no hay reseñas

// Página actual para menú activo
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inicio Empresa - ClickSoft</title>
<link rel="stylesheet" href="homeEmpresa.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<header>
    <nav>
        <div class="empresa-info">
            <a href="../vistaEditarPerfil/editarPerfilEmpresa.php" title="Editar perfil">
                <div class="logo-empresa" style="background-image: url('<?php echo htmlspecialchars($logo); ?>');"></div>
            </a>
            <span class="nombre-empresa"><?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
        </div>

        <div class="menu-hamburguesa" id="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <ul class="nav-links" id="nav-links">
            <li><a href="../VistaPrincipal/homeEmpresa.php" class="<?= $current_page === 'homeEmpresa.php' ? 'active' : '' ?>">Inicio</a></li>
            <li><a href="../VistaServicios/serviciosEmpresa.php" class="<?= $current_page === 'serviciosEmpresa.php' ? 'active' : '' ?>">Mis Servicios</a></li>
            <li><a href="../VistaReservas/reservasEmpresa.php" class="<?= $current_page === 'reservasEmpresa.php' ? 'active' : '' ?>">Reservas</a></li>
            <li><a href="../VistaPromociones/promocionesEmpresa.php" class="<?= $current_page === 'promocionesEmpresa.php' ? 'active' : '' ?>">Promociones</a></li>
        </ul>

        <div class="notificaciones">
            <a href="../VistaNotificaciones/notificacionesEmpresa.php" id="campana-notificaciones">
                <i class="fa-solid fa-bell"></i>
                <?php if($total_notificaciones > 0): ?>
                    <span class="contador" id="contador-notificaciones"><?= $total_notificaciones; ?></span>
                <?php endif; ?>
            </a>
        </div>
    </nav>
</header>

<main>
    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></h2>
    <p class="subtitulo">Aquí tienes una vista general de tu actividad en ClickSoft</p>

    <!-- Estadísticas -->
    <section class="estadisticas">
        <div class="card">
            <h3>Reservas próximas</h3>
            <p><?= count($pendientes) ?></p>
        </div>
        <div class="card">
            <h3>Ventas del mes</h3>
            <p>$ <?= number_format($total_ventas_mes, 0, ',', '.') ?></p>
        </div>
        <div class="card">
            <h3>Calificación promedio</h3>
            <p><?= number_format($promedio_calificacion, 1) ?> ★</p>
        </div>
    </section>

    <!-- Acciones rápidas -->
    <section class="acciones">
        <h3>Acciones rápidas</h3>
        <div class="acciones-grid">
            <a href="../VistaServicios/VistaPublicarServicios/publicarServicio.php" class="btn"> Publicar servicio</a>
            <a href="../VistaServicios/serviciosEmpresa.php" class="btn"> Gestionar servicios</a>
            <a href="../VistaReservas/reservasEmpresa.php" class="btn"> Ver reservas</a>
            <a href="../VistaPromociones/promocionesEmpresa.php" class="btn"> Crear promoción</a>
        </div>
    </section>

    <!-- Reservas próximas -->
    <section class="reservas-proximas">
        <h3>Reservas próximas</h3>
        <?php if(empty($reservas_proximas)): ?>
            <p>No hay reservas próximas.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reservas_proximas as $reserva): ?>
                <tr>
                    <td><?= htmlspecialchars($reserva['nombre_cliente'] ?? 'Desconocido') ?></td>
                    <td><?= htmlspecialchars($reserva['titulo'] ?? 'Servicio eliminado') ?></td>
                    <td><?= htmlspecialchars($reserva['fecha_reserva']) ?></td>
                    <td><?= htmlspecialchars($reserva['hora_reserva']) ?></td>
                    <td><span class="estado <?= htmlspecialchars($reserva['estado_reserva']); ?>">
                        <?= ucfirst($reserva['estado_reserva']); ?>
                    </span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </section>
</main>
</body>
</html>


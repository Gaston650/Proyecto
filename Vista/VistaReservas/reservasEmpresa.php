<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$id_empresa = $_SESSION['user_id'];
$reservasWrapper = new reservasControladorWrapper();

// Capturar filtros
$filtro_estado = $_GET['estado'] ?? '';
$filtro_fecha_inicio = $_GET['fecha_inicio'] ?? '';
$filtro_fecha_fin = $_GET['fecha_fin'] ?? '';

// Obtener reservas filtradas usando el nuevo método
$reservas_filtradas = $reservasWrapper->verReservasProveedorFiltradas(
    $id_empresa,
    $filtro_estado,
    $filtro_fecha_inicio,
    $filtro_fecha_fin
);

// Reservas pendientes para notificaciones
$pendientes = array_filter($reservas_filtradas, fn($r) => $r['estado_reserva'] === 'Pendiente');

// Logo empresa
$logo = '/ClickSoft/IMG/empresas/' . $_SESSION['empresa_logo'];

// Página actual para menú activo
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservas de mis servicios</title>
    <link rel="stylesheet" href="reservasEmpresa.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<header>
    <nav>
        <div class="empresa-info">
            <div class="logo-empresa" style="background-image: url('<?php echo htmlspecialchars($logo ?? ''); ?>');"></div>
            <span class="nombre-empresa"><?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
        </div>

        <ul class="nav-links">
            <li><a href="../VistaPrincipal/homeEmpresa.php" class="<?= $current_page === 'homeEmpresa.php' ? 'active' : '' ?>">Inicio</a></li>
            <li><a href="../VistaServicios/serviciosEmpresa.php" class="<?= $current_page === 'serviciosEmpresa.php' ? 'active' : '' ?>">Mis Servicios</a></li>
            <li><a href="../VistaReservas/reservasEmpresa.php" class="<?= $current_page === 'reservasEmpresa.php' ? 'active' : '' ?>">Reservas</a></li>
            <li><a href="../VistaPromociones/promocionesEmpresa.php" class="<?= $current_page === 'promocionesEmpresa.php' ? 'active' : '' ?>">Promociones</a></li>
        </ul>

        <div class="notificaciones">
            <a href="../VistaNotificaciones/notificaciones.php">
                <i class="fa-solid fa-bell"></i>
                <?php if (count($pendientes) > 0): ?>
                    <span class="contador"><?= count($pendientes); ?></span>
                <?php endif; ?>
            </a>
        </div>
    </nav>
</header>

<main>
    <h2>📅 Reservas de mis servicios</h2>

    <!-- Filtros -->
    <form method="GET" class="filtros">
        <label for="estado">Estado:</label>
        <select name="estado" id="estado">
            <option value="">Todos</option>
            <option value="Pendiente" <?= $filtro_estado === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="Confirmada" <?= $filtro_estado === 'Confirmada' ? 'selected' : '' ?>>Confirmada</option>
            <option value="Cancelada" <?= $filtro_estado === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
            <option value="Completada" <?= $filtro_estado === 'Completada' ? 'selected' : '' ?>>Completada</option>
        </select>

        <label for="fecha_inicio">Desde:</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?= htmlspecialchars($filtro_fecha_inicio) ?>">

        <label for="fecha_fin">Hasta:</label>
        <input type="date" name="fecha_fin" id="fecha_fin" value="<?= htmlspecialchars($filtro_fecha_fin) ?>">

        <button type="submit">Filtrar</button>
    </form>

    <!-- Tabla de reservas -->
    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($reservas_filtradas && count($reservas_filtradas) > 0): ?>
                    <?php foreach ($reservas_filtradas as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['nombre_cliente']); ?></td>
                            <td><?= htmlspecialchars($r['nombre_servicio']); ?></td>
                            <td><?= htmlspecialchars($r['fecha_reserva']); ?></td>
                            <td><?= htmlspecialchars($r['hora_reserva']); ?></td>
                            <td><span class="estado <?= htmlspecialchars($r['estado_reserva']); ?>"><?= htmlspecialchars($r['estado_reserva']); ?></span></td>
                            <td>
                                <?php if ($r['estado_reserva'] === 'Pendiente'): ?>
                                    <form style="display:inline;" method="POST" action="../../Controlador/superControlador/superControlador.php">
                                        <input type="hidden" name="reserva_id" value="<?= $r['id_reserva']; ?>">
                                        <button type="submit" name="accion" value="confirmar" class="btn confirmar">Confirmar</button>
                                    </form>
                                    <form style="display:inline;" method="POST" action="../../Controlador/superControlador/superControlador.php">
                                        <input type="hidden" name="reserva_id" value="<?= $r['id_reserva']; ?>">
                                        <button type="submit" name="accion" value="cancelar" class="btn cancelar">Cancelar</button>
                                    </form>
                                <?php elseif ($r['estado_reserva'] === 'Confirmada'): ?>
                                    <form style="display:inline;" method="POST" action="../../Controlador/superControlador/superControlador.php">
                                        <input type="hidden" name="reserva_id" value="<?= $r['id_reserva']; ?>">
                                        <button type="submit" name="accion" value="completada" class="btn completada">Marcar Completada</button>
                                    </form>
                                    <form style="display:inline;" method="POST" action="../../Controlador/superControlador/superControlador.php">
                                        <input type="hidden" name="reserva_id" value="<?= $r['id_reserva']; ?>">
                                        <button type="submit" name="accion" value="cancelar" class="btn cancelar">Cancelar</button>
                                    </form>
                                <?php endif; ?>
                                <form style="display:inline;" method="POST" action="../../Controlador/superControlador/superControlador.php">
                                    <input type="hidden" name="reserva_id" value="<?= $r['id_reserva']; ?>">
                                    <button type="submit" name="accion" value="mensaje" class="btn mensaje">Enviar Mensaje</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="sin-reservas">No hay reservas con esos filtros.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>


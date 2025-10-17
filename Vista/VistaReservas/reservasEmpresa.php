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
    </nav>
</header>

<main>
    <h2>📅 Reservas de mis servicios</h2>

    <?php if(isset($_GET['mensaje'])): ?>
        <div class="mensaje-exito"><?= htmlspecialchars($_GET['mensaje']); ?></div>
    <?php endif; ?>

    <!-- Filtros -->
    <form method="GET" class="filtros">
        <label for="estado">Estado:</label>
        <select name="estado" id="estado">
            <option value="">Todos</option>
            <option value="pendiente" <?= $filtro_estado === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="confirmada" <?= $filtro_estado === 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
            <option value="cancelada" <?= $filtro_estado === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
            <option value="completada" <?= $filtro_estado === 'completada' ? 'selected' : '' ?>>Completada</option>
        </select>

        <label for="fecha_inicio">Desde:</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?= htmlspecialchars($filtro_fecha_inicio) ?>">

        <label for="fecha_fin">Hasta:</label>
        <input type="date" name="fecha_fin" id="fecha_fin" value="<?= htmlspecialchars($filtro_fecha_fin) ?>">

        <button type="submit">Filtrar</button>
    </form>

    <!-- Tabla -->
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
                        <tr data-id="<?= $r['id_reserva'] ?>">
                            <td><?= htmlspecialchars($r['nombre_cliente']); ?></td>
                            <td><?= htmlspecialchars($r['nombre_servicio']); ?></td>
                            <td><?= htmlspecialchars($r['fecha_reserva']); ?></td>
                            <td><?= htmlspecialchars($r['hora_reserva']); ?></td>
                            <td>
                                <span class="estado <?= strtolower($r['estado_reserva']); ?>">
                                    <?= ucfirst($r['estado_reserva']); ?>
                                </span>
                            </td>
                            <td>
                                <select class="estado-select">
                                    <option value="pendiente" <?= $r['estado_reserva'] !== 'pendiente' ? 'disabled' : 'selected' ?>>Pendiente</option>
                                    <option value="confirmada" <?= $r['estado_reserva']=='confirmada'?'selected':'' ?>>Confirmada</option>
                                    <option value="completada" <?= $r['estado_reserva']=='completada'?'selected':'' ?>>Completada</option>
                                    <option value="cancelada" <?= $r['estado_reserva']=='cancelada'?'selected':'' ?>>Cancelada</option>
                                </select>
                                <button class="btn actualizar">Actualizar</button>
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

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <p id="modal-text"></p>
            <button id="modal-close">Cerrar</button>
        </div>
    </div>

</main>

<!-- Modal -->
<div class="modal" id="modal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <p id="modal-text">Estado actualizado con éxito</p>
    </div>
</div>
<script src="actualizarEstado.js"></script>
<script src="modal_info.js"></script>











</body>
</html>


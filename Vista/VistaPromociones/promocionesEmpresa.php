<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';
require_once __DIR__ . '/../../conexion.php';

// Verificar sesión de empresa
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión como empresa.");
    exit();
}

$empresa_id = $_SESSION['user_id'];
$logo = '/ClickSoft/IMG/empresas/' . $_SESSION['user_image'];

// Conexión
$conexion = new conexion();
$conn = $conexion->conectar();

// Instanciar wrappers
$servicioWrapper = new servicioControladorWrapper();
$promocionWrapper = new promocionControladorWrapper($conn);
$historialWrapper = new historialControladorWrapper();

// Traer servicios y promociones de la empresa
$servicios_empresa = $servicioWrapper->obtenerServicios($empresa_id);
$promociones = $promocionWrapper->listar($empresa_id); 

// Reservas pendientes para notificaciones
$reservas_proximas = $historialWrapper->listarReservasEmpresa($empresa_id);
$pendientes = array_filter($reservas_proximas, fn($r) => $r['estado_reserva'] === 'pendiente');

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Promociones</title>
<link rel="stylesheet" href="promocionesEmpresa.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
<nav>
    <div class="empresa-info">
        <a href="../vistaEditarPerfil/editarPerfilEmpresa.php" title="Editar perfil">
            <div class="logo-empresa" style="background-image: url('<?= htmlspecialchars($logo) ?>');"></div>
        </a>
        <span class="nombre-empresa"><?= htmlspecialchars($_SESSION['user_nombre']) ?></span>
    </div>

    <ul class="nav-links">
        <li><a href="../VistaPrincipal/homeEmpresa.php" class="<?= $current_page === 'homeEmpresa.php' ? 'active' : '' ?>">Inicio</a></li>
        <li><a href="../VistaServicios/serviciosEmpresa.php" class="<?= $current_page === 'serviciosEmpresa.php' ? 'active' : '' ?>">Mis Servicios</a></li>
        <li><a href="../VistaReservas/reservasEmpresa.php" class="<?= $current_page === 'reservasEmpresa.php' ? 'active' : '' ?>">Reservas</a></li>
        <li><a href="../VistaPromociones/promocionesEmpresa.php" class="<?= $current_page === 'promocionesEmpresa.php' ? 'active' : '' ?>">Promociones</a></li>
    </ul>
</nav>
</header>

<main>
<h1>Promociones de mis servicios</h1>

<section class="crear-promocion">
    <h2>Crear nueva promoción</h2>
    <label for="servicio_select">Selecciona un servicio:</label>
    <select id="servicio_select" required>
        <option value="">-- Selecciona --</option>
        <?php foreach($servicios_empresa as $servicio): ?>
            <option value="<?= $servicio['id_servicio'] ?>"><?= htmlspecialchars($servicio['titulo']) ?></option>
        <?php endforeach; ?>
    </select>
</section>

<!-- Modal Crear Promoción -->
<div id="modalPromocion" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Crear Promoción</h3>
        <form id="formPromocion" action="../../Controlador/minisControlador/validarPromocion.php" method="POST">
            <input type="hidden" name="accion" value="crear">
            <input type="hidden" name="id_servicio" id="id_servicio_input">

            <label for="porcentaje">Porcentaje (%):</label>
            <input type="number" name="porcentaje" min="1" max="100" required>

            <label for="fecha_inicio">Fecha inicio:</label>
            <input type="date" name="fecha_inicio" required>

            <label for="fecha_fin">Fecha fin:</label>
            <input type="date" name="fecha_fin" required>

            <label for="condiciones">Condiciones:</label>
            <textarea name="condiciones" rows="3"></textarea>

            <button type="submit">Guardar promoción</button>
        </form>
    </div>
</div>

<section class="list-section">
<h2>Mis promociones</h2>
<?php if (!empty($promociones)): ?>
    <table>
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Descuento</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Condiciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($promociones as $promo): ?>
            <tr>
                <td><?= htmlspecialchars($promo['titulo']) ?></td>
                <td><?= htmlspecialchars($promo['porcentaje_descuento']) ?>%</td>
                <td><?= htmlspecialchars($promo['fecha_inicio']) ?></td>
                <td><?= htmlspecialchars($promo['fecha_fin']) ?></td>
                <td><?= htmlspecialchars($promo['condiciones']) ?></td>
                <td>
                    <button class="btn-editar" 
                            data-id="<?= $promo['id_promocion'] ?>"
                            data-servicio="<?= $promo['id_servicio'] ?>"
                            data-porcentaje="<?= $promo['porcentaje_descuento'] ?>"
                            data-fecha_inicio="<?= date('Y-m-d', strtotime($promo['fecha_inicio'])) ?>"
                            data-fecha_fin="<?= date('Y-m-d', strtotime($promo['fecha_fin'])) ?>"
                            data-condiciones="<?= htmlspecialchars($promo['condiciones'], ENT_QUOTES) ?>">✏️ Editar</button>
                    <button class="btn-eliminar" data-id="<?= $promo['id_promocion'] ?>">🗑️ Eliminar</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No tienes promociones creadas.</p>
<?php endif; ?>
</section>

<!-- Modal Editar Promoción -->
<div id="modalEditar" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Editar Promoción</h3>
        <form id="formEditar" action="../../Controlador/minisControlador/validarPromocion.php" method="POST">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id_promocion" id="editar_id_promocion">

            <label for="editar_id_servicio">Servicio:</label>
            <select name="id_servicio" id="editar_id_servicio" required>
                <?php foreach($servicios_empresa as $servicio): ?>
                    <option value="<?= $servicio['id_servicio'] ?>"><?= htmlspecialchars($servicio['titulo']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="editar_porcentaje">Porcentaje (%):</label>
            <input type="number" name="porcentaje" id="editar_porcentaje" min="1" max="100" required>

            <label for="editar_fecha_inicio">Fecha inicio:</label>
            <input type="date" name="fecha_inicio" id="editar_fecha_inicio" required>

            <label for="editar_fecha_fin">Fecha fin:</label>
            <input type="date" name="fecha_fin" id="editar_fecha_fin" required>

            <label for="editar_condiciones">Condiciones:</label>
            <textarea name="condiciones" id="editar_condiciones" rows="3"></textarea>

            <button type="submit">Actualizar promoción</button>
            <button type="button" id="cancelarEditar">Cancelar</button>
        </form>
    </div>
</div>

<!-- Modal eliminar -->
<div id="modalEliminar" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Confirmar eliminación</h3>
        <p>¿Seguro que deseas eliminar esta promoción?</p>
        <form id="formEliminar" method="POST" action="../../Controlador/minisControlador/eliminarPromocion.php">
            <input type="hidden" name="id_promocion" id="id_promocion_eliminar">
            <button type="submit" class="btn-confirm">Sí, eliminar</button>
            <button type="button" class="btn-cancel" id="cancelarEliminar">Cancelar</button>
        </form>
    </div>
</div>

</main>

<script src="modalPromocion.js"></script>
<script src="fechasPromocion.js"></script>


</body>
</html>

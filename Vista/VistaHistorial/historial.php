<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';
require_once __DIR__ . '/../../Controlador/minisControlador/autologin.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$id_cliente = $_SESSION['user_id'];
$estadoFiltro = $_GET['estado'] ?? null;
$reseña_guardada = $_GET['reseña_guardada'] ?? null;

$historialWrapper = new historialControladorWrapper();
$historial = $historialWrapper->listarHistorial($id_cliente, $estadoFiltro);

$conn = (new Conexion())->conectar();

// ✅ Agregamos el modelo de perfil y lo instanciamos
require_once __DIR__ . '/../../Modelo/modeloPerfil.php';
$perfilModelo = new perfilModelo($conn);

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
<title>Historial de Reservas</title>
<link rel="stylesheet" href="historial.css">
<link rel="stylesheet" href="modal.css">
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
    <div class="nav-links">
        <ul>
            <li><a href="../VistaPrincipal/home.php">Inicio</a></li>
            <li><a href="../VistaServicios/servicios.php">Servicios</a></li>
            <li><a href="historial.php" class="active">Historial</a></li>
            <li><a href="../VistaReservas/reservas.php">Reservas</a></li>
        </ul>
    </div>
</nav>
</header>

<main>
<h2 class="titulo">Historial de Reservas</h2>
<p class="subtitulo">Consulta tus reservas, estados y calificaciones realizadas.</p>

<form method="get" class="filtros">
    <select name="estado" class="filtro-estado" onchange="this.form.submit()">
        <option value="">Todos los estados</option>
        <option value="pendiente" <?= $estadoFiltro == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
        <option value="confirmada" <?= $estadoFiltro == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
        <option value="cancelada" <?= $estadoFiltro == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
        <option value="completada" <?= $estadoFiltro == 'completada' ? 'selected' : '' ?>>Completada</option>
    </select>
</form>

<table class="tabla-historial">
<thead>
<tr>
    <th>Servicio</th>
    <th>Proveedor</th>
    <th>Fecha</th>
    <th>Hora</th>
    <th>Estado</th>
    <th>Precio</th>
    <th>Acciones</th>
</tr>
</thead>
<tbody>
<?php if(empty($historial)): ?>
<tr><td colspan="7" style="text-align:center;">No hay reservas</td></tr>
<?php else: ?>
<?php foreach($historial as $reserva): ?>
<tr>
    <td><?= htmlspecialchars($reserva['servicio']['titulo'] ?? 'N/A') ?></td>
    <td><?= htmlspecialchars($reserva['servicio']['nombre_proveedor'] ?? 'N/A') ?></td>
    <td><?= htmlspecialchars($reserva['fecha_reserva']) ?></td>
    <td><?= htmlspecialchars($reserva['hora_reserva']) ?></td>
    <td><span class="estado <?= htmlspecialchars($reserva['estado_reserva']) ?>"><?= ucfirst($reserva['estado_reserva']) ?></span></td>
    <td><?= '$'.number_format($reserva['servicio']['precio'] ?? 0,2) ?></td>
    <td>
        <?php if($reserva['estado_reserva'] != 'cancelada'): ?>
            <button class="ver-acciones-btn" data-id="acciones-<?= $reserva['id_reserva'] ?>">Ver Acciones</button>
        <?php else: ?>
            Cancelada
        <?php endif; ?>
    </td>
</tr>

<tr id="acciones-<?= $reserva['id_reserva'] ?>" class="acciones-contenedor" style="display:none;">
    <td colspan="7">
        <?php if($reserva['estado_reserva'] == 'pendiente' || $reserva['estado_reserva'] == 'confirmada'): ?>
        <form method="post" action="../../Controlador/minisControlador/validarHistorial.php" class="accion-form">
            <input type="hidden" name="reserva_id" value="<?= $reserva['id_reserva'] ?>">
            <input type="text" name="motivo" placeholder="Motivo (opcional)" class="input-accion">
            <button name="cancelar" class="btn-cancelar">Cancelar</button>
        </form>
        <?php endif; ?>

        <?php if($reserva['estado_reserva'] == 'completada' && empty($reserva['calificacion'])): ?>
        <form method="post" action="../../Controlador/minisControlador/validarHistorial.php" class="accion-form">
            <input type="hidden" name="reserva_id" value="<?= $reserva['id_reserva'] ?>">
            <div class="star-rating">
                <?php for($i=5; $i>=1; $i--): ?>
                    <input type="radio" id="estrella<?= $i ?>-<?= $reserva['id_reserva'] ?>" name="calificacion" value="<?= $i ?>"><label for="estrella<?= $i ?>-<?= $reserva['id_reserva'] ?>">★</label>
                <?php endfor; ?>
            </div>
            <textarea name="comentarios_cliente" placeholder="Escribe tu reseña..." required class="input-accion"></textarea>
            <button name="guardar_comentario" class="btn-guardar">Guardar Reseña</button>
        </form>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>

<!-- Modal -->
<div id="modal-reseña" class="modal">
    <div class="modal-content">
        <p>¡Tu reseña se ha guardado correctamente!</p>
        <button id="cerrar-modal">Cerrar</button>
    </div>
</div>

<script src="historial.js"></script>
<script src="modalResena.js"></script>

<?php if($reseña_guardada): ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    mostrarModal();
});
</script>
<?php endif; ?>

</main>
</body>
</html>
<?php
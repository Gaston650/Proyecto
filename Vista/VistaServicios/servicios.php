<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

// Datos del usuario/cliente
$id_cliente = $_SESSION['user_id'];
$nombre_cliente = $_SESSION['user_nombre'];
$logo_cliente = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : '../../img/perfil-vacio.png';

// Incluir superControlador y conexión
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';
require_once __DIR__ . '/../../conexion.php';
$conexion = new conexion();
$conn = $conexion->conectar();

// Crear wrappers
$servicioWrapper = new servicioControladorWrapper();
$favoritoWrapper = new controladorFavorito();
$promocionWrapper = new promocionControladorWrapper($conn);
$resenaWrapper = new resenaControladorWrapper();
$perfilModelo = new perfilModelo($conn);

// Capturar filtros y sanearlos
$filtros = [
    'buscar' => trim($_GET['buscar'] ?? ''),
    'zona' => trim($_GET['zona'] ?? ''),
    'categoria' => trim($_GET['categoria'] ?? '')
];

// Obtener servicios filtrados
$servicios = $servicioWrapper->obtenerServiciosFiltrados(
    $filtros['buscar'],
    $filtros['zona'],
    $filtros['categoria']
);

// Obtener categorías dinámicamente
$categorias = $servicioWrapper->obtenerCategorias();

// Departamentos de Uruguay
$departamentos = [
    'Artigas', 'Canelones', 'Cerro Largo', 'Colonia', 'Durazno',
    'Flores', 'Florida', 'Lavalleja', 'Maldonado', 'Montevideo',
    'Paysandú', 'Río Negro', 'Rivera', 'Rocha', 'Salto',
    'San José', 'Soriano', 'Tacuarembó', 'Treinta y Tres'
];

// Reseñas
$resenasPorServicios = [];
if (!empty($servicios)) {
    foreach($servicios as $s) {
        $resenasPorServicios[$s['id_servicio']] = $resenaWrapper->obtenerPorServicio($s['id_servicio']);
    }
}

// Función para mantener seleccionado en <select>
function selected($valorFiltro, $valorOpcion) {
    return strtolower($valorFiltro) === strtolower($valorOpcion) ? 'selected' : '';
}

// Imagen de perfil
$fotoPerfil = '../../IMG/perfil-vacio.png';
if (isset($_SESSION['user_image']) && !empty($_SESSION['user_image'])) {
    $fotoPerfil = $_SESSION['user_image'];
} elseif (isset($_SESSION['user_id'])) {
    $perfil = $perfilModelo->obtenerPerfil($_SESSION['user_id']);
    if ($perfil && !empty($perfil['foto_perfil'])) {
        $fotoPerfil = $perfil['foto_perfil'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Servicios</title>
<link rel="stylesheet" href="servicios.css">
<link rel="stylesheet" href="modalResenas.css">
</head>
<body>
<header>
<nav>
 <div class="usuario-info">
   <a href="../vistaEditarPerfil/editarPerfil.php" title="Editar perfil">
       <div class="foto-perfil" style="background-image: url('<?php echo htmlspecialchars($fotoPerfil); ?>');"></div>
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
       <li><a href="servicios.php" class="active">Servicios</a></li>
       <li><a href="../VistaHistorial/historial.php">Historial</a></li>
       <li><a href="../VistaReservas/reservas.php">Reservas</a></li>
   </ul>
 </div>
</nav>
</header>

<h2 class="titulo-servicios">Nuestros Servicios Disponibles</h2>
<p class="subtitulo-servicios">Explora los servicios que puedes contratar fácilmente en ClickSoft.</p>

<!-- Filtros -->
<div class="filtros">
<form method="GET" action="servicios.php">
    <input type="text" name="buscar" placeholder="Buscar servicio..." value="<?php echo htmlspecialchars($filtros['buscar']); ?>">
    <select name="zona">
        <option value="">Todas las zonas</option>
        <?php foreach ($departamentos as $dep): ?>
            <option value="<?php echo htmlspecialchars($dep); ?>" <?php echo selected($filtros['zona'], $dep); ?>>
                <?php echo htmlspecialchars($dep); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select name="categoria">
        <option value="">Todas las categorías</option>
        <?php foreach ($categorias as $cat): ?>
            <option value="<?php echo htmlspecialchars($cat['nombre_categoria']); ?>" <?php echo selected($filtros['categoria'], $cat['nombre_categoria']); ?>>
                <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filtrar</button>
</form>
</div>

<!-- Servicios -->
<div class="servicios-grid">
<?php if (!empty($servicios)): ?>
    <?php foreach($servicios as $servicio): 
        $promo = $promocionWrapper->obtenerPromocionPorServicio($servicio['id_servicio']);
        $esFavorito = $favoritoWrapper->esFavorito($id_cliente, $servicio['id_servicio']);
        $favAccion = $esFavorito ? 'quitar' : 'agregar';
        $imagen_servicio = !empty($servicio['imagen']) ? '../../uploads/' . $servicio['imagen'] : '../../img/servicio-vacio.png';
    ?>
    <div class="servicio-card">
        <div class="imagen-servicio" style="background-image: url('<?php echo htmlspecialchars($imagen_servicio); ?>');"></div>
        <h3><?php echo htmlspecialchars($servicio['titulo']); ?></h3>
        <p><?php echo htmlspecialchars($servicio['descripcion']); ?></p>

        <?php if ($promo && isset($promo['porcentaje_descuento'])): ?>
            <div class="promo-box"><?php echo $promo['porcentaje_descuento']; ?>% de descuento</div>
            <p><strong>Precio original:</strong> <span class="precio-original">$<?php echo number_format($servicio['precio'], 2); ?></span></p>
            <p><strong>Precio con descuento:</strong> <span class="precio-descuento">$<?php echo number_format($servicio['precio'] * (1 - $promo['porcentaje_descuento']/100), 2); ?></span></p>
        <?php else: ?>
            <p><strong>Precio:</strong> $<?php echo number_format($servicio['precio'], 2); ?></p>
        <?php endif; ?>

        <p><strong>Disponibilidad:</strong> <?php echo htmlspecialchars($servicio['disponibilidad']); ?></p>
        <p><strong>Proveedor:</strong> <?php echo htmlspecialchars($servicio['id_empresa']); ?></p>

        <div class="servicio-botones">
            <input type="hidden" name="id_servicio" value="<?php echo $servicio['id_servicio']; ?>">
            <button class="btn-contratar" data-id-servicio="<?php echo $servicio['id_servicio']; ?>">Contratar</button>
            <a href="../../Controlador/minisControlador/validarFavorito.php?id_servicio=<?php echo $servicio['id_servicio']; ?>&accion=<?php echo $favAccion; ?>" class="btn-favorito"><?php echo $esFavorito ? '💔 Quitado' : '❤️ Favorito'; ?></a>
            <button class="btn-ver-resenas" data-id-servicio="<?php echo $servicio['id_servicio']; ?>">Ver reseñas</button>

            <!-- NUEVO BOTÓN REPORTAR -->
            <button class="btn-reportar" 
                    data-id-servicio="<?php echo $servicio['id_servicio']; ?>"
                    data-titulo-servicio="<?php echo htmlspecialchars($servicio['titulo']); ?>">
                    Reportar
            </button>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
<p>No hay servicios disponibles.</p>
<?php endif; ?>
</div>

<!-- Modal Reportar -->
<div id="modal-reportar" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Reportar Servicio: <span id="titulo-servicio"></span></h2>
        <form id="form-reportar" method="POST" action="../../Controlador/minisControlador/validarReporte.php">
            <input type="hidden" name="id_servicio" id="input-id-servicio">
            <label for="motivo">Motivo del reporte:</label>
            <textarea name="motivo" id="motivo" rows="4" required></textarea>
            <button type="submit">Enviar reporte</button>
        </form>
    </div>
</div>

<!-- Modal Contratar -->
<div id="modal-contratar" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Contratar Servicio</h2>
        <form id="form-contratar" method="POST" action="../../Controlador/minisControlador/validarReserva.php">
            <input type="hidden" name="id_servicio" id="modal-id-servicio">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="modal-fecha" required>
            <label for="hora">Hora:</label>
            <input type="time" name="hora" id="modal-hora" required>
            <label for="comentarios">Comentarios (opcional):</label>
            <textarea name="comentarios" id="modal-comentarios" placeholder="Instrucciones para el proveedor"></textarea>
            <button type="submit" class="btn-contratar">Confirmar</button>
        </form>
    </div>
</div>

<!-- Modal de confirmación de reserva creada -->
<?php if (isset($_GET['exito']) && $_GET['exito'] == 1): ?>
<div id="modal-exito" class="modal" style="display:block;">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('modal-exito').style.display='none'">&times;</span>
        <h2>Servicio contratado con éxito</h2>
        <p>Tu reserva ha sido creada y podrás verla en la sección de 
           <a href="../VistaReservas/reservas.php">Reservas</a>.
        </p>
    </div>
</div>
<?php endif; ?>


<!-- Modal Reseñas -->
<div id="modal-resenas" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Reseñas del servicio</h2>
        <div id="contenido-resenas"></div>
    </div>
</div>

<!-- Modal de confirmación -->
<div id="modal-confirmacion" class="modal">
    <div class="modal-content">
        <h2>✅ Reporte enviado correctamente</h2>
        <p>Tu reporte ha sido recibido. Gracias por ayudarnos a mantener la comunidad segura.</p>
    </div>
</div>


<script>
// Pasar datos de reseñas de PHP a JS
const resenasData = <?php echo json_encode($resenasPorServicios); ?>;
</script>

<script src="modalConfirmacionReporte.js"></script>
<script src="modalReporte.js"></script>
<script src="../VistaPrincipal/verPagina.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="modalContratar.js"></script>
<script src="modalResenas.js"></script>
</body>
</html>

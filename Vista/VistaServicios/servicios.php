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

// Incluir superControlador
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

// Crear conexión
require_once __DIR__ . '/../../conexion.php';
$conexion = new conexion();
$conn = $conexion->conectar();

// Crear wrappers
$servicioWrapper = new servicioControladorWrapper();
$favoritoWrapper = new controladorFavorito();
$promocionWrapper = new promocionControladorWrapper($conn);

// Capturar filtros
$filtros = [
    'buscar' => $_GET['buscar'] ?? '',
    'zona' => $_GET['zona'] ?? '',
    'categoria' => $_GET['categoria'] ?? ''
];

// Obtener servicios filtrados
$servicios = $servicioWrapper->obtenerServiciosFiltrados(
    $filtros['buscar'],
    $filtros['zona'],
    $filtros['categoria']
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios</title>
    <link rel="stylesheet" href="servicios.css">
</head>
<body>
<header>
    <nav>
        <div class="usuario-info">
            <a href="../vistaEditarPerfil/editarPerfil.php" title="Editar perfil">
                <div class="foto-perfil" style="background-image: url(<?php echo htmlspecialchars($logo_cliente); ?>);"></div>
            </a>
            <span class="nombre-usuario"><?php echo htmlspecialchars($nombre_cliente); ?></span>
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
        <input type="text" name="buscar" placeholder="Buscar servicio..." 
               value="<?php echo htmlspecialchars($filtros['buscar']); ?>">

        <!-- Filtros zona -->
        <select name="zona">
            <option value="">Todas las zonas</option>
            <option value="artigas" <?php if($filtros['zona']=="artigas") echo "selected"; ?>>Artigas</option>
            <!-- Agregar todas las demás zonas -->
        </select>

        <!-- Filtros categoria -->
        <select name="categoria">
            <option value="">Todas las categorías</option>
            <option value="limpieza" <?php if($filtros['categoria']=="limpieza") echo "selected"; ?>>Limpieza</option>
            <option value="jardineria" <?php if($filtros['categoria']=="jardineria") echo "selected"; ?>>Jardinería</option>
            <option value="tecnologia" <?php if($filtros['categoria']=="tecnologia") echo "selected"; ?>>Tecnología</option>
        </select>

        <button type="submit">Filtrar</button>
    </form>
</div>

<!-- Servicios -->
<div class="servicios-grid">
<?php if ($servicios && count($servicios) > 0): ?>
    <?php foreach($servicios as $servicio): 
        $promo = $promocionWrapper->obtenerPromocionPorServicio($servicio['id_servicio']);
        $esFavorito = $favoritoWrapper->esFavorito($id_cliente, $servicio['id_servicio']);
        $favAccion = $esFavorito ? 'quitar' : 'agregar';
        $favTexto = $esFavorito ? '💔 Quitado' : '❤️ Favorito';
    ?>
        <div class="servicio-card">
            <img src="" alt="Foto del servicio" class="servicio-img">

            <h3><?php echo htmlspecialchars($servicio['titulo']); ?></h3>
            <p><?php echo htmlspecialchars($servicio['descripcion']); ?></p>

            <?php if ($promo && isset($promo['porcentaje_descuento'])): ?>
                <div class="promo-box">
                    <?php echo $promo['porcentaje_descuento']; ?>% de descuento
                </div>
                <p><strong>Precio original:</strong> <span class="precio-original">$<?php echo number_format($servicio['precio'], 2); ?></span></p>
                <p><strong>Precio con descuento:</strong> 
                    <span class="precio-descuento">
                        $<?php echo number_format($servicio['precio'] * (1 - $promo['porcentaje_descuento']/100), 2); ?>
                    </span>
                </p>
                <p><small>Vigente: <?php echo $promo['fecha_inicio']; ?> - <?php echo $promo['fecha_fin']; ?></small></p>
                <?php if (!empty($promo['condiciones'])): ?>
                    <p><em><?php echo htmlspecialchars($promo['condiciones']); ?></em></p>
                <?php endif; ?>
            <?php else: ?>
                <p><strong>Precio:</strong> $<?php echo number_format($servicio['precio'], 2); ?></p>
            <?php endif; ?>

            <p><strong>Disponibilidad:</strong> <?php echo htmlspecialchars($servicio['disponibilidad']); ?></p>
            <p><strong>Proveedor:</strong> <?php echo htmlspecialchars($servicio['id_empresa']); ?></p>

            <div class="servicio-botones">
                <input type="hidden" name="id_servicio" value="<?php echo $servicio['id_servicio']; ?>">
                <button class="btn-contratar" data-id-servicio="<?php echo $servicio['id_servicio']; ?>">Contratar</button>

                <a href="../../Controlador/minisControlador/validarFavorito.php?id_servicio=<?php echo $servicio['id_servicio']; ?>&accion=<?php echo $favAccion; ?>" class="btn-favorito">
                    <?php echo $favTexto; ?>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No hay servicios disponibles.</p>
<?php endif; ?>
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

<script src="../VistaPrincipal/verPagina.js"></script>   
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="modalContratar.js"></script>
<script src="modalExito.js"></script>
</body>
</html>

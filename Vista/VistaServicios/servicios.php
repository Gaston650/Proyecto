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

// Crear wrapper de servicios y favoritos
$servicioWrapper = new servicioControladorWrapper();
$favoritoWrapper = new controladorFavorito();

// Capturar filtros de GET
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

       <div class="notificaciones">
          <a href="#" title="Ver Notificaciones">
            <ion-icon name="notifications-outline"></ion-icon>
            <span class="contador">0</span>
          </a>
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

<select name="zona">
    <option value="">Todas las zonas</option>
    <option value="artigas" <?php if($filtros['zona']=="artigas") echo "selected"; ?>>Artigas</option>
    <option value="canelones" <?php if($filtros['zona']=="canelones") echo "selected"; ?>>Canelones</option>
    <option value="cerro largo" <?php if($filtros['zona']=="cerro largo") echo "selected"; ?>>Cerro Largo</option>
    <option value="colonia" <?php if($filtros['zona']=="colonia") echo "selected"; ?>>Colonia</option>
    <option value="durazno" <?php if($filtros['zona']=="durazno") echo "selected"; ?>>Durazno</option>
    <option value="flores" <?php if($filtros['zona']=="flores") echo "selected"; ?>>Flores</option>
    <option value="florida" <?php if($filtros['zona']=="florida") echo "selected"; ?>>Florida</option>
    <option value="lavalleja" <?php if($filtros['zona']=="lavalleja") echo "selected"; ?>>Lavalleja</option>
    <option value="maldonado" <?php if($filtros['zona']=="maldonado") echo "selected"; ?>>Maldonado</option>
    <option value="montevideo" <?php if($filtros['zona']=="montevideo") echo "selected"; ?>>Montevideo</option>
    <option value="paysandú" <?php if($filtros['zona']=="paysandú") echo "selected"; ?>>Paysandú</option>
    <option value="río negro" <?php if($filtros['zona']=="río negro") echo "selected"; ?>>Río Negro</option>
    <option value="rivera" <?php if($filtros['zona']=="rivera") echo "selected"; ?>>Rivera</option>
    <option value="rocha" <?php if($filtros['zona']=="rocha") echo "selected"; ?>>Rocha</option>
    <option value="salto" <?php if($filtros['zona']=="salto") echo "selected"; ?>>Salto</option>
    <option value="san josé" <?php if($filtros['zona']=="san josé") echo "selected"; ?>>San José</option>
    <option value="soriano" <?php if($filtros['zona']=="soriano") echo "selected"; ?>>Soriano</option>
    <option value="tacuarembó" <?php if($filtros['zona']=="tacuarembó") echo "selected"; ?>>Tacuarembó</option>
    <option value="treinta y tres" <?php if($filtros['zona']=="treinta y tres") echo "selected"; ?>>Treinta y Tres</option>
</select>
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
        $esFavorito = $favoritoWrapper->esFavorito($id_cliente, $servicio['id_servicio']);
        $favAccion = $esFavorito ? 'quitar' : 'agregar';
        $favTexto = $esFavorito ? '💔 Quitado' : '❤️ Favorito';
    ?>
        <div class="servicio-card">
            <img src="" alt="Foto del servicio" class="servicio-img">

            <h3><?php echo htmlspecialchars($servicio['titulo']); ?></h3>
            <p><?php echo htmlspecialchars($servicio['descripcion']); ?></p>
            <p><strong>Precio:</strong> $<?php echo htmlspecialchars($servicio['precio']); ?></p>
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

<script src="../VistaPrincipal/verPagina.js"></script>   
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="modalContratar.js"></script>
</body>
</html>


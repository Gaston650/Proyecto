<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$empresa_id = $_SESSION['user_id'];
$servicioWrapper = new servicioControladorWrapper();
$servicios = $servicioWrapper->obtenerServicios($empresa_id);

// Logo de la empresa
$logo = '/ClickSoft/IMG/empresas/' . $_SESSION['empresa_logo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Servicios</title>
    <link rel="stylesheet" href="serviciosEmpresa.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<header>
    <nav>
        <div class="empresa-info">
            <div class="logo-empresa" style="background-image: url('<?php echo htmlspecialchars($logo); ?>');"></div>
            <span class="nombre-empresa"><?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
        </div>

        <ul class="nav-links">
            <li><a href="../VistaPrincipal/homeEmpresa.php">Inicio</a></li>
            <li><a href="../VistaServicios/serviciosEmpresa.php" class="active">Mis Servicios</a></li>
            <li><a href="../VistaReservas/reservasEmpresa.php">Reservas</a></li>
            <li><a href="../VistaPromociones/promocionesEmpresa.php">Promociones</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Listado de servicios</h2>

    <?php if (!empty($servicios)): ?>
        <p>Gestiona tus servicios publicados</p>
        <div class="servicios-grid">
            <?php foreach ($servicios as $servicio): ?>
                <?php
                    $estadoClase = strtolower($servicio['estado']);
                    $estadoClase = str_replace(' ', '-', $estadoClase);
                ?>
                <div class="servicio-card">
                    <h3><?php echo htmlspecialchars($servicio['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($servicio['descripcion']); ?></p>
                    <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($servicio['ubicacion']); ?></p>
                    <p><strong>Precio:</strong> $<?php echo htmlspecialchars($servicio['precio']); ?></p>
                    <p><strong>Disponibilidad:</strong> <?php echo htmlspecialchars($servicio['disponibilidad']); ?></p>
                    <p><strong>Estado:</strong> 
                        <span class="estado <?php echo $estadoClase; ?>">
                            <?php echo htmlspecialchars($servicio['estado']); ?>
                        </span>
                    </p>
                    <!-- Botón Editar -->
                    <button class="btn-editar"
                        data-id="<?php echo $servicio['id_servicio']; ?>"
                        data-titulo="<?php echo htmlspecialchars($servicio['titulo']); ?>"
                        data-descripcion="<?php echo htmlspecialchars($servicio['descripcion']); ?>"
                        data-ubicacion="<?php echo htmlspecialchars($servicio['ubicacion']); ?>"
                        data-precio="<?php echo $servicio['precio']; ?>"
                        data-disponibilidad="<?php echo htmlspecialchars($servicio['disponibilidad']); ?>"
                        data-estado="<?php echo htmlspecialchars($servicio['estado']); ?>">
                        Editar
                    </button>
                    <!-- Botón Eliminar -->
                    <button class="btn-eliminar" data-id="<?php echo $servicio['id_servicio']; ?>">Eliminar</button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No has publicado servicios todavía.</p>
    <?php endif; ?>

    <!-- Modal Eliminar -->
    <div id="modalEliminar" class="modal">
        <div class="modal-contenido">
            <span class="cerrar-modal" id="cerrarEliminar">&times;</span>
            <h3>Eliminar servicio</h3>
            <p>¿Estás seguro de que quieres eliminar este servicio?</p>
           <form id="formEliminarServicio" method="POST" action="../../Controlador/minisControlador/validarEliminarServicio.php">
    <input type="hidden" id="eliminarIdServicio" name="id_servicio">
    <div class="modal-botones">
        <button type="submit" class="btn-confirmar-eliminar">Eliminar</button>
        <button type="button" id="cancelarEliminar" class="btn-cancelar">Cancelar</button>
    </div>
</form>


        </div>
    </div>

    <!-- Modal Editar -->
    <div id="modalEditar" class="modal">
        <div class="modal-contenido">
            <span class="cerrar-modal" id="cerrarEditar">&times;</span>
            <h3>Editar servicio</h3>
            <form id="formEditarServicio" method="POST" action="../../Controlador/minisControlador/editarServicio.php">
                <input type="hidden" id="editarIdServicio" name="id_servicio">

                <label for="editarTitulo">Título:</label>
                <input type="text" id="editarTitulo" name="titulo" required>

                <label for="editarDescripcion">Descripción:</label>
                <textarea id="editarDescripcion" name="descripcion" required></textarea>

                <label for="editarUbicacion">Ubicación:</label>
                <input type="text" id="editarUbicacion" name="ubicacion" required>

                <label for="editarPrecio">Precio:</label>
                <input type="number" id="editarPrecio" name="precio" step="0.01" required>

                <label for="editarDisponibilidad">Disponibilidad:</label>
                <input type="text" id="editarDisponibilidad" name="disponibilidad" required>

                <label for="editarEstado">Estado:</label>
                <select id="editarEstado" name="estado" required>
                    <option value="Disponible">Disponible</option>
                    <option value="No disponible">No disponible</option>
                    <option value="Pausado">Pausado</option>
                </select>

                <div class="modal-botones">
                    <button type="submit" class="btn-guardar">Guardar</button>
                    <button type="button" id="cancelarEditar" class="btn-cancelar">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

</main>

<script src="modalEditar.js"></script>
<script src="modalEliminar.js"></script>

</body>
</html>

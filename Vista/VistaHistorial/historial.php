<?php
session_start();

if (
    !isset($_SESSION['user_id']) &&
    !isset($_SESSION['empresa_id']) &&
    !isset($_SESSION['user_nombre'])
) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial</title>
    <link rel="stylesheet" href="historial.css">
</head>
<body>
      <header>
       <nav>
           <div class="usuario-info">
           <?php 
               $img = (isset($_SESSION['user_image']) && !empty($_SESSION['user_image']))
                   ? $_SESSION['user_image']
                   : '../../img/perfil-vacio.png';
           ?>
            <a href="../vistaEditarPerfil/editarPerfil.php" title="Editar perfil">
                <div class="foto-perfil" style="background-image: url(<?php echo htmlspecialchars($img); ?>);"></div>
            </a>
           <span class="nombre-usuario">
           <?php
               if (isset($_SESSION['user_nombre'])) {
                   echo htmlspecialchars($_SESSION['user_nombre']);
               } elseif (isset($_SESSION['nombre'])) {
                   echo htmlspecialchars($_SESSION['nombre']);
               } elseif (isset($_SESSION['nombre_empresa'])) {
                   echo htmlspecialchars($_SESSION['nombre_empresa']);
               } else {
                   echo 'usuario';
               }
           ?>
           </span>
       </div>
           <div class="nav-links">
                <ul>
                    <li><a href="../VistaPrincipal/home.php">Inicio</a></li>
                    <li><a href="../VistaServicios/servicios.php">Servicios</a></li>
                    <li><a href="historial.php">Historial</a></li>
                    <li><a href="../VistaReservas/reservas.php">Reservas</a></li>
                </ul>
           </div>
       </nav>
   </header>

            <main>
    <h2 class="titulo">Historial de Reservas</h2>
    <p class="subtitulo">Consulta tus reservas, estados y calificaciones realizadas.</p>

    <!-- Filtros -->
    <div class="filtros">
        <select class="filtro-estado">
            <option value="">Todos los estados</option>
            <option value="pendiente">Pendiente</option>
            <option value="confirmada">Confirmada</option>
            <option value="cancelada">Cancelada</option>
            <option value="completada">Completada</option>
        </select>
    </div>

    <!-- Tabla de historial -->
    <table class="tabla-historial">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Proveedor</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Monto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ejemplo estático (luego se llena con PHP + BD) -->
            <tr>
                <td>Categoria de servicio</td>
                <td>Nombre del proveedor</td>
                <td>fecha</td>
                <td><span class="estado confirmada">Confirmada(ejemplo)</span></td>
                <td>Precio</td>
                <td>
                    <button class="btn-detalle">Ver Detalle</button>
                    <button class="btn-calificar">Calificar</button>
                </td>
            </tr>
            <tr>
                <td>Reparación PC</td>
                <td>Carlos López</td>
                <td>2025-08-08</td>
                <td><span class="estado completada">Completada</span></td>
                <td>$2500</td>
                <td>
                    <button class="btn-detalle">Ver Detalle</button>
                    <button class="btn-calificar">Calificar</button>
                </td>
            </tr>
        </tbody>
    </table>
</main>

<script src="../VistaPrincipal/verPagina.js"></script>   
</body>
</html>
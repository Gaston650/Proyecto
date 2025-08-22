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
    <title>Reservas</title>
    <link rel="stylesheet" href="reservas.css">
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
                    <li><a href="../VistaHistorial/historial.php">Historial</a></li>
                    <li><a href="reservas.php">Reservas</a></li>
                </ul>
           </div>
       </nav>
   </header>

   <main>
    <h2 class="titulo">📅 Mis Reservas Activas</h2>
    <p class="subtitulo">Aquí puedes gestionar tus reservas pendientes y confirmadas.</p>

    <!-- Filtro -->
    <div class="filtros">
      <select class="filtro-estado">
        <option value="">Todos los estados</option>
        <option value="pendiente">Pendiente</option>
        <option value="confirmada">Confirmada</option>
        <option value="en-curso">En curso</option>
      </select>
    </div>

    <!-- Lista de reservas -->
    <div class="reservas-grid">
      <div class="reserva-card">
        <h3>Limpieza de hogar</h3>
        <p><strong>Proveedor:</strong> Ana Pérez</p>
        <p><strong>Fecha:</strong> 20/08/2025 - 15:00 hs</p>
        <p><strong>Estado:</strong> <span class="estado pendiente">Pendiente</span></p>
        <p><strong>Monto:</strong> $1200</p>
        <div class="acciones">
          <button class="btn-reprogramar">Reprogramar</button>
          <button class="btn-cancelar">Cancelar</button>
          <button class="btn-mensaje">Enviar Mensaje</button>
        </div>
      </div>

      <div class="reserva-card">
        <h3>Reparación de PC</h3>
        <p><strong>Proveedor:</strong> Carlos López</p>
        <p><strong>Fecha:</strong> 22/08/2025 - 10:00 hs</p>
        <p><strong>Estado:</strong> <span class="estado confirmada">Confirmada</span></p>
        <p><strong>Monto:</strong> $2500</p>
        <div class="acciones">
          <button class="btn-reprogramar">Reprogramar</button>
          <button class="btn-cancelar">Cancelar</button>
          <button class="btn-mensaje">Enviar Mensaje</button>
        </div>
      </div>
    </div>
  </main>

<script src="../VistaPrincipal/verPagina.js"></script>   
</body>
</html>
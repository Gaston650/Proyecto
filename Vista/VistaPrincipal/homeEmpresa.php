<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión como empresa.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Empresa - ClickSoft</title>
    <link rel="stylesheet" href="homeEmpresa.css">
</head>
<body>
    <header>
        <nav>
            <div class="empresa-info">
                <?php 
                    $logo = (isset($_SESSION['empresa_logo']) && !empty($_SESSION['empresa_logo'])) 
                        ? $_SESSION['empresa_logo'] 
                        : '../../img/logo-vacio.png';
                ?>
                <div class="logo-empresa" style="background-image: url(<?php echo htmlspecialchars($logo); ?>);"></div>
                <span class="nombre-empresa">
                    <?php echo htmlspecialchars($_SESSION['user_nombre']); ?>
                </span>
            </div>
            <ul class="nav-links">
                <li><a href="homeEmpresa.php">Inicio</a></li>
                <li><a href="../VistaServicios/serviciosEmpresa.php">Mis Servicios</a></li>
                <li><a href="../VistaReservas/reservasEmpresa.php">Reservas</a></li>
                <li><a href="../VistaPromociones/promocionesEmpresa.php">Promociones</a></li>
                <li><a href="../VistaSesion/cerrarSesion.php">Cerrar sesión</a></li>
            </ul>
            <div class="notificaciones">
                <a href="../VistaNotificaciones/notificacionesEmpresa.php" title="Notificaciones">
                    🔔
                </a>
            </div>
        </nav>
    </header>

    <main>
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></h2>
        <p>Administra tus servicios y reservas fácilmente desde tu panel de empresa.</p>

        <section class="acciones-rapidas">
            <h3>Acciones rápidas</h3>
            <div class="acciones-grid">
                <a href="../VistaServicios/publicarServicio.php" class="accion">📌 Publicar Servicio</a>
                <a href="../VistaReservas/reservasEmpresa.php" class="accion">📅 Gestionar Reservas</a>
                <a href="../VistaPromociones/promocionesEmpresa.php" class="accion">💸 Crear Promoción</a>
                <a href="../VistaEstadisticas/estadisticasEmpresa.php" class="accion">📊 Ver Estadísticas</a>
            </div>
        </section>

        <section class="estadisticas">
            <h3>Resumen de Actividad</h3>
            <div class="estadisticas-grid">
                <div class="card">
                    <h4>Reservas</h4>
                    <p><strong>12</strong> este mes</p>
                </div>
                <div class="card">
                    <h4>Ventas</h4>
                    <p><strong>$3.200</strong> acumulados</p>
                </div>
                <div class="card">
                    <h4>Calificación</h4>
                    <p><strong>4.5 ★</strong> promedio</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
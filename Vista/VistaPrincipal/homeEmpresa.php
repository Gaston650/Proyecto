<?php
session_start();

// Verificar que haya sesión de empresa
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión como empresa.");
    exit();
}

// Aquí defines la ruta correcta del logo
$logo = '/ClickSoft/IMG/empresas/' . $_SESSION['empresa_logo'];
?>



<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inicio Empresa - ClickSoft</title>
<link rel="stylesheet" href="homeEmpresa.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            <li><a href="../VistaPrincipal/homeEmpresa.php">Inicio</a></li>
            <li><a href="../VistaServicios/serviciosEmpresa.php">Mis Servicios</a></li>
            <li><a href="../VistaReservas/reservasEmpresa.php">Reservas</a></li>
            <li><a href="../VistaPromociones/promocionesEmpresa.php">Promociones</a></li>
        </ul>
        <div class="notificaciones">
            <a href="../VistaNotificaciones/notificaciones.php">
                <i class="fa-solid fa-bell"></i>
                <span class="badge">3</span>
            </a>
        </div>
    </nav>
</header>

<main>
    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></h2>
    <p class="subtitulo">Aquí tienes una vista general de tu actividad en ClickSoft</p>

    <!-- Estadísticas -->
    <section class="estadisticas">
        <div class="card">
            <h3>Reservas pendientes</h3>
            <p>8</p>
        </div>
        <div class="card">
            <h3>Ventas del mes</h3>
            <p>$ 12.350</p>
        </div>
        <div class="card">
            <h3>Calificación promedio</h3>
            <p>4.6 ★</p>
        </div>
    </section>

    <!-- Acciones rápidas -->
    <section class="acciones">
        <h3>Acciones rápidas</h3>
        <div class="acciones-grid">
            <a href="../VistaServicios/VistaPublicarServicios/publicarServicio.php" class="btn"> Publicar servicio</a>
            <a href="#" class="btn"> Gestionar servicios</a>
            <a href="#" class="btn"> Ver reservas</a>
            <a href="#" class="btn"> Crear promoción</a>
        </div>
    </section>

    <!-- Reservas próximas -->
    <section class="reservas-proximas">
        <h3>Reservas próximas</h3>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Juan Pérez</td>
                    <td>Limpieza hogar</td>
                    <td>20/08/2025</td>
                    <td>15:00</td>
                    <td><span class="estado pendiente">Pendiente</span></td>
                </tr>
                <tr>
                    <td>Ana López</td>
                    <td>Jardinería básica</td>
                    <td>21/08/2025</td>
                    <td>10:00</td>
                    <td><span class="estado confirmada">Confirmada</span></td>
                </tr>
            </tbody>
        </table>
    </section>
</main>
<script src="menuHamburguesa.js"></script>
</body>
</html>
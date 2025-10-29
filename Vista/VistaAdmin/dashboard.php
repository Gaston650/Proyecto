<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

// Redirigir si no es administrador
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: ../../Vista/VistaSesion/inicioSesion.php");
    exit();
}

// Instanciamos wrapper
$wrapper = new adminControladorWrapper();
$adminNombre = $_SESSION['user_nombre'] ?? 'Administrador';

// Obtener módulo actual (por defecto 'usuarios')
$moduloActual = $_GET['modulo'] ?? 'usuarios';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="?modulo=usuarios" class="<?= $moduloActual === 'usuarios' ? 'active' : '' ?>">Usuarios</a></li>
            <li><a href="?modulo=servicios" class="<?= $moduloActual === 'servicios' ? 'active' : '' ?>">Servicios</a></li>
            <li><a href="?modulo=categorias" class="<?= $moduloActual === 'categorias' ? 'active' : '' ?>">Categorías</a></li>
            <li><a href="?modulo=reportes" class="<?= $moduloActual === 'reportes' ? 'active' : '' ?>">Reportes</a></li>
            <li><a href="?modulo=historial" class="<?= $moduloActual === 'historial' ? 'active' : '' ?>">Historial</a></li>
            <li><a href="../../cerrar_sesion.php">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header class="header">
            <h1>Bienvenido, <?php echo htmlspecialchars($adminNombre); ?></h1>
        </header>

        <div class="contenido">
            <?php include __DIR__ . '/contenido.php'; ?>
        </div>
    </div>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../superControlador/superControlador.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar sesión
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
        exit();
    }

    // Capturar datos del formulario
    $id_usuario = $_SESSION['user_id'];
    $id_servicio = $_POST['id_servicio'] ?? null;
    $motivo = trim($_POST['motivo'] ?? '');

    // Validar campos
    if (!$id_servicio || empty($motivo)) {
        header("Location: ../../Vista/VistaServicios/servicios.php?reporte=error_campos");
        exit();
    }

    // Conexión a la base de datos
    $conexion = new conexion();
    $conn = $conexion->conectar();

    // Crear wrapper de reportes
    $reporteWrapper = new reporteControladorWrapper($conn);
    $resultado = $reporteWrapper->crearReporte($id_usuario, $id_servicio, $motivo);

    // Redirecciones según resultado
    if ($resultado) {
        header("Location: ../../Vista/VistaServicios/servicios.php?reporte=ok");
    } else {
        header("Location: ../../Vista/VistaServicios/servicios.php?reporte=error_bd");
    }

} else {
    header("Location: ../../Vista/VistaServicios/servicios.php");
    exit();
}
?>

<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

if (isset($_POST['id_servicio'])) {
    $id_servicio = intval($_POST['id_servicio']);
    $empresa_id = $_SESSION['user_id'];

    $servicioWrapper = new servicioControladorWrapper();
    if ($servicioWrapper->eliminarServicio($id_servicio, $empresa_id)) {
        header("Location: ../../Vista/VistaServicios/serviciosEmpresa.php?mensaje=Servicio eliminado correctamente");
        exit();
    } else {
        header("Location: ../../Vista/VistaServicios/serviciosEmpresa.php?error=Error al eliminar el servicio");
        exit();
    }
} else {
    header("Location: ../../Vista/VistaServicios/serviciosEmpresa.php");
    exit();
}
echo "prueba";
?>

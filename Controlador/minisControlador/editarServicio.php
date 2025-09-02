<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../superControlador/superControlador.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_servicio'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $disponibilidad = $_POST['disponibilidad'] ?? '';
    $estado = $_POST['estado'] ?? '';

    $servicioWrapper = new servicioControladorWrapper();
    $resultado = $servicioWrapper->actualizarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado);

    if ($resultado) {
        header("Location: ../../Vista/VistaServicios/serviciosEmpresa.php?exito=Servicio actualizado");
    } else {
        header("Location: ../../Vista/VistaServicios/serviciosEmpresa.php?error=No se pudo actualizar");
    }
    exit();
}

?>

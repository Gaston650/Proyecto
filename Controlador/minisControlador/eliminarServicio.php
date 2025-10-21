<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../superControlador/superControlador.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_servicio = $_POST['id_servicio'] ?? '';

    if (empty($id_servicio)) {
        header("Location: /ClickSoft/Vista/Admin/panelAdmin.php?modulo=servicios&error=ID del servicio no válido");
        exit();
    }

    $wrapper = new adminControladorWrapper();
    $resultado = $wrapper->eliminarServicio($id_servicio);

    if ($resultado) {
        header("Location: /ClickSoft/Vista/VistaAdmin/dashboard.php?modulo=servicios&mensaje=Servicio eliminado correctamente");
    } else {
        header("Location: /ClickSoft/Vista/VistaAdmin/dashboard.php?modulo=servicios&error=No se pudo eliminar el servicio");
    }
    exit();
}
?>

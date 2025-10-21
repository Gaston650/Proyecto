<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['id_usuario'])) {
        $redirect = '/ClickSoft/Vista/VistaAdmin/dashboard.php?modulo=usuarios';
        header("Location: $redirect&mensaje=ID de usuario no proporcionado");
        exit();
    }

    $id = $_POST['id_usuario'];

    // Instanciar wrapper
    $adminWrapper = new adminControladorWrapper();
    $resultado = $adminWrapper->eliminarUsuario($id);

    $redirect = '/ClickSoft/Vista/VistaAdmin/dashboard.php?modulo=usuarios';

    if ($resultado) {
        header("Location: $redirect&mensaje=Usuario eliminado correctamente");
    } else {
        header("Location: $redirect&mensaje=Error al eliminar el usuario");
    }
    exit();

} else {
    $redirect = '/ClickSoft/Vista/VistaAdmin/dashboard.php?modulo=usuarios';
    header("Location: $redirect&mensaje=Acceso no permitido");
    exit();
}
?>

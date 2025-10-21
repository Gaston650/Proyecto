<?php
require_once __DIR__ . '/../superControlador/superControlador.php';

$wrapper = new adminControladorWrapper();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_usuario'];
    $data = [
        'nombre' => $_POST['nombre'] ?? '',
        'email' => $_POST['email'] ?? '',
        'tipo_usuario' => $_POST['tipo_usuario'] ?? 'empresa',
        'estado' => $_POST['estado'] ?? 'activo',
        'rut' => $_POST['rut'] ?? '',
        'zona_cobertura' => $_POST['zona_cobertura'] ?? '',
        'logo' => $_POST['logo'] ?? ''
    ];

    $resultado = $wrapper->editarUsuario($id, $data);

    if ($resultado) {
        $mensaje = urlencode("Usuario editado correctamente");
    } else {
        $mensaje = urlencode("Error al editar usuario");
    }

    header("Location: /ClickSoft/Vista/VistaAdmin/dashboard.php?modulo=usuarios&mensaje=$mensaje");
    exit;
}
?>

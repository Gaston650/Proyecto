<?php
require_once __DIR__ . '/../superControlador/superControlador.php';
require_once __DIR__ . '/../../conexion.php'; // agregar la conexión
require_once __DIR__ . '/../minisControlador/usuarioControlador.php'; // asegurarse de incluir el controlador

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['correo'];
    $password = $_POST['contrasena'];
    $confirmar = $_POST['confirmar_contrasena'];

    if ($password !== $confirmar) {
        header("Location: ../../Vista/VistaRegistro/registrarCliente.php?error=Las contraseñas no coinciden");
        exit();
    }

    // Crear la conexión y pasarla al controlador
    $db = new conexion();
    $conn = $db->conectar();
    $controlador = new UsuarioControlador($conn);

    $resultado = $controlador->guardarUsuario($nombre, $email, $password);

    if ($resultado['exito']) {
        header("Location: ../../Vista/VistaRegistro/registrarCliente.php?exito=1");
        exit();
    } else {
        header("Location: ../../Vista/VistaRegistro/registrarCliente.php?error=" . urlencode($resultado['error']));
        exit();
    }
}
?>

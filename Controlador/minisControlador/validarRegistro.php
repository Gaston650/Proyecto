<?php
require_once __DIR__ . '/../superControlador/superControlador.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['correo'];
    $password = $_POST['contrasena'];
    $confirmar = $_POST['confirmar_contrasena'];

    if ($password !== $confirmar) {
       
        header("Location: ../../Vista/VistaRegistro/registrarCliente.php?error=Las contraseñas no coinciden");
        exit();
    }

    $controlador = new usuarioControlador();
    $resultado = $controlador->guardarUsuario($nombre, $email, $password);

  if ($resultado['exito']) {
        header("Location: ../../Vista/VistaRegistro/registrarCliente.php?exito=1");
        exit();
    } else {
        header("Location: ../../Vista/VistaRegistro/registrarCliente.php?error=" . urlencode($resultado['error']));
        exit();
    }
}


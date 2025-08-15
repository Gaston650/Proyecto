<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';


if (isset($_POST['correo']) && isset($_POST['contrasena'])) {
    $email = trim($_POST['correo']);
    $password = trim($_POST['contrasena']);
    $tipo = $_POST['tipo_usuario'] ?? 'cliente';

    $controlador = new sesionControladorWrapper();

    if ($controlador->login($tipo, $email, $password)) {
        header("Location: ../../Vista/VistaPrincipal/home.php");
        exit();
    } else {
        header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Usuario o contraseña incorrectos.");
        exit();
    }
}

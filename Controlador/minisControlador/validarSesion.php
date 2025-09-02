<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';

if (isset($_POST['correo']) && isset($_POST['contrasena'])) {
    $email = trim($_POST['correo']);
    $password = trim($_POST['contrasena']);
    $tipo = $_POST['tipo_usuario'] ?? 'cliente';

    $controlador = new sesionControladorWrapper();
    $loginExitoso = $controlador->login($tipo, $email, $password);

    if ($loginExitoso) {
        if ($tipo === 'cliente') {
            header("Location: ../../Vista/VistaPrincipal/home.php");
        } else {
            header("Location: ../../Vista/VistaPrincipal/homeEmpresa.php");
        }
        exit();
    } else {
        $mensaje = ($tipo === 'empresa')
            ? "Debes iniciar sesión como empresa o verificar tus credenciales."
            : "Usuario o contraseña incorrectos.";
        header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=" . urlencode($mensaje));
        exit();
    }
}
?>





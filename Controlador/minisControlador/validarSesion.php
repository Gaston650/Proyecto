<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';

if (isset($_POST['correo']) && isset($_POST['contrasena'])) {
    $email = trim($_POST['correo']);
    $password = trim($_POST['contrasena']);
    $tipoSeleccionado = $_POST['tipo_usuario'] ?? 'cliente'; // cliente, empresa o administrador

    $controlador = new sesionControladorWrapper();

    // Intentar iniciar sesión según el tipo seleccionado
    $loginExitoso = $controlador->login($tipoSeleccionado, $email, $password);

    if ($loginExitoso) {
        // Redirigir según tipo real
        $tipo = $_SESSION['tipo_usuario'];

        if ($tipo === 'cliente') {
            header("Location: ../../Vista/VistaPrincipal/home.php");
        } elseif ($tipo === 'empresa') {
            header("Location: ../../Vista/VistaPrincipal/homeEmpresa.php");
        } elseif ($tipo === 'administrador') {
            header("Location: ../../Vista/VistaAdmin/dashboard.php");
        }
        exit();
    } else {
        // Mensaje de error
        $mensaje = $_SESSION['error_login'] ?? "Usuario o contraseña incorrectos.";
        header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=" . urlencode($mensaje));
        exit();
    }
} else {
    header("Location: ../../Vista/VistaSesion/inicioSesion.php");
    exit();
}
?>
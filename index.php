<?php
require_once __DIR__ . '/Controlador/superControlador/superControlador.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Registro de usuario
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['contrasena'] ?? '';

    require_once __DIR__ . '/Controlador/minisControlador/usuarioControlador.php';
    $controlador = new usuarioControlador();
    $controlador->guardarUsuario($nombre, $email, $password);

    header("Location: /ClickSoft/index.php?mensaje=ok");
    exit();
}

// Sesión activa → ir al home según tipo
if (!empty($_SESSION['user_id']) && !empty($_SESSION['tipo_usuario'])) {
    switch ($_SESSION['tipo_usuario']) {
        case 'cliente':
            header('Location: /ClickSoft/Vista/VistaPrincipal/home.php');
            break;
        case 'empresa':
            header('Location: /ClickSoft/Vista/VistaPrincipal/homeEmpresa.php');
            break;
        case 'administrador':
            header('Location: /ClickSoft/Vista/VistaAdmin/dashboard.php');
            break;
        default:
            header('Location: /ClickSoft/index.php');
            break;
    }
    exit();
}

// Sin sesión → mostrar landing directamente
include __DIR__ . '/index.html';
exit();
?>
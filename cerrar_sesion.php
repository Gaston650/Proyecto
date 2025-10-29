<?php
require_once 'Controlador/superControlador/superControlador.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// --- Guardar datos de sesión antes de destruirla ---
$tipo_usuario = $_SESSION['tipo_usuario'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

// Limpiar sesión
$_SESSION = [];
session_destroy();

// Eliminar cookie de sesión
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Eliminar cookies de "recordarme"
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}
if (isset($_COOKIE['tipo_usuario'])) {
    setcookie('tipo_usuario', '', time() - 3600, '/');
}

// Limpiar token en la base de datos
if ($tipo_usuario && $user_id) {
    if ($tipo_usuario === 'cliente' || $tipo_usuario === 'administrador') {
        $usuarioModelo = new usuarioModelo();
        $usuarioModelo->guardarToken($user_id, null); // borra token
    } elseif ($tipo_usuario === 'empresa') {
        $empresaModelo = new empresaModelo();
        $empresaModelo->actualizarEmpresaRememberToken($user_id, null);
    }
}

// Redirigir al login
header('Location: ./index.php');
exit();

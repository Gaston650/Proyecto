<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar datos obligatorios
    if (!isset($_POST['nombre'], $_POST['email'], $_POST['tipo_usuario'], $_POST['estado'], $_POST['contraseña'])) {
        $redirect = '/ClickSoft/Vista/VistaAdmin/dashboard.php?modulo=usuarios';
        header("Location: $redirect&mensaje=Faltan datos para crear el usuario");
        exit();
    }

    $tipo = $_POST['tipo_usuario'];
    $rut = $_POST['rut'] ?? null;

    // Validación mínima para empresas
    if ($tipo === 'empresa') {
        $rut = strtoupper(trim($rut));
        if (empty($rut)) {
            $redirect = '/ClickSoft/Vista/VistaAdmin/dashboard.php?modulo=usuarios';
            header("Location: $redirect&mensaje=RUT requerido para empresa");
            exit();
        }
        // Solo caracteres válidos (números y letras, sin espacios extra)
        if (!preg_match('/^[0-9A-Z]+$/', $rut)) {
            $redirect = '/ClickSoft/Vista/VistaAdmin/dashboard.php?modulo=usuarios';
            header("Location: $redirect&mensaje=RUT inválido. Solo se permiten números y letras");
            exit();
        }
    }

    $data = [
        'nombre' => trim($_POST['nombre']),
        'email' => trim($_POST['email']),
        'tipo_usuario' => $tipo,
        'estado' => $_POST['estado'],
        'contraseña' => password_hash($_POST['contraseña'], PASSWORD_DEFAULT),
        'rut' => $rut
    ];

    try {
        $adminWrapper = new adminControladorWrapper();
        $resultado = $adminWrapper->crearUsuario($data);

        $redirect = '/ClickSoft/Vista/VistaAdmin/dashboard.php?modulo=usuarios';
        if ($resultado) {
            header("Location: $redirect&mensaje=Usuario creado correctamente");
        } else {
            header("Location: $redirect&mensaje=Error al crear el usuario");
        }
    } catch (Exception $e) {
        $redirect = '/ClickSoft/Vista/VistaAdmin/dashboard.php?modulo=usuarios';
        header("Location: $redirect&mensaje=" . urlencode($e->getMessage()));
    }
    exit();

} else {
    $redirect = '/ClickSoft/Vista/VistaAdmin/dashboard.php?modulo=usuarios';
    header("Location: $redirect&mensaje=Acceso no permitido");
    exit();
}
?>
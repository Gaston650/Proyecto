<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../Modelo/modeloRegistro.php';

$modelo = new usuarioModelo();

// Restaurar sesión solo si existen cookies válidas
if (empty($_SESSION['user_id']) && !empty($_COOKIE['remember_token']) && !empty($_COOKIE['tipo_usuario'])) {

    $token = $_COOKIE['remember_token'];
    $tipo_usuario = $_COOKIE['tipo_usuario'];

    // Usuario
    if ($tipo_usuario === 'cliente' || $tipo_usuario === 'administrador') {
        $usuario = $modelo->obtenerUsuarioPorToken($token);
        if ($usuario) {
            $_SESSION['user_id'] = $usuario['id_usuario'];
            $_SESSION['user_nombre'] = $usuario['nombre'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
            $_SESSION['email'] = $usuario['email'] ?? '';
        }
    } elseif ($tipo_usuario === 'empresa') {
        $db = new Conexion();
        $conn = $db->conectar();
        $stmt = $conn->prepare("SELECT id_empresa, nombre_empresa FROM empresas_proveedor WHERE remember_token=? LIMIT 1");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $empresa = $stmt->get_result()->fetch_assoc();
        if ($empresa) {
            $_SESSION['user_id'] = (int)$empresa['id_empresa'];
            $_SESSION['empresa_id'] = (int)$empresa['id_empresa'];
            $_SESSION['nombre_empresa'] = $empresa['nombre_empresa'];
            $_SESSION['tipo_usuario'] = 'empresa';
        }
    }

    // Token inválido → borrar cookies
    if (empty($_SESSION['user_id'])) {
        setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        setcookie('tipo_usuario', '', time() - 3600, '/', '', false, true);
    }
}
?>

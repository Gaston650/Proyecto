<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class controladorCerrarSesion {
    public function cerrar() {
        // Antes de destruir sesión, limpiar remember_token en BD y cookies
        require_once __DIR__ . '/../../Modelo/modeloRegistro.php';
        $modelo = new usuarioModelo();

        // Si hay usuario logueado, limpiar su remember_token
        if (!empty($_SESSION['user_id']) && ($_SESSION['tipo_usuario'] ?? '') === 'cliente') {
            $modelo->actualizarRememberToken((int)$_SESSION['user_id'], null);
        }

        // Si hay empresa logueada, limpiar su remember_token
        if (!empty($_SESSION['empresa_id']) || (isset($_SESSION['user_id']) && ($_SESSION['tipo_usuario'] ?? '') === 'empresa')) {
            $empresa_id = $_SESSION['empresa_id'] ?? $_SESSION['user_id'];
            if ($empresa_id) $modelo->actualizarEmpresaRememberToken((int)$empresa_id, null);
        }

        // Limpiar cookies relacionadas
        if (isset($_COOKIE['remember_token'])) setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        if (isset($_COOKIE['user_token'])) setcookie('user_token', '', time() - 3600, '/', '', false, true);
        if (isset($_COOKIE['tipo_usuario'])) setcookie('tipo_usuario', '', time() - 3600, '/', '', false, true);

        // Limpiamos todas las variables de sesión
        $_SESSION = [];

        // Destruimos la sesión
        session_destroy();

        // Redirigimos al inicio de sesión (ruta corregida)
        header("Location: ../../index.php");
        exit();
    }
}
?>

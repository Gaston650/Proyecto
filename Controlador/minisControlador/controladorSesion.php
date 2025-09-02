<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Modelo/modeloRegistro.php';
require_once __DIR__ . '/../../Modelo/modeloRegistrarEmpresa.php';

class controladorSesion {
    private $usuarioModelo;
    private $empresaModelo;

    public function __construct() {
        $this->usuarioModelo = new usuario2Modelo(); // modelo de usuarios
        $this->empresaModelo = new empresaModelo();  // modelo de empresas
    }

    // Iniciar sesión para usuarios/clientes
    public function iniciarSesionUsuario($email, $password) {
    $usuario = $this->usuarioModelo->obtenerUsuario($email);
    if ($usuario && password_verify($password, $usuario['contraseña'])) {

        // Guardar datos del usuario actual
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_nombre'] = $usuario['nombre'];
        $_SESSION['tipo_usuario'] = 'cliente';

        return true;
    } else {
        $_SESSION['error_login'] = "Debes iniciar sesión como cliente o verificar tus credenciales.";
        return false;
    }
}

// Iniciar sesión para empresas
public function iniciarSesionEmpresa($email, $password) {
    $empresa = $this->empresaModelo->obtenerEmpresa($email);
    if ($empresa && password_verify($password, $empresa['contraseña'])) {
        // Guardar datos de la empresa actual
        $_SESSION['user_id'] = $empresa['id_empresa'];
        $_SESSION['user_nombre'] = $empresa['nombre_empresa'];
        $_SESSION['empresa_logo'] = $empresa['logo']; // opcional
        $_SESSION['tipo_usuario'] = 'empresa';

        return true;
    } else {
        $_SESSION['error_login'] = "Debes iniciar sesión como empresa o verificar tus credenciales.";
        return false;
    }
}
}
?>

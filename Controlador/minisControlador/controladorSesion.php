<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Modelo/modeloRegistrarEmpresa.php';
require_once __DIR__ . '/../../Modelo/modeloRegistro.php';

class controladorSesion {
    private $usuarioModelo;
    private $empresaModelo;

    public function __construct() {
        $this->usuarioModelo = new usuarioModelo();
        $this->empresaModelo = new empresaModelo();
    }

    public function iniciarSesionUsuario($email, $password) {
        $usuario = $this->usuarioModelo->loginUsuario($email);
        if ($usuario && password_verify($password, $usuario['contraseña'])) {
            $_SESSION['user_id'] = $usuario['id_usuario'];
            $_SESSION['user_nombre'] = $usuario['nombre'];
            $_SESSION['tipo_usuario'] = 'cliente';
            return true;
        }
        return false;
    }

    public function iniciarSesionEmpresa($email, $password) {
        $empresa = $this->empresaModelo->obtenerEmpresa($email);
        if ($empresa && password_verify($password, $empresa['contraseña'])) {
            $_SESSION['user_id'] = $empresa['id_empresa'];
            $_SESSION['user_nombre'] = $empresa['nombre_empresa'];
            $_SESSION['tipo_usuario'] = 'empresa';
            return true;
        }
        return false;
    }
}
?>







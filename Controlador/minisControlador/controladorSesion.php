<?php
require_once __DIR__ . '/../../Modelo/modeloRegistro.php';
require_once __DIR__ . '/../../Modelo/modeloRegistrarEmpresa.php';

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
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['user_image'] = '../../img/perfil-vacio.png';
            return true;
        }
        return false;
    }

    public function iniciarSesionEmpresa($email, $password) {
        $empresa = $this->empresaModelo->loginEmpresa($email);
        if ($empresa && password_verify($password, $empresa['contraseña'])) {
            $_SESSION['empresa_id'] = $empresa['id_empresa'];
            $_SESSION['nombre_empresa'] = $empresa['nombre_empresa'];
            $_SESSION['logo_empresa'] = $empresa['logo'];
            return true;
        }
        return false;
    }
}

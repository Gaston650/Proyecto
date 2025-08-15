<?php
require_once __DIR__ . '/../minisControlador/usuarioControlador.php';
require_once __DIR__ . '/../minisControlador/controladorEmpresa.php';
require_once __DIR__ . '/../minisControlador/controladorSesion.php';
require_once __DIR__ . '/../minisControlador/controladorCerrarSesion.php';

class usuarioControladorWrapper {
    private $controlador;

    public function __construct() {
        $this->controlador = new usuarioControlador();
    }

    public function registrar($nombre, $email, $password) {
        return $this->controlador->guardarUsuario($nombre, $email, $password);
    }

    public function loginGoogle($nombre, $email) {
        return $this->controlador->loginGoogle($nombre, $email);
    }
}

class empresaControladorWrapper {
    private $controlador;

    public function __construct() {
        $this->controlador = new empresaControlador();
    }

    public function registrar($nombre, $email, $zona, $logo, $rut, $password, $telefono) {
        return $this->controlador->guardarEmpresa($nombre, $email, $zona, $logo, $rut, $password, $telefono);
    }
}

class sesionControladorWrapper {
    private $controlador;

    public function __construct() {
        $this->controlador = new controladorSesion();
    }

    public function login($tipo, $email, $password) {
        if ($tipo === 'empresa') {
            return $this->controlador->iniciarSesionEmpresa($email, $password);
        }
        return $this->controlador->iniciarSesionUsuario($email, $password);
    }
}

class cerrarSesionControladorWrapper{
    private $controlador;

    public function __construct() {
        $this->controlador = new controladorCerrarSesion();
    }

    public function cerrarSesion() {
        return $this->controlador->cerrar();
    }
}
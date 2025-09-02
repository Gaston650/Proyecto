<?php
session_start();
require_once __DIR__ . '/../../Modelo/modeloUsuario.php';
require_once __DIR__ . '/../../conexion.php';

class LoginControlador {
    private $usuarioModelo;

    public function __construct($conn) {
        $this->usuarioModelo = new usuarioModelo($conn);
    }

    public function login($email, $password) {
        // Intenta cliente
        $usuario = $this->usuarioModelo->obtenerUsuario($email, $password);

        if ($usuario) {
            $_SESSION['user_id'] = $usuario['id_usuario'];
            $_SESSION['user_nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = 'cliente';
            return $usuario;
        }

        // Intenta empresa
        $empresa = $this->usuarioModelo->obtenerEmpresa($email, $password);

        if ($empresa) {
            $_SESSION['empresa_id'] = $empresa['id_empresa'];
            $_SESSION['nombre_empresa'] = $empresa['nombre'];
            $_SESSION['rol'] = 'empresa';
            return $empresa;
        }

        return false;
    }
}

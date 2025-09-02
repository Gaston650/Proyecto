<?php
require_once __DIR__ . '/../../Modelo/modeloUsuario.php';

class UsuarioControlador {
    private $usuarioModelo;

    // Constructor recibe la conexión y la pasa al modelo
    public function __construct($conn) {
        $this->usuarioModelo = new usuario2Modelo($conn);
    }

    // Guarda un usuario con datos tradicionales
    public function guardarUsuario($nombre, $email, $password) {
        return $this->usuarioModelo->insertarUsuario($nombre, $email, $password);
    }

    // Login mediante Google
    public function loginGoogle($nombre, $email) {
        $usuario = $this->usuarioModelo->obtenerUsuario($email); // revisa si existe en usuarios

        if (!$usuario) {
            $this->usuarioModelo->registrarUsuarioGoogle($nombre, $email);
            $usuario = $this->usuarioModelo->obtenerUsuario($email);
        }

        return $usuario;
    }
}
?>

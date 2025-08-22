<?php
require_once __DIR__ . '/../../Modelo/modeloUsuario.php';

class UsuarioControlador {
    private $usuarioModelo;

    public function __construct($conn) {
        $this->usuarioModelo = new UsuarioModelo($conn);
    }

    public function guardarUsuario($nombre, $email, $password) {
        return $this->usuarioModelo->insertarUsuario($nombre, $email, $password);
    }

    public function loginGoogle($nombre, $email) {
        $usuario = $this->usuarioModelo->buscarPorEmail($email);

        if (!$usuario) {
            $this->usuarioModelo->registrarUsuarioGoogle($nombre, $email);
            $usuario = $this->usuarioModelo->buscarPorEmail($email);
        }

        return $usuario;
    }
}
?>


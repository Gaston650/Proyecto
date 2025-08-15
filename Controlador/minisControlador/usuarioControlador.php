<?php
require_once __DIR__ . '/miniControladorRegistro.php';

class usuarioControlador {
    private $registroMini;

    public function __construct() {
        $this->registroMini = new miniControladorRegistro();
    }

    public function guardarUsuario($nombre, $email, $password) {
        return $this->registroMini->registrarCliente($nombre, $email, $password);
    }

    public function loginGoogle($nombre, $email) {
        $modelo = new usuarioModelo();  // acceso directo al modelo
        $usuario = $modelo->buscarPorEmail($email);

        if (!$usuario) {
            $modelo->registrarUsuarioGoogle($nombre, $email);
            $usuario = $modelo->buscarPorEmail($email);
        }

        return $usuario;
    }
}

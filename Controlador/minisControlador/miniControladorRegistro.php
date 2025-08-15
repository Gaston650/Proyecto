<?php
require_once __DIR__ . '/../../Modelo/modeloRegistro.php';

class miniControladorRegistro {
    private $modelo;

    public function __construct() {
        $this->modelo = new usuarioModelo();
    }

    public function registrarCliente($nombre, $email, $password) {
        // Validar si el correo ya existe
        $existe = $this->modelo->buscarPorEmail($email);
        if ($existe) {
            return ['exito' => false, 'error' => 'El correo ya está registrado.'];
        }

        // Encriptar contraseña
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar usuario
        $ok = $this->modelo->insertarUsuario($nombre, $email, $hash);
        return $ok ? ['exito' => true] : ['exito' => false, 'error' => 'Error al registrar.'];
    }
}

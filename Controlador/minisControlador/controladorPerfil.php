<?php
require_once __DIR__ . '/../../Modelo/modeloPerfil.php';

class perfilControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new perfilModelo();
    }

    public function obtenerPerfil($id_usuario) {
        return $this->modelo->obtenerPerfil($id_usuario);
    }

    public function guardarPerfil($id_usuario, $direccion, $ciudad, $biografia, $foto) {
        return $this->modelo->guardarPerfil($id_usuario, $direccion, $ciudad, $biografia, $foto);
    }

    public function obtenerMetodoPago($id_usuario) {
        return $this->modelo->obtenerMetodoPago($id_usuario);
    }

    public function guardarMetodoPago($id_usuario, $tipo) {
        return $this->modelo->guardarMetodoPago($id_usuario, $tipo);
    }
}
?>
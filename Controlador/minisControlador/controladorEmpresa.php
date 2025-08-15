<?php
require_once __DIR__ . '/../../Modelo/modeloRegistrarEmpresa.php';

class empresaControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new empresaModelo();
    }

    public function guardarEmpresa($nombre, $email, $zona, $logo, $rut, $password, $telefono) {
        return $this->modelo->insertarEmpresa($nombre, $email, $zona, $logo, $rut, $password, $telefono);
    }
}

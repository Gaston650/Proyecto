<?php
require_once __DIR__ . '/../../Modelo/modeloReporte.php';

class controladorReporte {
    private $modelo;

    public function __construct($conexion) {
        $this->modelo = new modeloReporte($conexion);
    }

    public function crearReporte($id_usuario, $id_servicio, $motivo) {
        return $this->modelo->crearReporte($id_usuario, $id_servicio, $motivo);
    }

    public function obtenerPorServicio($id_servicio) {
        return $this->modelo->obtenerPorServicio($id_servicio);
    }
}
?>

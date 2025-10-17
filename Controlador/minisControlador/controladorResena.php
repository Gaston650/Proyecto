<?php
require_once __DIR__ . '/../../Modelo/modeloResena.php';

class controladorResena {
    public $modelo;

    public function __construct() {
        $this->modelo = new modeloResena();
    }

    // Guardar reseña
    public function guardar($id_cliente, $id_servicio, $comentario, $calificacion) {
        return $this->modelo->guardar($id_cliente, $id_servicio, $comentario, $calificacion);
    }

    // Obtener reseñas de un servicio
    public function obtenerPorServicio($id_servicio) {
        return $this->modelo->obtenerResenasPorServicio($id_servicio);
    }

    // Obtener reseña de un cliente a un servicio
    public function obtenerPorClienteYServicio($id_cliente, $id_servicio) {
        return $this->modelo->obtenerResenaPorReserva($id_cliente, $id_servicio);
    }

    // Obtener calificación promedio de una empresa
    public function obtenerPromedioPorEmpresa($id_empresa) {
        return $this->modelo->obtenerPromedioPorEmpresa($id_empresa);
    }
}
?>

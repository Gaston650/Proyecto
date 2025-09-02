<?php
require_once __DIR__ . '/../../Modelo/modeloHistorial.php';

class controladorHistorial {

    private $modelo;

    public function __construct() {
        $this->modelo = new HistorialModelo();
    }

    public function listarHistorial($id_cliente, $estado = null) {
        return $this->modelo->obtenerHistorialReserva($id_cliente, $estado);
    }

    public function listarReservasEmpresa($id_empresa, $estado = null) {
        return $this->modelo->obtenerReservasEmpresa($id_empresa, $estado);
    }

    public function agregarComentario($id_reserva, $comentarios_cliente, $calificacion, $comentario_calificacion = null) {
        return $this->modelo->agregarComentario($id_reserva, $comentarios_cliente, $calificacion, $comentario_calificacion);
    }

    public function cancelarReserva($id_reserva, $motivo = null) {
        return $this->modelo->cancelarReserva($id_reserva, $motivo);
    }
}
?>

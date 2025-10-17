<?php
require_once __DIR__ . '/../../Modelo/modeloPago.php';

class pagoControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new modeloPago();
    }

    // Guarda un pago con un estado (por defecto 'pendiente')
    public function guardarPago($id_reserva, $monto, $estado = 'pendiente') {
        if (empty($id_reserva) || empty($monto)) {
            return false;
        }
        return $this->modelo->insertarPago($id_reserva, $monto, $estado);
    }

    // Obtiene todos los pagos (opcional para reportes)
    public function obtenerPagos() {
        return $this->modelo->obtenerPagos();
    }

    // Obtiene los pagos de una reserva específica
    public function obtenerPagosPorReserva($id_reserva) {
        return $this->modelo->obtenerPagosPorReserva($id_reserva);
    }

     // Obtiene el total de ventas del mes para una empresa
    public function obtenerVentasDelMes($id_empresa) {
        if (empty($id_empresa)) {
            return 0;
        }
        return $this->modelo->obtenerVentasDelMes($id_empresa);
    }

}
?>

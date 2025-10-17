<?php
require_once __DIR__ . '/../../Modelo/modeloReservas.php';
require_once __DIR__ . '/../../Modelo/modeloNotificaciones.php';

class notificacionPagoControlador {

    private $modeloReservas;
    private $modeloNotificaciones;

    public function __construct() {
        $this->modeloReservas = new ModeloReservas();
        $this->modeloNotificaciones = new modeloNotificaciones();
    }

    /**
     * Genera una notificación de pago para el proveedor correspondiente
     * @param int $id_reserva
     * @param float $monto
     * @return bool
     */
    public function generarNotificacion($id_reserva, $monto) {
        // Obtener información de la reserva
        $reserva = $this->modeloReservas->obtenerReservaPorId($id_reserva);

        if (!$reserva) return false;

        $id_proveedor = $reserva['id_empresa'] ?? null;
        $id_cliente = $reserva['id_cliente'] ?? null;
        $titulo_servicio = $reserva['nombre_servicio'] ?? 'tu servicio';

        if (!$id_proveedor || !$id_cliente) return false;

        // Obtener información del cliente
        $cliente_info = $this->modeloReservas->obtenerClientePorId($id_cliente);
        $nombre_cliente = $cliente_info['nombre'] ?? 'Cliente';

        // Crear mensaje de notificación
        $mensaje = "$nombre_cliente ha realizado el pago de \$" . number_format($monto, 2) . " por el servicio '$titulo_servicio'.";

        // Insertar la notificación en la tabla notificaciones
        return $this->modeloNotificaciones->insertarNotificacion($id_proveedor, $mensaje, 'pago');
    }
}
?>

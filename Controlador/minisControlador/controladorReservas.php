<?php
require_once __DIR__ . '/../../Modelo/modeloReservas.php';

class controladorReservas {

    public function crearReserva($id_cliente, $id_servicio, $fecha, $hora) {
        return ModeloReservas::crearReserva($id_cliente, $id_servicio, $fecha, $hora);
    }

    public function verReservasCliente($id_cliente) {
        return ModeloReservas::obtenerReservasPorCliente($id_cliente);
    }

    public function verReservasProveedor($id_proveedor) {
        return ModeloReservas::obtenerReservasPorProveedor($id_proveedor);
    }

    public function cancelarReserva($id_reserva) {
        return ModeloReservas::cancelarReserva($id_reserva);
    }


    public function reprogramarReserva($id_reserva, $fecha, $hora) {
        return ModeloReservas::reprogramarReserva($id_reserva, $fecha, $hora);
    }

    public function actualizarEstado($id_reserva, $estado) {
        return ModeloReservas::actualizarEstado($id_reserva, $estado);
    }

   public function verReservasProveedorFiltradas($id_proveedor, $estado = '', $fecha_inicio = '', $fecha_fin = '') {
        $resultado = $this->verReservasProveedor($id_proveedor);

        // Convertir a array
        $reservas = [];
        if ($resultado instanceof mysqli_result) {
            while ($fila = $resultado->fetch_assoc()) {
                $reservas[] = $fila;
            }
        }

        // Filtrar según parámetros
        return array_filter($reservas, function($r) use ($estado, $fecha_inicio, $fecha_fin) {
            if ($estado && $r['estado_reserva'] !== $estado) return false;
            if ($fecha_inicio && $r['fecha_reserva'] < $fecha_inicio) return false;
            if ($fecha_fin && $r['fecha_reserva'] > $fecha_fin) return false;
            return true;
        });
    }


}
?>

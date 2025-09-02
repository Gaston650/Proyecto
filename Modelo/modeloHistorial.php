<?php
require_once __DIR__ . '/../conexion.php';

class HistorialModelo {

    // Obtener historial de reservas por cliente
    public function obtenerHistorialReserva($id_cliente, $estado = null) {
        $conexion = new conexion();
        $db = $conexion->conectar();

        $query = "SELECT * FROM reservas WHERE id_cliente = $id_cliente";
        if ($estado) {
            $query .= " AND estado_reserva = '$estado'";
        }

        $resultado = $db->query($query);
        $reservas = [];

        if ($resultado) {
            while ($reserva = $resultado->fetch_assoc()) {
                // Obtener datos del servicio
                $id_servicio = $reserva['id_servicio'];
                $resServicio = $db->query("SELECT * FROM servicios WHERE id_servicio = $id_servicio");
                if ($resServicio && $resServicio->num_rows > 0) {
                    $servicio = $resServicio->fetch_assoc();

                    // Obtener proveedor del servicio
                    $id_proveedor = $servicio['id_empresa'] ?? null;
                    if ($id_proveedor && is_numeric($id_proveedor)) {
                        $resUser = $db->query("SELECT nombre_empresa FROM empresas_proveedor WHERE id_empresa = $id_proveedor");
                        $servicio['nombre_proveedor'] = ($resUser && $resUser->num_rows > 0)
                            ? $resUser->fetch_assoc()['nombre_empresa']
                            : 'Desconocido';
                    } else {
                        $servicio['nombre_proveedor'] = 'Desconocido';
                    }

                } else {
                    $servicio = [
                        'titulo' => 'Servicio eliminado',
                        'precio' => 0,
                        'nombre_proveedor' => 'Desconocido'
                    ];
                }

                $reserva['servicio'] = $servicio;

                // Obtener nombre del cliente
                $id_cliente_reserva = $reserva['id_cliente'];
                $resCliente = $db->query("SELECT nombre FROM usuarios WHERE id_usuario = $id_cliente_reserva");
                $reserva['nombre_cliente'] = ($resCliente && $resCliente->num_rows > 0)
                    ? $resCliente->fetch_assoc()['nombre']
                    : 'Desconocido';

                $reservas[] = $reserva;
            }
        }

        return $reservas;
    }

    // Obtener todas las reservas de los servicios de una empresa (sin JOIN)
    public function obtenerReservasEmpresa($id_empresa, $estado = null) {
        $conexion = new conexion();
        $db = $conexion->conectar();

        $reservas = [];

        // Obtener todos los servicios de la empresa
        $resServicios = $db->query("SELECT id_servicio, titulo FROM servicios WHERE id_empresa = $id_empresa");
        if ($resServicios && $resServicios->num_rows > 0) {
            while ($servicio = $resServicios->fetch_assoc()) {
                $id_servicio = $servicio['id_servicio'];

                // Obtener todas las reservas de este servicio
                $query = "SELECT * FROM reservas WHERE id_servicio = $id_servicio";
                if ($estado) {
                    $query .= " AND estado_reserva = '$estado'";
                }

                $resReservas = $db->query($query);
                if ($resReservas && $resReservas->num_rows > 0) {
                    while ($reserva = $resReservas->fetch_assoc()) {
                        // Agregar título del servicio
                        $reserva['titulo'] = $servicio['titulo'];

                        // Obtener nombre del cliente
                        $id_cliente = $reserva['id_cliente'];
                        $resCliente = $db->query("SELECT nombre FROM usuarios WHERE id_usuario = $id_cliente");
                        $reserva['nombre_cliente'] = ($resCliente && $resCliente->num_rows > 0)
                            ? $resCliente->fetch_assoc()['nombre']
                            : 'Desconocido';

                        $reservas[] = $reserva;
                    }
                }
            }
        }

        return $reservas;
    }

    public function agregarComentario($id_reserva, $comentarios_cliente, $calificacion, $comentario_calificacion = null) {
        $conexion = new conexion();
        $db = $conexion->conectar();
        $comentario_calificacion = $comentario_calificacion ?? '';
        $query = "UPDATE reservas SET 
                    comentarios_cliente = '$comentarios_cliente', 
                    calificacion = $calificacion,
                    comentario_calificacion = '$comentario_calificacion'
                  WHERE id_reserva = $id_reserva";
        return $db->query($query);
    }

    public function cancelarReserva($id_reserva, $motivo = null) {
        $conexion = new conexion();
        $db = $conexion->conectar();
        $motivo = $motivo ?? '';
        $query = "UPDATE reservas 
                  SET estado_reserva = 'cancelada', 
                      comentario_calificacion = '$motivo'
                  WHERE id_reserva = $id_reserva";
        return $db->query($query);
    }
}
?>

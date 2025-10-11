<?php
require_once __DIR__ . '/../../Modelo/modeloReservas.php';
require_once __DIR__ . '/../superControlador/superControlador.php'; // Wrapper notificaciones

class controladorReservas {

    // Crear reserva y enviar notificación al proveedor
    public function crearReserva($id_cliente, $id_servicio, $fecha, $hora, $comentarios = '') {
        // Crear la reserva en la base de datos
        $reserva_creada = ModeloReservas::crearReserva($id_cliente, $id_servicio, $fecha, $hora, $comentarios);

        if ($reserva_creada) {
            // Obtener info del servicio
            $servicio = ModeloReservas::obtenerServicioPorId($id_servicio);
            $id_proveedor = $servicio['id_proveedor'] ?? null;
            $titulo_servicio = $servicio['titulo'] ?? 'tu servicio';

            if ($id_proveedor) {
                // Obtener nombre del cliente
                $cliente_info = ModeloReservas::obtenerClientePorId($id_cliente);
                $nombre_cliente = $cliente_info['nombre'] ?? 'Cliente';

                // Crear mensaje de notificación
                $mensaje = "$nombre_cliente ha reservado tu servicio '$titulo_servicio' para la fecha $fecha a las $hora.";
                if (!empty($comentarios)) {
                    $mensaje .= " Comentarios del cliente: \"$comentarios\"";
                }

                // Enviar notificación
                $notif = new notificacionControladorWrapper();
                $notif->insertarNotificacion($id_proveedor, $mensaje, 'confirmación');
            }
        }

        return $reserva_creada;
    }

    // Ver reservas de un cliente
    public function verReservasCliente($id_cliente) {
        return ModeloReservas::obtenerReservasPorCliente($id_cliente);
    }

    // Ver reservas de un proveedor
    public function verReservasProveedor($id_proveedor) {
        return ModeloReservas::obtenerReservasPorProveedor($id_proveedor);
    }

    // Cancelar reservas
public function cancelarReserva($id_reserva, $id_cliente) {
    $resultado = ModeloReservas::cancelarReserva($id_reserva, $id_cliente);

    if ($resultado) {
        // Obtener info de la reserva cancelada
        $reserva = ModeloReservas::obtenerReservaPorId($id_reserva);

        if ($reserva) {
            $id_proveedor = $reserva['id_empresa'] ?? null;
            $nombre_cliente = $reserva['nombre_cliente'] ?? 'Cliente';
            $titulo_servicio = $reserva['nombre_servicio'] ?? 'tu servicio';

            if ($id_proveedor) {
                // Crear mensaje de notificación
                $mensaje = "$nombre_cliente ha cancelado tu reserva para '$titulo_servicio'.";

                // Insertar notificación
                $notif = new notificacionControladorWrapper();
                $notif->insertarNotificacion($id_proveedor, $mensaje, 'alerta');
            }
        }
    }

    return $resultado;
}


   // Reprogramar reserva
    public function reprogramarReserva($id_reserva, $fecha, $hora, $id_cliente) {
        $resultado = ModeloReservas::reprogramarReserva($id_reserva, $fecha, $hora, $id_cliente);

        if ($resultado) {
            $reserva = ModeloReservas::obtenerReservaPorId($id_reserva);

            if ($reserva) {
                $id_proveedor = $reserva['id_empresa'] ?? null;
                $nombre_cliente = $reserva['nombre_cliente'] ?? 'Cliente';
                $titulo_servicio = $reserva['nombre_servicio'] ?? 'tu servicio';

                if ($id_proveedor) {
                    $mensaje = "$nombre_cliente ha reprogramado tu reserva para '$titulo_servicio' al día $fecha a las $hora.";
                    $notif = new notificacionControladorWrapper();
                    $notif->insertarNotificacion($id_proveedor, $mensaje, 'alerta');

                    if (!$insertado) {
                        error_log("No se pudo insertar la notificación para la reserva $id_reserva");
                    }
                }
            }
        }

        return $resultado;
    }





    // Actualizar estado de reserva
    public function actualizarEstado($id_reserva, $estado) {
        return ModeloReservas::actualizarEstado($id_reserva, $estado);
    }

    // Ver reservas filtradas de proveedor
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
            if ($estado && strtolower($r['estado_reserva']) !== strtolower($estado)) return false;
            if ($fecha_inicio && $r['fecha_reserva'] < $fecha_inicio) return false;
            if ($fecha_fin && $r['fecha_reserva'] > $fecha_fin) return false;
            return true;
        });
    }

    // Confirmar reserva
    public function confirmarReserva($id_reserva) {
        return ModeloReservas::actualizarEstado($id_reserva, 'confirmada');
    }

    // Rechazar reserva
    public function rechazarReserva($id_reserva) {
        return ModeloReservas::actualizarEstado($id_reserva, 'rechazada');
    }
}
?>


<?php
require_once __DIR__ . '/../conexion.php';

class ModeloReservas {

    // Crear reserva
    public static function crearReserva($id_cliente, $id_servicio, $fecha, $hora, $comentarios = '') {
        $db = new conexion();
        $conn = $db->conectar();

        $stmt = $conn->prepare("
            INSERT INTO reservas (id_cliente, id_servicio, fecha_reserva, hora_reserva, comentarios_cliente)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisss", $id_cliente, $id_servicio, $fecha, $hora, $comentarios);

        $resultado = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $resultado;
    }

    // Obtener servicio por id (para notificación)
    public static function obtenerServicioPorId($id_servicio) {
        $conexion = new conexion();
        $conn = $conexion->conectar();
        $sql = "SELECT id_empresa AS id_proveedor, titulo FROM servicios WHERE id_servicio = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_servicio);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $resultado;
    }

    // Obtener nombre del cliente por id
    public static function obtenerClientePorId($id_cliente) {
        $db = new conexion();
        $conn = $db->conectar();

        $stmt = $conn->prepare("SELECT nombre FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        $conn->close();

        return $resultado;
    }

    // Obtener reservas de un cliente
    public static function obtenerReservasPorCliente($id_cliente) {
        $conn = (new conexion())->conectar();

        $stmt = $conn->prepare("
            SELECT r.*,
                   s.titulo AS nombre_servicio,
                   e.id_empresa AS id_proveedor,
                   e.nombre_empresa AS nombre_proveedor,
                   s.precio AS monto
            FROM reservas r
            JOIN servicios s ON s.id_servicio = r.id_servicio
            JOIN empresas_proveedor e ON e.id_empresa = s.id_empresa
            WHERE r.id_cliente = ?
            ORDER BY r.fecha_reserva, r.hora_reserva
        ");
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $conn->close();

        return $resultado;
    }

    // Obtener reservas de un proveedor
    public static function obtenerReservasPorProveedor($id_proveedor) {
        $conn = (new conexion())->conectar();

        $stmt = $conn->prepare("
            SELECT r.*,
                (SELECT titulo FROM servicios s WHERE s.id_servicio = r.id_servicio) AS nombre_servicio,
                (SELECT nombre FROM usuarios u WHERE u.id_usuario = r.id_cliente) AS nombre_cliente
            FROM reservas r
            WHERE r.id_servicio IN (SELECT id_servicio FROM servicios WHERE id_empresa = ?)
            ORDER BY r.fecha_reserva, r.hora_reserva
        ");
        $stmt->bind_param("i", $id_proveedor);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $stmt->close();
        $conn->close();

        return $resultado;
    }

    // Cancelar reserva
    public static function cancelarReserva($id_reserva, $id_cliente) {
        $db = new conexion();
        $conn = $db->conectar();

        $stmt = $conn->prepare("
            UPDATE reservas
            SET estado_reserva = 'cancelada'
            WHERE id_reserva = ? AND id_cliente = ?
        ");
        $stmt->bind_param("ii", $id_reserva, $id_cliente);
        $resultado = $stmt->execute();

        $stmt->close();
        $conn->close();

        return $resultado;
    }

    // Reprogramar reserva
    public static function reprogramarReserva($id_reserva, $fecha, $hora, $id_cliente) {
        $db = new conexion();
        $conn = $db->conectar();

        $stmt = $conn->prepare("
            UPDATE reservas
            SET fecha_reserva = ?, hora_reserva = ?, estado_reserva = 'pendiente'
            WHERE id_reserva = ? AND id_cliente = ?
        ");
        $stmt->bind_param("ssii", $fecha, $hora, $id_reserva, $id_cliente);
        $resultado = $stmt->execute();

        $stmt->close();
        $conn->close();

        return $resultado;
    }

    // Actualizar estado de reserva
    public static function actualizarEstado($id_reserva, $estado) {
        $db = new conexion();
        $conn = $db->conectar();

        $stmt = $conn->prepare("
            UPDATE reservas
            SET estado_reserva = ?
            WHERE id_reserva = ?
        ");
        $stmt->bind_param("si", $estado, $id_reserva);
        $resultado = $stmt->execute();

        $stmt->close();
        $conn->close();

        return $resultado;
    }

    // Obtener reservas filtradas de un proveedor
    public static function obtenerReservasPorProveedorFiltradas($id_proveedor, $estado = '', $fecha_inicio = '', $fecha_fin = '') {
        $conn = (new conexion())->conectar();

        $query = "
            SELECT r.*,
            (SELECT titulo FROM servicios s WHERE s.id_servicio = r.id_servicio) AS nombre_servicio,
            (SELECT nombre FROM usuarios u WHERE u.id_usuario = r.id_cliente) AS nombre_cliente
            FROM reservas r
            WHERE r.id_servicio IN (SELECT id_servicio FROM servicios WHERE id_empresa = ?)
        ";

        $params = [$id_proveedor];
        $types = "i";

        if ($estado) {
            $query .= " AND LOWER(r.estado_reserva) = LOWER(?)";
            $types .= "s";
            $params[] = $estado;
        }

        if ($fecha_inicio) {
            $query .= " AND r.fecha_reserva >= ?";
            $types .= "s";
            $params[] = $fecha_inicio;
        }

        if ($fecha_fin) {
            $query .= " AND r.fecha_reserva <= ?";
            $types .= "s";
            $params[] = $fecha_fin;
        }

        $query .= " ORDER BY r.fecha_reserva ASC, r.hora_reserva ASC";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $stmt->close();
        $conn->close();

        return $resultado;
    }

    // Obtener reserva por ID
    public static function obtenerReservaPorId($id_reserva) {
        $db = new conexion();
        $conn = $db->conectar();

        $stmt = $conn->prepare("
            SELECT r.*, 
                   s.id_empresa AS id_empresa,
                   s.titulo AS nombre_servicio,
                   u.nombre AS nombre_cliente
            FROM reservas r
            JOIN servicios s ON s.id_servicio = r.id_servicio
            JOIN usuarios u ON u.id_usuario = r.id_cliente
            WHERE r.id_reserva = ?
        ");
        $stmt->bind_param("i", $id_reserva);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        $conn->close();

        return $resultado;
    }
}
?>

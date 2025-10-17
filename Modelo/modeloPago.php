<?php
require_once __DIR__ . '/../conexion.php';

class modeloPago {
    private $db;

    public function __construct() {
        $conexion = new conexion();
        $this->db = $conexion->conectar();
    }

    // Inserta un pago en la tabla 'pago'
    public function insertarPago($id_reserva, $monto, $metodo_pago = 'pendiente') {
        $sql = "INSERT INTO pago (id_reserva, monto, fecha_pago, metodo_pago)
                VALUES (?, ?, NOW(), ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ids", $id_reserva, $monto, $metodo_pago);

        $resultado = $stmt->execute();

        if (!$resultado) {
            error_log("Error al insertar pago: " . $stmt->error);
        }

        $stmt->close();
        return $resultado;
    }

    // Obtiene todos los pagos registrados
    public function obtenerPagos() {
        $sql = "SELECT * FROM pago ORDER BY fecha_pago DESC";
        $result = $this->db->query($sql);
        $pagos = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        return $pagos;
    }

    // Obtiene los pagos de una reserva específica
    public function obtenerPagosPorReserva($id_reserva) {
        $stmt = $this->db->prepare("SELECT * FROM pago WHERE id_reserva = ?");
        $stmt->bind_param("i", $id_reserva);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado;
    }

        // Calcula el total de ventas del mes para una empresa
public function obtenerVentasDelMes($empresa_id) {
    $sql = "
        SELECT SUM(p.monto) AS total
        FROM pago p
        INNER JOIN reservas r ON p.id_reserva = r.id_reserva
        INNER JOIN servicios s ON r.id_servicio = s.id_servicio
        WHERE s.id_empresa = ?
          AND p.metodo_pago = 'realizado'
          AND MONTH(p.fecha_pago) = MONTH(CURRENT_DATE())
          AND YEAR(p.fecha_pago) = YEAR(CURRENT_DATE())
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $empresa_id);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $resultado['total'] ?? 0;
}



}
?>

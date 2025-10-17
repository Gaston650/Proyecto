<?php
require_once __DIR__ . '/../conexion.php';

class modeloResena {
    private $conn;

    public function __construct() {
        $conexion = new conexion();
        $this->conn = $conexion->conectar();
    }

    // Guardar reseña
    public function guardar($id_cliente, $id_servicio, $comentario, $calificacion) {
        $stmt = $this->conn->prepare("
            INSERT INTO reseñas (id_cliente, id_servicio, comentario, calificacion, fecha)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("iisi", $id_cliente, $id_servicio, $comentario, $calificacion);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtener reseñas por servicio
    public function obtenerResenasPorServicio($id_servicio) {
        $stmt = $this->conn->prepare("
            SELECT r.calificacion, r.comentario, u.nombre AS cliente_nombre
            FROM reseñas r
            JOIN usuarios u ON r.id_cliente = u.id_usuario
            WHERE r.id_servicio = ?
            ORDER BY r.fecha DESC
        ");
        $stmt->bind_param("i", $id_servicio);
        $stmt->execute();
        $resenas = $stmt->get_result();
        return $resenas->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener reseña específica de un cliente a un servicio
    public function obtenerResenaPorReserva($id_cliente, $id_servicio) {
        $stmt = $this->conn->prepare("
            SELECT calificacion, comentario
            FROM reseñas
            WHERE id_cliente = ? AND id_servicio = ?
        ");
        $stmt->bind_param("ii", $id_cliente, $id_servicio);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function obtenerPromedioPorEmpresa($id_empresa) {
        $sql = "
            SELECT AVG(r.calificacion) AS promedio
            FROM reseñas r
            INNER JOIN servicios s ON r.id_servicio = s.id_servicio
            WHERE s.id_empresa = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado['promedio'];
    }


}
?>
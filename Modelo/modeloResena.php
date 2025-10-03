<?php
require_once __DIR__ . '/../conexion.php';

class modeloResena {
    private $conn;

    public function __construct() {
        $conexion = new conexion();
        $this->conn = $conexion->conectar();
    }

    // Guardar nueva reseña
    public function guardarResena($id_cliente, $id_servicio, $calificacion, $comentario) {
        // Validar que la reserva exista
        $stmt = $this->conn->prepare("SELECT * FROM reservas WHERE id_cliente = ? AND id_servicio = ?");
        $stmt->bind_param("ii", $id_cliente, $id_servicio);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows === 0) {
            return "RESERVA_NO_EXISTE"; 
        }

        // Validar si ya existe reseña
        $stmt = $this->conn->prepare("SELECT * FROM reseñas WHERE id_cliente = ? AND id_servicio = ?");
        $stmt->bind_param("ii", $id_cliente, $id_servicio);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            return "RESEÑA_EXISTE"; 
        }

        // Insertar reseña
        $stmt = $this->conn->prepare("INSERT INTO reseñas (id_cliente, id_servicio, calificacion, comentario, fecha)
                                      VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiis", $id_cliente, $id_servicio, $calificacion, $comentario);
        return $stmt->execute();
    }

    // Obtener reseña por cliente y servicio
    public function obtenerResenaPorReserva($id_cliente, $id_servicio) {
        $stmt = $this->conn->prepare("SELECT * FROM reseñas WHERE id_cliente = ? AND id_servicio = ?");
        $stmt->bind_param("ii", $id_cliente, $id_servicio);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Actualizar reseña
    public function actualizarResena($id_cliente, $id_servicio, $calificacion, $comentario) {
        $stmt = $this->conn->prepare("UPDATE reseñas SET calificacion = ?, comentario = ?, fecha = NOW()
                                      WHERE id_cliente = ? AND id_servicio = ?");
        $stmt->bind_param("isii", $calificacion, $comentario, $id_cliente, $id_servicio);
        return $stmt->execute();
    }
}
?>

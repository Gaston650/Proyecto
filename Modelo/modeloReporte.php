<?php
class modeloReporte {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function crearReporte($id_usuario, $id_servicio, $motivo) {
        $sql = "INSERT INTO reporte (id_usuario_reportante, id_servicio, motivo)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("iis", $id_usuario, $id_servicio, $motivo);

        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }

    public function obtenerPorServicio($id_servicio) {
        $sql = "SELECT * FROM reporte WHERE id_servicio = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_servicio);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $reportes = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $reportes;
    }
}
?>

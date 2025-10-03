<?php
require_once __DIR__ . '/../conexion.php';

class modeloPromocion {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Crear promoción
    public function crearPromocion($id_servicio, $porcentaje, $fecha_inicio, $fecha_fin, $condiciones) {
    $sql = "INSERT INTO promocion (id_servicio, porcentaje_descuento, fecha_inicio, fecha_fin, condiciones) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Error prepare: " . $this->conn->error);
    }

    $stmt->bind_param("issss", $id_servicio, $porcentaje, $fecha_inicio, $fecha_fin, $condiciones);
    $res = $stmt->execute();

        if (!$res) {
            die("Error execute: " . $stmt->error);
        }

        return $res;
    }


    // Editar promoción
    public function editarPromocion($id_promocion, $porcentaje, $fecha_inicio, $fecha_fin, $condiciones) {
        $sql = "UPDATE promocion 
                SET porcentaje_descuento=?, fecha_inicio=?, fecha_fin=?, condiciones=? 
                WHERE id_promocion=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $porcentaje, $fecha_inicio, $fecha_fin, $condiciones, $id_promocion);
        return $stmt->execute();
    }

    // Eliminar promoción
    public function eliminarPromocion($id_promocion) {
        $sql = "DELETE FROM promocion WHERE id_promocion=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_promocion);
        return $stmt->execute();
    }

    // Listar promociones por empresa
 public function listarPorEmpresa($id_empresa) {
    $sql = "SELECT p.*, s.titulo 
            FROM promocion p
            INNER JOIN servicios s ON p.id_servicio = s.id_servicio
            WHERE s.id_empresa = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id_empresa);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


 public function obtenerPromocionPorServicio($id_servicio) {
    $hoy = date("Y-m-d");
    $sql = "SELECT * FROM promocion 
            WHERE id_servicio = ? 
              AND DATE(fecha_inicio) <= ? 
              AND DATE(fecha_fin) >= ?
            ORDER BY fecha_inicio DESC
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) die("Error prepare: ".$this->conn->error);
    $stmt->bind_param("iss", $id_servicio, $hoy, $hoy);
    $stmt->execute();
    $result = $stmt->get_result();
    $promo = $result->fetch_assoc();
    return $promo ?: null;
}



}
?>

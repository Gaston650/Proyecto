<?php
require_once __DIR__ . '/../conexion.php';

class modeloFavoritos {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function agregarFavorito($id_cliente, $id_servicio) {
        $sql = "INSERT INTO favoritos (id_cliente, id_servicio) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt) die("Error prepare agregarFavorito: " . $this->conn->error);
        $stmt->bind_param("ii", $id_cliente, $id_servicio);
        return $stmt->execute();
    }

    public function quitarFavorito($id_cliente, $id_servicio) {
        $sql = "DELETE FROM favoritos WHERE id_cliente = ? AND id_servicio = ?";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt) die("Error prepare quitarFavorito: " . $this->conn->error);
        $stmt->bind_param("ii", $id_cliente, $id_servicio);
        return $stmt->execute();
    }

    public function esFavorito($id_cliente, $id_servicio) {
        $sql = "SELECT * FROM favoritos WHERE id_cliente = ? AND id_servicio = ?";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt) die("Error prepare esFavorito: " . $this->conn->error);
        $stmt->bind_param("ii", $id_cliente, $id_servicio);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function obtenerFavoritos($id_cliente) {
        $sql = "SELECT s.* 
                FROM servicios s
                INNER JOIN favoritos f ON s.id_servicio = f.id_servicio
                WHERE f.id_cliente = ?";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt) die("Error prepare obtenerFavoritos: " . $this->conn->error);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $result = $stmt->get_result();
        $favoritos = [];
        while ($row = $result->fetch_assoc()) {
            $favoritos[] = $row;
        }
        return $favoritos;
    }
}
?>

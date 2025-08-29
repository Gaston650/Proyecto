<?php
require_once __DIR__ . '/../conexion.php';

class modeloServicio {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener todos los servicios de una empresa
    public function obtenerServiciosPorEmpresa($id_empresa) {
        $sql = "SELECT id_servicio, titulo, descripcion, ubicacion, precio, disponibilidad, fecha_publicacion, estado 
                FROM servicios 
                WHERE id_empresa = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $result = $stmt->get_result();

        $servicios = [];
        while ($row = $result->fetch_assoc()) {
            $servicios[] = $row;
        }
        return $servicios;
    }

    // Insertar un nuevo servicio
    public function publicarServicio($id_empresa, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado) {
        // Asegurarse de que el estado no exceda el tamaño de la columna
        $estado = substr($estado, 0, 20);

        $sql = "INSERT INTO servicios (id_empresa, titulo, descripcion, ubicacion, precio, disponibilidad, estado, fecha_publicacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssdss", $id_empresa, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado);

        if(!$stmt){
            die("Error en prepare: " . $this->conn->error);
        }

        $ejecucion = $stmt->execute();
        if(!$ejecucion){
            die("Error en execute: " . $stmt->error);
        }

        return $ejecucion;
    }

    // Eliminar un servicio
    public function eliminarServicio($id_servicio, $id_empresa) {
        $sql = "DELETE FROM servicios WHERE id_servicio = ? AND id_empresa = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_servicio, $id_empresa);
        return $stmt->execute();
    }

     // Editar un servicio
    public function editarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado) {
    $sql = "UPDATE servicios 
            SET titulo = ?, descripcion = ?, ubicacion = ?, precio = ?, disponibilidad = ?, estado = ?
            WHERE id_servicio = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sssdssi", $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado, $id);
    return $stmt->execute();
}

}
?>

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
        if(!$stmt) die("Error en prepare: " . $this->conn->error);

        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $result = $stmt->get_result();

        $servicios = [];
        while ($row = $result->fetch_assoc()) {
            $servicios[] = $row;
        }
        return $servicios;
    }

    // Obtener todos los servicios activos (para clientes)
    public function obtenerServiciosActivos() {
    $sql = "SELECT id_servicio, titulo, descripcion, ubicacion, precio, disponibilidad, fecha_publicacion, estado, id_empresa
            FROM servicios
            WHERE estado = 'disponible'";
    $result = $this->conn->query($sql);

    $servicios = [];
    while ($row = $result->fetch_assoc()) {
        $servicios[] = $row;
    }
    return $servicios;
}


    // Publicar un nuevo servicio
    public function publicarServicio($id_empresa, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado) {
        $sql = "INSERT INTO servicios (id_empresa, titulo, descripcion, ubicacion, precio, disponibilidad, estado, fecha_publicacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt) die("Error en prepare: " . $this->conn->error);

        $stmt->bind_param("isssdss", $id_empresa, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado);
        return $stmt->execute();
    }

    // Eliminar un servicio
    public function eliminarServicio($id_servicio, $id_empresa) {
        $sql = "DELETE FROM servicios WHERE id_servicio = ? AND id_empresa = ?";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt) die("Error en prepare: " . $this->conn->error);

        $stmt->bind_param("ii", $id_servicio, $id_empresa);
        return $stmt->execute();
    }

    // Editar un servicio
    public function editarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado) {
        $sql = "UPDATE servicios 
                SET titulo = ?, descripcion = ?, ubicacion = ?, precio = ?, disponibilidad = ?, estado = ?
                WHERE id_servicio = ?";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt) die("Error en prepare: " . $this->conn->error);

        $stmt->bind_param("sssdssi", $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado, $id);
        return $stmt->execute();
    }

    // Obtener servicios activos con filtros (para clientes)
    public function obtenerServiciosFiltrados($buscar = '', $zona = '', $categoria = '') {
        $sql = "SELECT id_servicio, titulo, descripcion, ubicacion, precio, disponibilidad, fecha_publicacion, estado, id_empresa
            FROM servicios
            WHERE estado = 'disponible'";
        $params = [];
        $types = "";

        if (!empty($buscar)) {
            $sql .= " AND (titulo LIKE ? OR descripcion LIKE ?)";
            $busqueda = "%".$buscar."%";
            $params[] = $busqueda;
            $params[] = $busqueda;
            $types .= "ss";
        }

        if (!empty($zona)) {
            $sql .= " AND ubicacion = ?";
            $params[] = $zona;
            $types .= "s";
        }

        if (!empty($categoria)) {
            $sql .= " AND categoria = ?";
            $params[] = $categoria;
            $types .= "s";
        }

        $stmt = $this->conn->prepare($sql);
        if(!$stmt) die("Error en prepare: " . $this->conn->error);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $servicios = [];
        while ($row = $result->fetch_assoc()) {
            $servicios[] = $row;
        }
        return $servicios;
    }

}
?>

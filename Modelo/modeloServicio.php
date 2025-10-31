<?php
require_once __DIR__ . '/../conexion.php';

class modeloServicio {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener todos los servicios de una empresa (incluye imagen)
    public function obtenerServiciosPorEmpresa($id_empresa) {
        $sql = "SELECT id_servicio, titulo, descripcion, categoria, ubicacion, precio, disponibilidad, fecha_publicacion, estado, id_empresa, imagen
                FROM servicios
                WHERE id_empresa = ?";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt) die("Error en prepare: " . $this->conn->error);

        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $result = $stmt->get_result();

        $servicios = [];
        while ($row = $result->fetch_assoc()) {
            if (empty($row['imagen'])) {
                $row['imagen'] = '../../img/servicio-vacio.png';
            }
            $servicios[] = $row;
        }
        return $servicios;
    }

    // Obtener todos los servicios activos (para clientes) con imagen
    public function obtenerServiciosActivos() {
        $sql = "SELECT id_servicio, titulo, descripcion, categoria, ubicacion, precio, disponibilidad, fecha_publicacion, estado, id_empresa, imagen
                FROM servicios
                WHERE LOWER(estado) = 'disponible'";
        $result = $this->conn->query($sql);
        if(!$result) die("Error en query: " . $this->conn->error);

        $servicios = [];
        while ($row = $result->fetch_assoc()) {
            if (empty($row['imagen'])) {
                $row['imagen'] = '../../img/servicio-vacio.png';
            }
            $servicios[] = $row;
        }
        return $servicios;
    }

    // Publicar un nuevo servicio
    public function publicarServicio($id_empresa, $titulo, $descripcion, $categoria, $ubicacion, $precio, $disponibilidad, $estado = 'Disponible', $imagen = null) {
        $sql = "INSERT INTO servicios (id_empresa, titulo, descripcion, categoria, ubicacion, precio, disponibilidad, estado, imagen, fecha_publicacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);
        if(!$stmt) die("Error en prepare: " . $this->conn->error);

        $stmt->bind_param(
            "issssdsss",
            $id_empresa,
            $titulo,
            $descripcion,
            $categoria,
            $ubicacion,
            $precio,
            $disponibilidad,
            $estado,
            $imagen
        );

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

    // Editar un servicio (con opción de cambiar imagen)
    public function editarServicio($id, $titulo, $descripcion, $categoria, $ubicacion, $precio, $disponibilidad, $estado = 'Disponible', $imagen = null) {
        if ($imagen) {
            $sql = "UPDATE servicios 
                    SET titulo = ?, descripcion = ?, categoria = ?, ubicacion = ?, precio = ?, disponibilidad = ?, estado = ?, imagen = ?
                    WHERE id_servicio = ?";
            $stmt = $this->conn->prepare($sql);
            if(!$stmt) die("Error en prepare: " . $this->conn->error);
            $stmt->bind_param("ssssdsssi", $titulo, $descripcion, $categoria, $ubicacion, $precio, $disponibilidad, $estado, $imagen, $id);
        } else {
            $sql = "UPDATE servicios 
                    SET titulo = ?, descripcion = ?, categoria = ?, ubicacion = ?, precio = ?, disponibilidad = ?, estado = ?
                    WHERE id_servicio = ?";
            $stmt = $this->conn->prepare($sql);
            if(!$stmt) die("Error en prepare: " . $this->conn->error);
            $stmt->bind_param("ssssdssi", $titulo, $descripcion, $categoria, $ubicacion, $precio, $disponibilidad, $estado, $id);
        }
        return $stmt->execute();
    }

   public function obtenerServiciosFiltrados($buscar = '', $zona = '', $categoria = '') {
    $sql = "SELECT s.id_servicio, s.titulo, s.descripcion, s.categoria, s.ubicacion, s.precio, s.disponibilidad,
                   s.fecha_publicacion, s.estado, s.id_empresa, s.imagen, e.nombre_empresa
            FROM servicios s
            LEFT JOIN empresas_proveedor e ON s.id_empresa = e.id_empresa
            WHERE s.estado = 'Disponible'";

    $params = [];
    $types = "";

    if (!empty($buscar)) {
        $sql .= " AND (LOWER(s.titulo) LIKE ? OR LOWER(s.descripcion) LIKE ?)";
        $busqueda = "%".strtolower($buscar)."%";
        $params[] = $busqueda;
        $params[] = $busqueda;
        $types .= "ss";
    }

    if (!empty($zona)) {
        $sql .= " AND LOWER(s.ubicacion) = ?";
        $params[] = strtolower($zona);
        $types .= "s";
    }

    if (!empty($categoria)) {
        $sql .= " AND LOWER(s.categoria) = ?";
        $params[] = strtolower($categoria);
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
        if (empty($row['imagen'])) {
            $row['imagen'] = '../../img/servicio-vacio.png';
        }
        $servicios[] = $row;
    }
    return $servicios;
}


    // Obtener todas las categorías
    public function obtenerCategorias() {
        $sql = "SELECT * FROM categorias ORDER BY nombre_categoria ASC";
        $result = $this->conn->query($sql);
        if(!$result) die("Error en query: " . $this->conn->error);

        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        return $categorias;
    }

    // Obtener un servicio por su ID
public function obtenerServicioPorId($id_servicio) {
    $sql = "SELECT * FROM servicios WHERE id_servicio = ?";
    $stmt = $this->conn->prepare($sql);
    if(!$stmt) die("Error en prepare: " . $this->conn->error);

    $stmt->bind_param("i", $id_servicio);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

    // Actualizar disponibilidad de un servicio
    public function actualizarDisponibilidad($id_servicio, $disponibilidad) {
        $sql = "UPDATE servicios SET disponibilidad = ? WHERE id_servicio = ?";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt) die("Error en prepare: " . $this->conn->error);

        $stmt->bind_param("si", $disponibilidad, $id_servicio);
        return $stmt->execute();
    }

}
?>
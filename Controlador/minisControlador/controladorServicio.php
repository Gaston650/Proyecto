<?php
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../Modelo/modeloServicio.php';

class controladorServicio {
    private $modelo;

    public function __construct() {
        $db = new conexion();
        $conn = $db->conectar();
        $this->modelo = new modeloServicio($conn);
    }

    // Listar servicios de una empresa específica
    public function listarServiciosEmpresa($id_empresa) {
        return $this->modelo->obtenerServiciosPorEmpresa($id_empresa);
    }

    // Listar todos los servicios activos (para clientes)
    public function listarServiciosActivos() {
        return $this->modelo->obtenerServiciosActivos();
    }

    // Publicar un nuevo servicio
    public function publicarServicio($id_empresa, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado) {
        return $this->modelo->publicarServicio($id_empresa, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado);
    }

    // Eliminar un servicio
    public function borrarServicio($id_servicio, $id_empresa) {
        return $this->modelo->eliminarServicio($id_servicio, $id_empresa);
    }

    // Editar un servicio
    public function editarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado) {
        return $this->modelo->editarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado);
    }

    // Listar servicios activos con filtros
    public function listarServiciosFiltrados($buscar = '', $zona = '', $categoria = '') {
        return $this->modelo->obtenerServiciosFiltrados($buscar, $zona, $categoria);
    }

}
?>

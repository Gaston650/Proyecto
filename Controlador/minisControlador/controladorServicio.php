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

    public function listarServiciosEmpresa($id_empresa) {
        return $this->modelo->obtenerServiciosPorEmpresa($id_empresa);
    }

    public function publicarServicio($id_empresa, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado) {
        return $this->modelo->publicarServicio($id_empresa, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado);
    }

    public function borrarServicio($id_servicio, $id_empresa) {
        return $this->modelo->eliminarServicio($id_servicio, $id_empresa);
    }

    public function editarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado) {
        return $this->modelo->editarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado);
    }
}
?>


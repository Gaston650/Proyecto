<?php
require_once __DIR__ . '/../../Modelo/modeloServicio.php';

class controladorServicio {
    private $modelo;

    // Permitir inyectar un modelo (para test) o crear uno nuevo (producción)
    public function __construct($modelo = null) {
        if ($modelo) {
            $this->modelo = $modelo;
        } else {
            require_once __DIR__ . '/../../conexion.php';
            $db = new conexion();
            $conn = $db->conectar();
            $this->modelo = new modeloServicio($conn);
        }
    }

    public function listarServiciosEmpresa($id_empresa) {
        return $this->modelo->obtenerServiciosPorEmpresa($id_empresa);
    }

    public function listarServiciosActivos() {
        return $this->modelo->obtenerServiciosActivos();
    }

    public function publicarServicio(
        $id_empresa, $titulo, $descripcion, $categoria,
        $ubicacion, $precio, $disponibilidad,
        $estado = 'activo', $imagen = null
    ) {
        return $this->modelo->publicarServicio(
            $id_empresa, $titulo, $descripcion, $categoria,
            $ubicacion, $precio, $disponibilidad, $estado, $imagen
        );
    }

    public function borrarServicio($id_servicio, $id_empresa) {
        return $this->modelo->eliminarServicio($id_servicio, $id_empresa);
    }

    public function editarServicio(
        $id, $titulo, $descripcion, $categoria,
        $ubicacion, $precio, $disponibilidad,
        $estado = 'activo', $imagen = null
    ) {
        return $this->modelo->editarServicio(
            $id, $titulo, $descripcion, $categoria,
            $ubicacion, $precio, $disponibilidad, $estado, $imagen
        );
    }

    public function listarServiciosFiltrados($buscar = '', $zona = '', $categoria = '') {
        return $this->modelo->obtenerServiciosFiltrados($buscar, $zona, $categoria);
    }

    public function listarCategorias() {
        return $this->modelo->obtenerCategorias();
    }

    public function obtenerServicio($id_servicio) {
        return $this->modelo->obtenerServicioPorId($id_servicio);
    }

    public function actualizarDisponibilidad($id_servicio, $disponibilidad) {
        return $this->modelo->actualizarDisponibilidad($id_servicio, $disponibilidad);
    }
}
?>

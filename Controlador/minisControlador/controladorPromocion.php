<?php
require_once __DIR__ . '/../../Modelo/modeloPromocion.php';

class controladorPromocion {
    private $modelo;

    public function __construct($conn) {
        $this->modelo = new modeloPromocion($conn);
    }

    public function guardarPromocion($id_servicio, $porcentaje, $fecha_inicio, $fecha_fin, $condiciones) {
        return $this->modelo->crearPromocion($id_servicio, $porcentaje, $fecha_inicio, $fecha_fin, $condiciones);
    }

    public function actualizarPromocion($id_promocion, $porcentaje, $fecha_inicio, $fecha_fin, $condiciones) {
        return $this->modelo->editarPromocion($id_promocion, $porcentaje, $fecha_inicio, $fecha_fin, $condiciones);
    }

    public function borrarPromocion($id_promocion) {
        return $this->modelo->eliminarPromocion($id_promocion);
    }

    public function obtenerPromociones($id_empresa) {
        return $this->modelo->listarPromociones($id_empresa);
    }
}
?>

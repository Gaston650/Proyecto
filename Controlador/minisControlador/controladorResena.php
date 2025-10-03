<?php
require_once __DIR__ . '/../../Modelo/modeloResena.php';

class controladorResena {
    private $modelo;

    public function __construct() {
        $this->modelo = new modeloResena();
    }

    // Guardar o actualizar reseña
    public function guardar($id_cliente, $id_servicio, $comentario, $calificacion) {
        $existe = $this->modelo->obtenerResenaPorReserva($id_cliente, $id_servicio);

        if ($existe) {
            return $this->modelo->actualizarResena($id_cliente, $id_servicio, $calificacion, $comentario);
        } else {
            return $this->modelo->guardarResena($id_cliente, $id_servicio, $calificacion, $comentario);
        }
    }
}
?>



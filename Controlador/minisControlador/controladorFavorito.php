<?php
require_once __DIR__ . '/../../Modelo/modeloFavoritos.php';
require_once __DIR__ . '/../../conexion.php';

class controladorFavorito {
    private $modelo;

    public function __construct() {
        $db = new conexion();
        $conn = $db->conectar();
        $this->modelo = new modeloFavoritos($conn);
    }

    public function agregarFavorito($id_cliente, $id_servicio) {
        return $this->modelo->agregarFavorito($id_cliente, $id_servicio);
    }

    public function quitarFavorito($id_cliente, $id_servicio) {
        return $this->modelo->quitarFavorito($id_cliente, $id_servicio);
    }

    public function esFavorito($id_cliente, $id_servicio) {
        return $this->modelo->esFavorito($id_cliente, $id_servicio);
    }

    public function obtenerFavoritos($id_cliente) {
        return $this->modelo->obtenerFavoritos($id_cliente);
    }
}
?>

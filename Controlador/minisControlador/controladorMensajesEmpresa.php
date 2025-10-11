<?php
require_once __DIR__ . '/../../Modelo/modeloMensaje.php';

class mensajeControladorEmpresa {
    private $modelo;

    public function __construct() {
        $this->modelo = new modeloMensaje();
    }

    public function obtenerConversacion($id_cliente, $id_empresa, $id_reserva) {
        return $this->modelo->obtenerConversacion($id_cliente, $id_empresa, $id_reserva);
    }

    public function marcarMensajesLeidos($id_empresa, $id_cliente, $id_reserva) {
        return $this->modelo->marcarMensajesLeidos($id_empresa, $id_cliente, $id_reserva);
    }

    public function obtenerMensajesParaEmpresa($id_empresa) {
        return $this->modelo->obtenerMensajesParaEmpresa($id_empresa);
    }

    public function insertarMensaje($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva, $contenido) {
        return $this->modelo->insertarMensaje($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva, $contenido);
    }

    public function obtenerConexionParaDebug() {
        return $this->modelo->getConexion();
    }
}
?>










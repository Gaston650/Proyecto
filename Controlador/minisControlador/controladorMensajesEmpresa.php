<?php
require_once __DIR__ . '/../../Modelo/modeloMensaje.php';

class mensajeControladorEmpresa {
    private $modelo;

    public function __construct() {
        $this->modelo = new modeloMensaje();
    }

    public function obtenerMensajesParaEmpresa($id_empresa) {
        return $this->modelo->obtenerMensajesParaEmpresa($id_empresa);
    }

    public function obtenerConversacionPorCliente($id_cliente, $id_empresa, $id_reserva = null) {
        return $this->modelo->obtenerConversacionPorClienteReserva($id_cliente, $id_empresa, $id_reserva);
    }

    // Ahora requiere los tipos de emisor y receptor
    public function insertarMensaje($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva, $contenido) {
        return $this->modelo->insertarMensaje($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva, $contenido);
    }

    public function marcarMensajesLeidosEmpresa($id_empresa, $id_cliente, $id_reserva) {
        return $this->modelo->marcarMensajesLeidos($id_empresa, $id_cliente, $id_reserva);
    }

    public function contarNoLeidos($id_empresa) {
        return $this->modelo->contarNoLeidos($id_empresa);
    }
}
?>
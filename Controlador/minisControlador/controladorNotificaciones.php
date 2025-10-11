<?php
require_once __DIR__ . '/../../Modelo/modeloNotificaciones.php';

class notificacionControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new modeloNotificaciones();
    }

    // Insertar notificación
    public function insertarNotificacion($id_empresa, $mensaje, $tipo = 'sistema') {
        return $this->modelo->insertarNotificacion($id_empresa, $mensaje, $tipo);
    }

    // Obtener notificaciones no leídas
    public function obtenerNoLeidas($id_empresa) {
        return $this->modelo->obtenerNoLeidas($id_empresa);
    }

    // Obtener todas las notificaciones
    public function obtenerTodas($id_empresa) {
        return $this->modelo->obtenerTodas($id_empresa);
    }

    // Marcar todas las notificaciones como leídas
    public function marcarLeidas($id_empresa) {
        return $this->modelo->marcarLeidas($id_empresa);
    }

    // Contar notificaciones no leídas
    public function contarNoLeidas($id_empresa) {
        return $this->modelo->contarNoLeidas($id_empresa);
    }

}
?>
<?php
require_once __DIR__ . '/../../Modelo/modeloMensaje.php';

class mensajeControlador {
    private $modelo;

    // Constructor permite inyectar un mock (para tests)
    public function __construct($modelo = null) {
        $this->modelo = $modelo ?? new modeloMensaje();
    }

    public function manejarConversacion($id_cliente, $tipo_emisor, $id_empresa, $tipo_receptor, $id_reserva) {
        $resultado = ['mensajes' => null, 'exito' => null, 'error' => null];

        if (!$id_empresa || !$id_reserva) return $resultado;

        // Guardar mensaje si se envía POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenido = trim($_POST['contenido'] ?? '');
            if (!empty($contenido)) {
                $this->enviarMensaje($id_cliente, $tipo_emisor, $id_empresa, $tipo_receptor, $id_reserva, $contenido);
                $resultado['exito'] = "✅ Mensaje enviado correctamente.";
            } else {
                $resultado['error'] = "⚠️ El mensaje no puede estar vacío.";
            }
        }

        $resultado['mensajes'] = $this->modelo->obtenerConversacion($id_cliente, $id_empresa, $id_reserva);
        return $resultado;
    }

    public function obtenerMensajesCliente($id_cliente) {
        return $this->modelo->obtenerMensajesParaCliente($id_cliente);
    }

    public function obtenerMensajesEmpresa($id_empresa) {
        return $this->modelo->obtenerMensajesParaEmpresa($id_empresa);
    }

    public function enviarMensaje($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva, $contenido) {
        return $this->modelo->insertarMensaje($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva, $contenido);
    }

    public function marcarMensajesLeidos($id_empresa, $id_cliente, $id_reserva) {
        return $this->modelo->marcarMensajesLeidos($id_empresa, $id_cliente, $id_reserva);
    }
}
?>

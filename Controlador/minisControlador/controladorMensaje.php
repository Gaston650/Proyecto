<?php
require_once __DIR__ . '/../../Modelo/modeloMensaje.php';

class mensajeControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new modeloMensaje();
    }

    public function manejarConversacion($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva) {
        $resultado = ['mensajes'=>null,'exito'=>null,'error'=>null];

        if (!$id_receptor || !$id_reserva) return $resultado;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenido = trim($_POST['contenido'] ?? '');
            if (!empty($contenido)) {
                // Pasa los nuevos parámetros al modelo
                $this->modelo->insertarMensaje($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva, $contenido);
                $resultado['exito'] = "✅ Mensaje enviado correctamente.";
            } else {
                $resultado['error'] = "⚠️ El mensaje no puede estar vacío.";
            }
        }

        // Al consultar la conversación, filtra por ambos tipos
        $resultado['mensajes'] = $this->modelo->obtenerConversacionPorClienteReserva($id_emisor, $id_receptor, $id_reserva);
        return $resultado;
    }

    public function obtenerMensajesEmpresa($id_empresa) {
        return $this->modelo->obtenerMensajesParaEmpresa($id_empresa);
    }

    public function marcarLeido($id_mensaje) {
        return $this->modelo->marcarComoLeido($id_mensaje);
    }
}
?>
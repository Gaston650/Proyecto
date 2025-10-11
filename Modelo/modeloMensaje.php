<?php
require_once __DIR__ . '/../conexion.php';

class modeloMensaje {
    private $conn;

    public function __construct() {
        $this->conn = (new conexion())->conectar();
    }

    public function insertarMensaje($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva, $contenido) {
        $sql = "INSERT INTO mensajes (id_emisor, tipo_emisor, id_receptor, tipo_receptor, id_reserva, contenido) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isisss", $id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva, $contenido);
        $stmt->execute();
        $stmt->close();
    }

    public function obtenerConversacion($id_cliente, $id_empresa, $id_reserva) {
        $sql = "
            SELECT m.*,
                   COALESCE(u.nombre, e.nombre_empresa, 'Desconocido') AS nombre_emisor
            FROM mensajes m
            LEFT JOIN usuarios u ON m.id_emisor = u.id_usuario
            LEFT JOIN empresas_proveedor e ON m.id_emisor = e.id_empresa
            WHERE m.id_reserva = ?
              AND (
                    (m.id_emisor = ? AND m.id_receptor = ?)
                 OR (m.id_emisor = ? AND m.id_receptor = ?)
              )
            ORDER BY m.fecha_envio ASC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiiii", $id_reserva, $id_cliente, $id_empresa, $id_empresa, $id_cliente);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function marcarMensajesLeidos($id_empresa, $id_cliente, $id_reserva) {
        $stmt = $this->conn->prepare("
            UPDATE mensajes
            SET leido = 1
            WHERE id_receptor = ? AND id_emisor = ? AND id_reserva = ?
        ");
        $stmt->bind_param("iii", $id_empresa, $id_cliente, $id_reserva);
        return $stmt->execute();
    }

    public function obtenerMensajesParaCliente($id_cliente) {
        $sql = "
            SELECT m.*,
                   u.nombre AS nombre_cliente,
                   e.nombre_empresa AS nombre_empresa
            FROM mensajes m
            LEFT JOIN usuarios u 
                ON m.tipo_emisor = 'usuario' AND m.id_emisor = u.id_usuario
            LEFT JOIN empresas_proveedor e 
                ON m.tipo_emisor = 'empresa' AND m.id_emisor = e.id_empresa
            WHERE m.id_receptor = ? AND m.tipo_receptor = 'usuario'
            ORDER BY m.fecha_envio DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

      public function obtenerMensajesParaEmpresa($id_empresa) {
        $stmt = $this->conn->prepare("
            SELECT m.*, u.nombre AS nombre_cliente
            FROM mensajes m
            LEFT JOIN usuarios u 
                ON m.id_emisor = u.id_usuario AND m.tipo_emisor = 'usuario'
            WHERE m.id_receptor = ? AND m.tipo_receptor = 'empresa'
            ORDER BY m.fecha_envio DESC
        ");
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // DEBUG: ver qué llega
        //var_dump($result); exit;

        return $result;
    }



    public function contarNoLeidosCliente($id_cliente) {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) AS total
            FROM mensajes
            WHERE id_receptor = ? AND tipo_receptor = 'usuario' AND leido = 0
        ");
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $res['total'] ?? 0;
    }

    public function getConexion() {
        return $this->conn;
    }
}
?>

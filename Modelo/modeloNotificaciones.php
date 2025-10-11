<?php
require_once __DIR__ . '/../conexion.php';

class modeloNotificaciones {

    // Insertar notificación
    public function insertarNotificacion($id_empresa, $mensaje, $tipo = 'sistema') {
        $db = new conexion();
        $conn = $db->conectar();

        $stmt = $conn->prepare("
            INSERT INTO notificaciones (id_empresa, mensaje, tipo)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iss", $id_empresa, $mensaje, $tipo);

        $resultado = $stmt->execute();
        if (!$resultado) {
            error_log("Error al insertar notificación: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();

        return $resultado;
    }

    // Obtener notificaciones no leídas de un proveedor
    public function obtenerNoLeidas($id_empresa) {
        $db = new conexion();
        $conn = $db->conectar();

        $stmt = $conn->prepare("
            SELECT * FROM notificaciones
            WHERE id_empresa = ? AND estado = 'no leída'
            ORDER BY fecha_envio DESC
        ");
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $conn->close();

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener todas las notificaciones (leídas y no leídas)
    public function obtenerTodas($id_empresa) {
        $db = new conexion();
        $conn = $db->conectar();

        $stmt = $conn->prepare("
            SELECT * FROM notificaciones
            WHERE id_empresa = ?
            ORDER BY fecha_envio DESC
        ");
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $conn->close();

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Marcar todas las notificaciones como leídas
    public function marcarLeidas($id_empresa) {
        $db = new conexion();
        $conn = $db->conectar();

        $stmt = $conn->prepare("
            UPDATE notificaciones
            SET estado = 'leída'
            WHERE id_empresa = ?
        ");
        $stmt->bind_param("i", $id_empresa);
        $resultado = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $resultado;
    }

    // Contar notificaciones no leídas
    public function contarNoLeidas($id_empresa) {
        $db = new conexion();
        $conn = $db->conectar();

        $stmt = $conn->prepare("
            SELECT COUNT(*) as total
            FROM notificaciones
            WHERE id_empresa = ? AND estado = 'no leída'
        ");
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();

        return $resultado['total'] ?? 0;
    }

}
?>

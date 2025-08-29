<?php
require_once __DIR__ . '/../conexion.php';

class perfilModelo {
    private $conexion;

    public function __construct() {
        $db = new conexion();
        $this->conexion = $db->conectar();

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    // Obtener perfil por id_usuario
    public function obtenerPerfil($id_usuario) {
        $sql = "SELECT * FROM perfiles WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $perfil = $resultado->fetch_assoc();
        $stmt->close();
        return $perfil;
    }

    // Guardar o actualizar perfil
    public function guardarPerfil($id_usuario, $direccion, $ciudad, $biografia, $foto) {
        $perfil = $this->obtenerPerfil($id_usuario);

        if ($perfil) {
            $sql = "UPDATE perfiles 
                    SET direccion = ?, ciudad = ?, biografia = ?, foto_perfil = ? 
                    WHERE id_usuario = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("ssssi", $direccion, $ciudad, $biografia, $foto, $id_usuario);
        } else {
            $sql = "INSERT INTO perfiles (id_usuario, direccion, ciudad, biografia, foto_perfil) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("issss", $id_usuario, $direccion, $ciudad, $biografia, $foto);
        }

        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Obtener método de pago
    public function obtenerMetodoPago($id_usuario) {
        $sql = "SELECT tipo FROM metodo_pago_cliente WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $row = $resultado->fetch_assoc();
        $stmt->close();
        return $row ? $row['tipo'] : null;
    }

    // Guardar o actualizar método de pago
    public function guardarMetodoPago($id_usuario, $tipo) {
        $existe = $this->obtenerMetodoPago($id_usuario);

        if ($existe) {
            $sql = "UPDATE metodo_pago_cliente SET tipo = ? WHERE id_usuario = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("si", $tipo, $id_usuario);
        } else {
            $sql = "INSERT INTO metodo_pago_cliente (id_usuario, tipo) VALUES (?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("is", $id_usuario, $tipo);
        }

        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>
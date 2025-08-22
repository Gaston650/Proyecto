<?php
require_once __DIR__ . '/../conexion.php';

class empresaModelo {
    private $conexion;

    public function __construct() {
        $db = new conexion();
        $this->conexion = $db->conectar();

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    // Insertar empresa (sin el teléfono)
    public function insertarEmpresa($nombre, $email, $logo, $password, $rut) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // encriptamos la contraseña

        $sql = "INSERT INTO empresas_proveedor (nombre_empresa, email_empresa, logo, contraseña, rut) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            die("Error al preparar consulta: " . $this->conexion->error);
        }
        $stmt->bind_param("sssss", $nombre, $email, $logo, $hashedPassword, $rut);
        if ($stmt->execute()) {
            $id_empresa = $stmt->insert_id;
            $stmt->close();
            return $id_empresa;
        }
        $stmt->close();
        return false;
    }

    // Insertar teléfono de empresa
    public function insertarTelefono($id_empresa, $telefono) {
        $sql = "INSERT INTO telefono_empresa (id_empresa, telefono) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            die("Error al preparar consulta insertarTelefono: " . $this->conexion->error);
        }
        $stmt->bind_param("is", $id_empresa, $telefono);
        $stmt->execute();
        $stmt->close();
    }

    // Obtener empresa por email
    public function obtenerEmpresa($email) {
        $sql = "SELECT id_empresa, nombre_empresa, contraseña, logo FROM empresas_proveedor WHERE email_empresa = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            die("Error al preparar consulta: " . $this->conexion->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $empresa = $result->fetch_assoc();
        $stmt->close();
        return $empresa ?: false;
    }
}
?>
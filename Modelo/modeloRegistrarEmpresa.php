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

    // Insertar empresa
    public function insertarEmpresa($nombre, $email, $zona, $logo, $password, $rut) {
        $stmt = $this->conexion->prepare("
            INSERT INTO empresas_proveedor
            (nombre_empresa, email_empresa, zona_cobertura, logo, contraseña, rut)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssssss", $nombre, $email, $zona, $logo, $hashedPassword, $rut);
        return $stmt->execute();
    }

    // Insertar teléfono
    public function insertarTelefono($id_empresa, $telefono) {
        $stmt = $this->conexion->prepare("INSERT INTO telefono_empresa (id_empresa, telefono) VALUES (?, ?)");
        $stmt->bind_param("is", $id_empresa, $telefono);
        $stmt->execute();
        $stmt->close();
    }

    // Obtener empresa por email
    public function obtenerEmpresa($email) {
        $stmt = $this->conexion->prepare("
            SELECT id_empresa, nombre_empresa, contraseña, logo
            FROM empresas_proveedor
            WHERE email_empresa = ?
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $empresa = $result->fetch_assoc();
        $stmt->close();
        return $empresa ?: false;
    }
}
?>

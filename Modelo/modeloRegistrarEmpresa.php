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
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // HASH SOLO AQUÍ
        $stmt->bind_param("ssssss", $nombre, $email, $zona, $logo, $hashedPassword, $rut);
        return $stmt->execute();
    }

    public function insertarTelefono($id_empresa, $telefono) {
        $stmt = $this->conexion->prepare("INSERT INTO telefono_empresa (id_empresa, telefono) VALUES (?, ?)");
        $stmt->bind_param("is", $id_empresa, $telefono);
        $stmt->execute();
        $stmt->close();
    }

    public function obtenerEmpresa($email) {
        $email = strtolower(trim($email));
        $stmt = $this->conexion->prepare("
            SELECT id_empresa, nombre_empresa, contraseña, logo, estado, remember_token, email_empresa
            FROM empresas_proveedor
            WHERE LOWER(email_empresa) = ?
            LIMIT 1
        ");
        if (!$stmt) die("Error al preparar consulta obtenerEmpresa: " . $this->conexion->error);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $empresa = $res->fetch_assoc();
        $stmt->close();
        return $empresa ?: false;
    }

    public function guardarToken($id_empresa, $token = null) {
        if ($token === null) {
            $sql = "UPDATE empresas_proveedor SET remember_token = NULL WHERE id_empresa = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("i", $id_empresa);
        } else {
            $sql = "UPDATE empresas_proveedor SET remember_token = ? WHERE id_empresa = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("si", $token, $id_empresa);
        }
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function obtenerEmpresaPorToken(string $token) {
        $sql = "SELECT id_empresa, nombre_empresa, email_empresa, remember_token, estado 
                FROM empresas_proveedor 
                WHERE remember_token = ? 
                LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $res = $stmt->get_result();
        $empresa = $res->fetch_assoc();
        $stmt->close();
        return $empresa ?: null;
    }

    public function actualizarEmpresaRememberToken(int $id_empresa, ?string $token) {
        return $this->guardarToken($id_empresa, $token);
    }
}
?>
<?php
require_once __DIR__ . '/../conexion.php';

class usuario2Modelo {
    private $conn;

    public function __construct() {
        $db = new conexion();
        $this->conn = $db->conectar();
    }

    public function obtenerUsuario($email){
        $sql = "SELECT id_usuario AS id, nombre, contraseña, 'cliente' AS tipo FROM usuario WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();
        return $usuario ?: false;
    }

    public function obtenerEmpresa($email){
        $sql = "SELECT id_empresa AS id, nombre_empresa AS nombre, contraseña, 'empresa' AS tipo FROM empresas_proveedor WHERE email_empresa = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $empresa = $result->fetch_assoc();
        $stmt->close();
        return $empresa ?: false;
    }
}
?>

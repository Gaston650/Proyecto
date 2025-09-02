<?php
require_once __DIR__ . '/../conexion.php';

class usuario2Modelo {
    private $conn;

    public function __construct() {
        $db = new conexion();
        $this->conn = $db->conectar();
    }

    public function obtenerUsuario($email){
        $sql = "SELECT id_usuario AS id, nombre, contraseña, 'cliente' AS tipo FROM usuarios WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();
        return $usuario ?: false;
    }

    public function insertarUsuario($nombre, $email, $password) {
        $sql = "INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return ['exito' => false, 'error' => $this->conn->error];
        }
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $nombre, $email, $hashed);
        $ejec = $stmt->execute();
        if ($ejec) {
            $stmt->close();
            return ['exito' => true];
        } else {
            $error = $stmt->error;
            $stmt->close();
            return ['exito' => false, 'error' => $error];
        }
    }

    public function registrarUsuarioGoogle($nombre, $email) {
        $sql = "INSERT INTO usuarios (nombre, email) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $nombre, $email);
        $stmt->execute();
        $stmt->close();
    }

    public function buscarPorEmail($email) {
        return $this->obtenerUsuario($email);
    }
}
?>

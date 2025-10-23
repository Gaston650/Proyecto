<?php
require_once __DIR__ . '/../conexion.php';

class usuario2Modelo {
    private $conn;

    public function __construct() {
        $db = new conexion();
        $this->conn = $db->conectar();
    }

    public function obtenerUsuario($email){
        $sql = "SELECT id_usuario, nombre, email, contraseña, tipo_usuario FROM usuarios WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function insertarUsuario($nombre, $email, $password) {
        $sql = "INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return ['exito' => false, 'error' => $this->conn->error];

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $nombre, $email, $hashed);
        $ejec = $stmt->execute();
        $stmt->close();

        return $ejec ? ['exito' => true] : ['exito' => false, 'error' => $stmt->error];
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

    public function crearRecuperacion($id_usuario) {
        $token = bin2hex(random_bytes(32));
        $fecha_solicitud = date('Y-m-d H:i:s');
        $expiracion = date('Y-m-d H:i:s', time() + 3600); // 1 hora
        $estado = 'pendiente';

        $stmt = $this->conn->prepare(
            "INSERT INTO recuperacion_contrasena 
             (id_usuario, medio, fecha_solicitud, estado, token, expiracion) 
             VALUES (?, 'correo', ?, ?, ?, ?)"
        );
        $stmt->bind_param("issss", $id_usuario, $fecha_solicitud, $estado, $token, $expiracion);
        $stmt->execute();
        $stmt->close();

        return $token;
    }

    public function validarToken($token) {
        $sql = "SELECT id_recuperacion, id_usuario, expiracion 
                FROM recuperacion_contrasena 
                WHERE token = ? AND estado = 'pendiente'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    // Cambiar contraseña correctamente con mysqli
    public function actualizarContrasena($id_usuario, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "UPDATE usuarios SET contraseña = ? WHERE id_usuario = ?"
        );
        $stmt->bind_param("si", $hash, $id_usuario);
        $stmt->execute();
        $stmt->close();
    }

    // Marcar token usado correctamente con mysqli
    public function marcarTokenUsado($id_recuperacion) {
        $stmt = $this->conn->prepare(
            "UPDATE recuperacion_contrasena SET estado = 'usado' WHERE id_recuperacion = ?"
        );
        $stmt->bind_param("i", $id_recuperacion);
        $stmt->execute();
        $stmt->close();
    }

    public function obtenerUsuarioPorId($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT id_usuario AS id, nombre, contraseña, tipo_usuario, estado 
                FROM usuarios 
                WHERE id_usuario = '$id' 
                LIMIT 1";
        $resultado = $this->conn->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }
        return false;
    }
}
?>

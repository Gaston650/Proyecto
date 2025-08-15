<?php
require_once __DIR__ . '/../conexion.php';

class usuarioModelo {
    private $conexion;

    public function __construct() {
        $db = new conexion();
        $this->conexion = $db->conectar();

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function insertarUsuario($nombre, $email, $password) {
        $sql = "INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            die("Error al preparar consulta: " . $this->conexion->error);
        }
        $stmt->bind_param("sss", $nombre, $email, $password);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

        public function obtenerUsuarioPorEmail($email) {
            $sql = "SELECT id_usuario, nombre, email FROM usuarios WHERE email = ?";
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) {
                die("Error al preparar consulta obtenerUsuarioPorEmail: " . $this->conexion->error);
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $usuario = $resultado->fetch_assoc();
            $stmt->close();
            return $usuario;
        }

    public function registrarUsuarioGoogle($nombre, $email) {
        // Verificamos si ya existe
        $existe = $this->buscarPorEmail($email);
        if ($existe) {
            return $existe['id_usuario']; // ya existe, devolvemos su ID
        }

        $stmt = $this->conexion->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
        if (!$stmt) {
            die("Error al preparar consulta registrarUsuarioGoogle: " . $this->conexion->error);
        }
        $stmt->bind_param("ss", $nombre, $email);
        if ($stmt->execute()) {
            $insert_id = $stmt->insert_id;
            $stmt->close();
            return $insert_id;
        }
        $stmt->close();
        return false;
    }

    public function buscarPorEmail($email) {
        $stmt = $this->conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
        if (!$stmt) {
            die("Error al preparar consulta buscarPorEmail: " . $this->conexion->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();
        return $usuario;
    }

    public function loginUsuario($email) {
    $sql = "SELECT id_usuario, nombre, email, contraseña FROM usuarios WHERE email = ?";
    $stmt = $this->conexion->prepare($sql);
    if (!$stmt) {
        die("Error al preparar consulta loginUsuario: " . $this->conexion->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();
    $stmt->close();

    return $usuario; 
}

}
?>

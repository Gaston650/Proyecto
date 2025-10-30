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
    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
    $stmt = $this->conexion->prepare($sql);
    if (!$stmt) {
        return ['exito' => false, 'error' => "Error al preparar consulta: " . $this->conexion->error];
    }

    $stmt->bind_param("sss", $nombre, $email, $password);

    if ($stmt->execute()) {
        $stmt->close();
        return ['exito' => true, 'error' => '']; // ✅ Éxito
    } else {
        $error = $stmt->error;
        $stmt->close();
        return ['exito' => false, 'error' => $error]; // ❌ Error al ejecutar
    }
}



    public function obtenerUsuarioPorEmail($email) {
        $sql = "SELECT id_usuario, nombre, email, password, tipo_usuario, remember_token FROM usuarios WHERE email = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) die("Error al preparar consulta obtenerUsuarioPorEmail: " . $this->conexion->error);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();
        return $usuario;
    }

    public function registrarUsuarioGoogle($nombre, $email) {
        $existe = $this->buscarPorEmail($email);
        if ($existe) return $existe['id_usuario'];

        $stmt = $this->conexion->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
        if (!$stmt) die("Error al preparar consulta registrarUsuarioGoogle: " . $this->conexion->error);
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
        if (!$stmt) die("Error al preparar consulta buscarPorEmail: " . $this->conexion->error);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();
        return $usuario;
    }

    public function guardarToken($id_usuario, $token = null) {
        // Permite guardar o eliminar el token (pasar null para eliminar)
        if ($token === null) {
            $sql = "UPDATE usuarios SET remember_token = NULL WHERE id_usuario = ?";
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) die("Error al preparar consulta guardarToken (NULL): " . $this->conexion->error);
            $stmt->bind_param("i", $id_usuario);
        } else {
            $sql = "UPDATE usuarios SET remember_token = ? WHERE id_usuario = ?";
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) die("Error al preparar consulta guardarToken: " . $this->conexion->error);
            $stmt->bind_param("si", $token, $id_usuario);
        }
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function obtenerUsuarioPorToken(string $token) {
        $sql = "SELECT id_usuario, nombre, email, tipo_usuario, remember_token FROM usuarios WHERE remember_token = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) die("Error al preparar consulta obtenerUsuarioPorToken: " . $this->conexion->error);
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }

    public function obtenerUsuarioPorId($id_usuario) {
        $sql = "SELECT id_usuario, nombre, email, tipo_usuario FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) die("Error al preparar consulta obtenerUsuarioPorId: " . $this->conexion->error);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();
        return $usuario;
    }


    public function actualizarEmpresaRememberToken(int $id_empresa, ?string $token) {
        if ($token === null) {
            $sql = "UPDATE empresas_proveedor SET remember_token = NULL WHERE id_empresa = ?";
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) die("Error al preparar consulta actualizarEmpresaRememberToken (NULL): " . $this->conexion->error);
            $stmt->bind_param('i', $id_empresa);
        } else {
            $sql = "UPDATE empresas_proveedor SET remember_token = ? WHERE id_empresa = ?";
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) die("Error al preparar consulta actualizarEmpresaRememberToken: " . $this->conexion->error);
            $stmt->bind_param('si', $token, $id_empresa);
        }
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Actualizar el remember_token (pasar null para eliminar)
    public function actualizarRememberToken(int $user_id, ?string $token) {
        // Reutiliza guardarToken para mantener la lógica en un solo lugar
        return $this->guardarToken($user_id, $token);
    }
}
?>

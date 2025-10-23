<?php
require_once __DIR__ . '/../conexion.php';

class modeloRecEmpresa {
    private $conn;

    public function __construct() {
        $db = new conexion();
        $this->conn = $db->conectar();
    }

    // Obtener empresa por correo electrónico
    public function obtenerEmpresaPorEmail($email) {
        $sql = "SELECT id_empresa, nombre_empresa, email_empresa, contraseña, estado 
                FROM empresas_proveedor 
                WHERE email_empresa = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Crear registro de recuperación de contraseña
    public function crearRecuperacionEmpresa($id_empresa) {
        $token = bin2hex(random_bytes(32));
        $fecha_solicitud = date('Y-m-d H:i:s');
        $expiracion = date('Y-m-d H:i:s', time() + 3600); // Expira en 1 hora
        $estado = 'pendiente';

        $stmt = $this->conn->prepare(
            "INSERT INTO recuperacion_contrasena_empresa 
             (id_empresa, medio, fecha_solicitud, estado, token, expiracion) 
             VALUES (?, 'correo', ?, ?, ?, ?)"
        );

        if (!$stmt) {
            return ['exito' => false, 'error' => $this->conn->error];
        }

        $stmt->bind_param("issss", $id_empresa, $fecha_solicitud, $estado, $token, $expiracion);
        $stmt->execute();
        $stmt->close();

        return ['exito' => true, 'token' => $token];
    }

    // Validar token de recuperación
    public function validarTokenEmpresa($token) {
        $sql = "SELECT id_recuperacion, id_empresa, expiracion 
                FROM recuperacion_contrasena_empresa 
                WHERE token = ? AND estado = 'pendiente'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $data = $result->fetch_assoc();
        if (!$data) return false;

        // Verificar expiración
        if (strtotime($data['expiracion']) < time()) {
            return false;
        }

        return $data;
    }

    // Actualizar contraseña de la empresa
    public function actualizarContrasenaEmpresa($id_empresa, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "UPDATE empresas_proveedor SET contraseña = ? WHERE id_empresa = ?"
        );
        $stmt->bind_param("si", $hash, $id_empresa);
        $stmt->execute();
        $stmt->close();
    }

    // Marcar token como usado
    public function marcarTokenUsadoEmpresa($id_recuperacion) {
        $stmt = $this->conn->prepare(
            "UPDATE recuperacion_contrasena_empresa SET estado = 'usado' WHERE id_recuperacion = ?"
        );
        $stmt->bind_param("i", $id_recuperacion);
        $stmt->execute();
        $stmt->close();
    }

    // Obtener empresa por ID
    public function obtenerEmpresaPorId($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT id_empresa, nombre_empresa, email_empresa, estado, logo 
                FROM empresas_proveedor 
                WHERE id_empresa = '$id' 
                LIMIT 1";
        $resultado = $this->conn->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }
        return false;
    }
}
?>

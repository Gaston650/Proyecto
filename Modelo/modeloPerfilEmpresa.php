<?php
require_once __DIR__ . '/../conexion.php';

class modeloPerfilEmpresa {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener perfil de la empresa
    public function obtenerPerfil($id_empresa) {
        $sql = "SELECT * FROM perfil_proveedor WHERE id_empresa = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Guardar o actualizar perfil (sin teléfono)
    public function guardarPerfil($id_empresa, $descripcion, $habilidades, $experiencia, $zona_cobertura) {
        $perfilExistente = $this->obtenerPerfil($id_empresa);

        if ($perfilExistente) {
            $sql = "UPDATE perfil_proveedor 
                    SET descripcion=?, habilidades=?, experiencia=?, zona_cobertura=? 
                    WHERE id_empresa=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssi", $descripcion, $habilidades, $experiencia, $zona_cobertura, $id_empresa);
        } else {
            $sql = "INSERT INTO perfil_proveedor (id_empresa, descripcion, habilidades, experiencia, zona_cobertura) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("issss", $id_empresa, $descripcion, $habilidades, $experiencia, $zona_cobertura);
        }

        return $stmt->execute();
    }

    // Guardar o actualizar teléfono de la empresa
    public function actualizarTelefono($id_empresa, $telefono) {
        $sql = "SELECT * FROM telefono_empresa WHERE id_empresa = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $sql = "UPDATE telefono_empresa SET telefono=? WHERE id_empresa=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $telefono, $id_empresa);
        } else {
            $sql = "INSERT INTO telefono_empresa (id_empresa, telefono) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("is", $id_empresa, $telefono);
        }

        return $stmt->execute();
    }
}
?>



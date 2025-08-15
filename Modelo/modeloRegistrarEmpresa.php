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

    public function insertarEmpresa($nombre, $email, $zona, $logo, $rut, $password, $telefono) {
        // Insertar empresa (sin teléfono)
        $sql = "INSERT INTO empresas_proveedor 
            (nombre_empresa, email_empresa, zona_cobertura, logo, rut, contraseña) 
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            die("Error al preparar consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("ssssss", $nombre, $email, $zona, $logo, $rut, $password);

        $result = $stmt->execute();

        // Obtener el id_empresa recién insertado
        $id_empresa = $this->conexion->insert_id;

        $stmt->close();

        // Insertar teléfono en la tabla telefono_empresa
        if ($result && $id_empresa) {
            $sqlTel = "INSERT INTO telefono_empresa (id_empresa, telefono) VALUES (?, ?)";
            $stmtTel = $this->conexion->prepare($sqlTel);

            if (!$stmtTel) {
                die("Error al preparar consulta de teléfono: " . $this->conexion->error);
            }

            $stmtTel->bind_param("is", $id_empresa, $telefono);
            $resultTel = $stmtTel->execute();
            $stmtTel->close();

            return $resultTel;
        }

        return false;
    }
}
?>
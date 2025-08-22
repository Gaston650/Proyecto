<?php
require_once __DIR__ . '/../../Modelo/modeloRegistrarEmpresa.php';

class empresaControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new empresaModelo();
    }

    public function guardarEmpresa($nombre, $email, $zona, $logo, $rut, $password, $telefono) {
        // Insertar la empresa (sin el teléfono)
        $id_empresa = $this->modelo->insertarEmpresa($nombre, $email, $logo, $password, $rut);
        
        if ($id_empresa) {
            // Insertar el teléfono en la tabla telefono_empresa
            $this->modelo->insertarTelefono($id_empresa, $telefono);
            return true;
        }

        return false;
    }
}
?>


<?php
require_once __DIR__ . '/../../Modelo/modeloRegistrarEmpresa.php';

class empresaControladorWrapper2 {
    private $modelo;

    public function __construct() {
        $this->modelo = new empresaModelo();
    }

    // Método para registrar empresa
    public function registrar($nombre, $email, $zona, $logoNombre, $telefono, $password, $rut) {
        // Verificar si el email ya existe
        if ($this->modelo->obtenerEmpresa($email)) {
            return "EMAIL_DUPLICADO";
        }

        // Guardar empresa
        $resultado = $this->modelo->insertarEmpresa($nombre, $email, $zona, $logoNombre, $password, $rut);

        if ($resultado) {
            // Guardar teléfono
            $empresa = $this->modelo->obtenerEmpresa($email); // Obtener ID de la empresa recién creada
            $this->modelo->insertarTelefono($empresa['id_empresa'], $telefono);
            return true;
        } else {
            return false;
        }
    }
}
?>

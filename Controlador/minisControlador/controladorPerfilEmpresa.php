<?php
require_once __DIR__ . '/../../Modelo/modeloPerfilEmpresa.php';
require_once __DIR__ . '/../../conexion.php';

class controladorPerfilEmpresa {
    private $modelo;

    public function __construct() {
        $db = new conexion();
        $conn = $db->conectar();
        $this->modelo = new modeloPerfilEmpresa($conn);
    }

    // Editar perfil y teléfono + logo en empresas_proveedor
    public function editarPerfilEmpresa($idEmpresa, $descripcion, $habilidades, $experiencia, $zona, $telefono, $logo = null) {
        if ($logo) {
            $this->modelo->actualizarLogo($idEmpresa, $logo);
        }
        $perfilActualizado = $this->modelo->guardarPerfil($idEmpresa, $descripcion, $habilidades, $experiencia, $zona);
        $this->modelo->actualizarTelefono($idEmpresa, $telefono);
        return $perfilActualizado;
    }

    public function obtenerPerfil($idEmpresa) {
        return $this->modelo->obtenerPerfil($idEmpresa);
    }
}
?>

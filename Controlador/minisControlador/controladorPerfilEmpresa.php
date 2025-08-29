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

    // Editar perfil y teléfono
    public function editarPerfilEmpresa($idEmpresa, $descripcion, $habilidades, $experiencia, $zona, $telefono) {
        $perfilActualizado = $this->modelo->guardarPerfil($idEmpresa, $descripcion, $habilidades, $experiencia, $zona);
        $telefonoActualizado = $this->modelo->actualizarTelefono($idEmpresa, $telefono);
        return $perfilActualizado && $telefonoActualizado;
    }

    public function obtenerPerfil($idEmpresa) {
        return $this->modelo->obtenerPerfil($idEmpresa);
    }
}
?>


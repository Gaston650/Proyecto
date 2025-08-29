<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';

if (!isset($_SESSION['empresa_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Debes iniciar sesión como empresa.");
    exit();
}

$perfilWrapper = new perfilEmpresaControladorWrapper();

$empresaId = $_SESSION['empresa_id'];
$descripcion = $_POST['descripcion'] ?? '';
$habilidades = $_POST['habilidades'] ?? '';
$experiencia = $_POST['experiencia'] ?? '';
$zona = $_POST['zona'] ?? '';
$telefono = $_POST['telefono'] ?? '';

$resultado = $perfilWrapper->editarPerfilEmpresa($empresaId, $descripcion, $habilidades, $experiencia, $zona, $telefono);

if ($resultado) {
    header("Location: ../../Vista/vistaEditarPerfil/editarPerfilEmpresa.php?success=Perfil actualizado correctamente");
} else {
    header("Location: ../../Vista/vistaEditarPerfil/editarPerfilEmpresa.php?error=No se pudo actualizar el perfil");
}
exit();
?>
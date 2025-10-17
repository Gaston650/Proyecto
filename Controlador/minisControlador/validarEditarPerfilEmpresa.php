<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$perfilWrapper = new perfilEmpresaControladorWrapper();

$empresaId = $_SESSION['user_id'];
$descripcion = $_POST['descripcion'] ?? '';
$habilidades = $_POST['habilidades'] ?? '';
$experiencia = $_POST['experiencia'] ?? '';
$zona = $_POST['zona'] ?? '';
$telefono = $_POST['telefono'] ?? '';

$logoNombre = null;
if (isset($_FILES['logoEmpresa']) && $_FILES['logoEmpresa']['error'] === 0) {
    $ext = pathinfo($_FILES['logoEmpresa']['name'], PATHINFO_EXTENSION);
    $logoNombre = 'logo_' . $empresaId . '.' . $ext;
    move_uploaded_file($_FILES['logoEmpresa']['tmp_name'], "../../IMG/empresas/" . $logoNombre);
    $_SESSION['empresa_logo'] = $logoNombre; 
}

$resultado = $perfilWrapper->editarPerfilEmpresa(
    $empresaId,
    $descripcion,
    $habilidades,
    $experiencia,
    $zona,
    $telefono,
    $logoNombre
);

if ($resultado) {
    header("Location: ../../Vista/vistaEditarPerfil/editarPerfilEmpresa.php?exito=Perfil actualizado correctamente");
} else {
    header("Location: ../../Vista/vistaEditarPerfil/editarPerfilEmpresa.php?error=No se pudo actualizar el perfil");
}
exit();
?>

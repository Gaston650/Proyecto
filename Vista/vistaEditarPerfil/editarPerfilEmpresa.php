<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión como empresa.");
    exit();
}

$perfilWrapper = new perfilEmpresaControladorWrapper();
$empresaId = $_SESSION['user_id'];
$perfil = $perfilWrapper->obtenerPerfil($empresaId);

$img = !empty($perfil['logo'])
    ? '../../IMG/empresas/' . $perfil['logo']
    : '../../IMG/perfil-vacio.png';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - Empresa</title>
    <link rel="stylesheet" href="editarPerfilEmpresa.css">
</head>
<body>

<header>
    <a href="../VistaPrincipal/homeEmpresa.php" class="volver">Volver al inicio</a>
    <form action="../../cerrar_sesion.php" method="POST" style="margin:0;">
        <button type="submit" class="btn-cerrar-sesion">Cerrar Sesión</button>
    </form>
</header>

<main>
    <h2>Editar Perfil de Empresa</h2>

    <?php if (isset($_GET['exito'])): ?>
        <div class="mensaje-exito">
            <?php echo htmlspecialchars($_GET['exito']); ?>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="mensaje-error">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form action="../../Controlador/minisControlador/validarEditarPerfilEmpresa.php" method="POST" enctype="multipart/form-data" class="formulario-perfil">
        <div class="profile-pic">
            <img src="<?php echo htmlspecialchars($img); ?>" alt="Logo de empresa" class="logo-empresa" id="previewLogo">
            <input type="file" name="logoEmpresa" accept="image/*" id="logoInput">
        </div>

        <div class="form-group">
            <label for="nombre">Nombre de la empresa</label>
           <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($perfil['nombre_empresa'] ?? ''); ?>" disabled>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($perfil['descripcion'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="habilidades">Habilidades</label>
            <textarea id="habilidades" name="habilidades" rows="3"><?php echo htmlspecialchars($perfil['habilidades'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="experiencia">Experiencia</label>
            <textarea id="experiencia" name="experiencia" rows="3"><?php echo htmlspecialchars($perfil['experiencia'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="zona">Zona de cobertura</label>
            <input type="text" id="zona" name="zona" value="<?php echo htmlspecialchars($perfil['zona_cobertura'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($perfil['telefono'] ?? ''); ?>">
        </div>

        <div class="actions">
            <button type="reset" class="btn-cancel">Cancelar</button>
            <button type="submit" class="btn-guardar">Guardar cambios</button>
        </div>
    </form>
</main>

<script>
const logoInput = document.getElementById('logoInput');
const previewLogo = document.getElementById('previewLogo');

logoInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewLogo.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>

</body>
</html>
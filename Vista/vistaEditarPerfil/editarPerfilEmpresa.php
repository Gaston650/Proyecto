<?php
session_start();
$img = (isset($_SESSION['empresa_logo']) && !empty($_SESSION['empresa_logo']))
    ? '../../IMG/empresas/' . $_SESSION['empresa_logo']
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
    <form action="../../Controlador/cerrar_sesion.php" method="POST" style="margin:0;">
        <button type="submit" class="btn-cerrar-sesion">Cerrar Sesión</button>
    </form>
</header>

<main>
    <h2>Editar Perfil de Empresa</h2>

    <?php if (isset($_GET['exito'])): ?>
        <div class="mensaje-exito">
            Tus cambios se guardaron correctamente.
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="mensaje-error">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form action="../../Controlador/minisControlador/validarEditarPerfilEmpresa.php" method="POST" enctype="multipart/form-data" class="formulario-perfil">
        
        <!-- Logo de la empresa -->
        <div class="profile-pic">
            <img src="<?php echo htmlspecialchars($img); ?>" alt="Logo de empresa" class="logo-empresa">
            <input type="file" name="logoEmpresa" accept="image/*">
        </div>

        <!-- Nombre de la empresa -->
        <div class="form-group">
            <label for="nombre">Nombre de la empresa</label>
            <input type="text" id="nombre" name="nombre"
                value="<?php echo isset($_SESSION['user_nombre']) ? htmlspecialchars($_SESSION['user_nombre']) : ''; ?>">
        </div>

        <!-- Descripción -->
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="4"></textarea>
        </div>

        <!-- Habilidades -->
        <div class="form-group">
            <label for="habilidades">Habilidades</label>
            <textarea id="habilidades" name="habilidades" rows="3"></textarea>
        </div>

        <!-- Experiencia -->
        <div class="form-group">
            <label for="experiencia">Experiencia</label>
            <textarea id="experiencia" name="experiencia" rows="3"></textarea>
        </div>

        <!-- Zona de cobertura -->
        <div class="form-group">
            <label for="zona">Zona de cobertura</label>
            <input type="text" id="zona" name="zona">
        </div>

        <!-- Teléfono -->
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono">
        </div>

        <!-- Botones -->
        <div class="actions">
            <button type="reset" class="btn-cancel">Cancelar</button>
            <button type="submit" class="btn-guardar">Guardar cambios</button>
        </div>
    </form>
</main>

</body>
</html>

<?php
session_start();
require_once '../../../Controlador/superControlador/superControlador.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$mensaje = $_GET['mensaje'] ?? '';

$servicioWrapper = new servicioControladorWrapper();
$categorias = $servicioWrapper->obtenerCategorias();

// Departamentos de Uruguay
$departamentos = [
    'Artigas', 'Canelones', 'Cerro Largo', 'Colonia', 'Durazno',
    'Flores', 'Florida', 'Lavalleja', 'Maldonado', 'Montevideo',
    'Paysandú', 'Río Negro', 'Rivera', 'Rocha', 'Salto',
    'San José', 'Soriano', 'Tacuarembó', 'Treinta y Tres'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Publicar Servicio</title>
<link rel="stylesheet" href="publicarServicio.css">
</head>
<body>
<div class="container">
    <h1>Publicar nuevo servicio</h1>

    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <form method="POST" action="../../../Controlador/minisControlador/validarPublicarServicio.php" enctype="multipart/form-data">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" placeholder="Nombre del servicio" required>

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" placeholder="Detalles del servicio" required></textarea>

        <label for="categoria">Categoría</label>
        <select id="categoria" name="categoria" required>
            <option value="">-- Selecciona una categoría --</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat['nombre_categoria']); ?>">
                    <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="ubicacion">Ubicación (Departamento)</label>
        <select id="ubicacion" name="ubicacion" required>
            <option value="">-- Selecciona un departamento --</option>
            <?php foreach ($departamentos as $dep): ?>
                <option value="<?php echo htmlspecialchars($dep); ?>"><?php echo htmlspecialchars($dep); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="precio">Precio</label>
        <input type="number" id="precio" step="0.01" name="precio" placeholder="Precio en $" required>

        <label for="disponibilidad">Disponibilidad</label>
        <input type="text" id="disponibilidad" name="disponibilidad" placeholder="Días y horarios disponibles" required>

        <label for="estado">Estado</label>
        <select id="estado" name="estado" required>
            <option value="Disponible">Disponible</option>
            <option value="Ocupado">Ocupado</option>
            <option value="Cancelado">Cancelado</option>
        </select>

        <label for="imagen">Imagen del servicio</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" onchange="mostrarNombreArchivo(this)">
        <span id="nombre-archivo">Ningún archivo seleccionado</span>

        <div id="preview-container">
            <img id="preview-imagen" src="" alt="" style="display:none;">
        </div>

        <div class="botones">
            <button type="submit">Publicar Servicio</button>
            <a href="../../VistaPrincipal/homeEmpresa.php">← Volver</a>
        </div>
    </form>
</div>

<script>
function mostrarNombreArchivo(input) {
    const nombreArchivo = document.getElementById('nombre-archivo');
    const preview = document.getElementById('preview-imagen');
    const archivo = input.files[0];

    if (archivo) {
        nombreArchivo.textContent = archivo.name;

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(archivo);
    } else {
        nombreArchivo.textContent = 'Ningún archivo seleccionado';
        preview.style.display = 'none';
    }
}
</script>
</body>
</html>

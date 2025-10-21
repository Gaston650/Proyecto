<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';

// Validar sesión de empresa
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../../VistaSesion/inicioSesion.php?error=" . urlencode("Debes iniciar sesión primero."));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y sanear inputs
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $ubicacion = trim($_POST['ubicacion'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);
    $disponibilidad = trim($_POST['disponibilidad'] ?? '');
    $estado = trim($_POST['estado'] ?? 'activo');

    // Validar campos obligatorios
    $errores = [];
    if ($titulo === '') $errores[] = "El título es obligatorio.";
    if ($descripcion === '') $errores[] = "La descripción es obligatoria.";
    if ($categoria === '') $errores[] = "La categoría es obligatoria.";
    if ($precio <= 0) $errores[] = "El precio debe ser mayor a 0.";

    // Manejo de imagen
    $nombreImagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreOriginal = $_FILES['imagen']['name'];
        $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

        // Validar extensión
        $allowed = ['jpg','jpeg','png','gif'];
        if (!in_array($extension, $allowed)) {
            $errores[] = "Formato de imagen no permitido. Solo JPG, PNG y GIF.";
        } else {
            $nombreImagen = time() . '_' . uniqid() . '.' . $extension;
            $rutaDestino = __DIR__ . '/../../uploads/' . $nombreImagen;

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $nombreImagen = null; // si falla, se publica sin imagen
            }
        }
    }

    // Si hay errores, redirigir con mensaje
    if (!empty($errores)) {
        $mensaje = implode(" ", $errores);
        header("Location: http://localhost/ClickSoft/Vista/VistaServicios/VistaPublicarServicios/publicarServicio.php?error=" . urlencode($mensaje));
        exit();
    }

    // Publicar servicio usando tu controlador
    $servicioWrapper = new servicioControladorWrapper();
    $resultado = $servicioWrapper->publicarServicio(
        $_SESSION['user_id'], 
        $titulo, 
        $descripcion,
        $categoria,
        $ubicacion, 
        $precio, 
        $disponibilidad, 
        $estado, 
        $nombreImagen
    );

    $mensaje = $resultado ? "Servicio publicado correctamente." : "Error al publicar el servicio.";
    header("Location: http://localhost/ClickSoft/Vista/VistaServicios/VistaPublicarServicios/publicarServicio.php?mensaje=" . urlencode($mensaje));
    exit();
}
?>

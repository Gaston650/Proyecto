<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar campos obligatorios
    if (empty($_POST['id_empresa']) || empty($_POST['titulo']) || empty($_POST['descripcion']) || empty($_POST['precio']) || empty($_POST['categoria'])) {
        header("Location: ../../Vista/VistaAdmin/dashboard.php?modulo=servicios&error=Faltan campos obligatorios");
        exit();
    }

    $id_empresa = $_POST['id_empresa'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];

    $imagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $nombreArchivo = basename($_FILES['imagen']['name']);
        $carpetaDestino = __DIR__ . '/../../IMG/servicios/';

        // Crear carpeta si no existe
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0755, true);
        }

        $rutaDestino = $carpetaDestino . $nombreArchivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imagen = 'servicios/' . $nombreArchivo;
        } else {
            header("Location: ../../Vista/VistaAdmin/dashboard.php?modulo=servicios&error=Error al subir la imagen");
            exit();
        }
    }

    // Instanciar wrapper
    if (!isset($wrapper)) {
        $wrapper = new adminControladorWrapper();
    }

    try {
        $wrapper->crearServicio($id_empresa, $titulo, $descripcion, $precio, $categoria, $imagen);
        header("Location: ../../Vista/VistaAdmin/dashboard.php?modulo=servicios&mensaje=Servicio creado correctamente");
        exit();
    } catch (Exception $e) {
        header("Location: ../../Vista/VistaAdmin/dashboard.php?modulo=servicios&error=Error al crear el servicio");
        exit();
    }
} else {
    header("Location: ../../Vista/VistaAdmin/dashboard.php?modulo=servicios&error=Método no permitido");
    exit();
}
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../superControlador/superControlador.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id_servicio'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $categoria = $_POST['categoria'] ?? '';
    $estado = $_POST['estado'] ?? 'Disponible'; 

    $imagen = null;

    // Subir imagen si se seleccionó
    if (!empty($_FILES['imagen']['name']) && !empty($_FILES['imagen']['tmp_name'])) {
        $nombreArchivo = basename($_FILES['imagen']['name']);
        $rutaDestino = __DIR__ . '/../../IMG/servicios/' . $nombreArchivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imagen = $nombreArchivo;
        }
    }

    // Preparar datos en array para el wrapper
    $data = [
        'titulo'      => $titulo,
        'descripcion' => $descripcion,
        'ubicacion'   => $ubicacion,
        'precio'      => $precio,
        'categoria'   => $categoria,
        'estado'      => $estado,
        'imagen'      => $imagen
    ];

    // Instanciar el wrapper correcto
    $servicioWrapper = new adminControladorWrapper();
    $resultado = $servicioWrapper->editarServicio($id, $data);

    if ($resultado) {
    header("Location: ../../Vista/VistaAdmin/dashboard.php?modulo=servicios&mensaje=Servicio actualizado");
    } else {
        header("Location: ../../Vista/VistaAdmin/dashboard.php?modulo=servicios&error=No se pudo actualizar");
    }

    exit();

}
?>

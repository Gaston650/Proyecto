<?php
session_start();
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/controladorServicio.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../../VistaSesion/inicioSesion.php?error=Debes+iniciar+sesión+primero");
    exit();
}

$conn = (new conexion())->conectar();
$controlador = new controladorServicio($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_servicio = $_POST['id_servicio'] ?? null;
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $disponibilidad = $_POST['disponibilidad'] ?? 'Disponible';
    $estado = $_POST['estado'] ?? 'Disponible';
    $imagen = $_FILES['imagen']['name'] ?? null;

    if (!$id_servicio) {
        header("Location: ../../VistaServicios/serviciosEmpresa.php?error=Servicio+no+especificado");
        exit();
    }

    // Subida de imagen si se proporcionó
    if ($imagen && isset($_FILES['imagen']['tmp_name'])) {
        $destino = __DIR__ . "/../../IMG/servicios/" . basename($imagen);
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
            $imagen = "IMG/servicios/" . basename($imagen);
        } else {
            $imagen = null;
        }
    } else {
        $imagen = null;
    }

    $resultado = $controlador->editarServicio(
        $id_servicio,
        $titulo,
        $descripcion,
        $categoria,
        $ubicacion,
        $precio,
        $disponibilidad,
        $estado,
        $imagen
    );

    if ($resultado) {
        header("Location: ../../Vista/VistaServicios/serviciosEmpresa.php?success=1");
    } else {
        header("Location: ../../Vista/VistaServicios/serviciosEmpresa.php?error=Error+al+editar+el+servicio");
    }
    exit();
}
?>

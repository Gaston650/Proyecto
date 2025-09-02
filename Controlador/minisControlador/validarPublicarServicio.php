<?php

session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $disponibilidad = $_POST['disponibilidad'] ?? '';
    $estado = $_POST['estado'] ?? 'Disponible';

    $servicioWrapper = new servicioControladorWrapper();
    $resultado = $servicioWrapper->publicarServicio(
        $_SESSION['user_id'], $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado
    );

    $mensaje = $resultado ? "Servicio publicado correctamente." : "Error al publicar el servicio.";

    // Redirigir a la vista correcta
    header("Location: http://localhost/ClickSoft/Vista/VistaServicios/VistaPublicarServicios/publicarServicio.php?mensaje=" . urlencode($mensaje));
    exit();

}
?>


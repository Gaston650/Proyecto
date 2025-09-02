<?php
session_start();
require_once __DIR__ . '/controladorFavorito.php';

// Validar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php");
    exit();
}

$id_cliente = $_SESSION['user_id'];
$id_servicio = $_GET['id_servicio'] ?? null;
$accion = $_GET['accion'] ?? null;

$favoritos = new controladorFavorito();

if ($id_servicio && $accion) {
    if ($accion === 'agregar') {
        $favoritos->agregarFavorito($id_cliente, $id_servicio);
    } elseif ($accion === 'quitar') {
        $favoritos->quitarFavorito($id_cliente, $id_servicio);
    }
}

// Redirigir a la página anterior
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>

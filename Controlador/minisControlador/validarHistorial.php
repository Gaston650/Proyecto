<?php
session_start();
require_once __DIR__ . '/../Controlador/superControlador/superControlador.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$id_cliente = $_SESSION['user_id'];
$historialWrapper = new historialControladorWrapper();

// Procesar cancelación
if (isset($_POST['cancelar'])) {
    $reserva_id = $_POST['reserva_id'];
    $motivo = $_POST['motivo'] ?? null;
    $historialWrapper->cancelarReserva($reserva_id, $motivo);
    header("Location: ../../Vista/VistaHistorisl/historial.php"); // redirige a la vista principal
    exit();
}

// Procesar comentario y calificación
if (isset($_POST['guardar_comentario'])) {
    $reserva_id = $_POST['reserva_id'];
    $comentario = $_POST['comentarios_cliente'] ?? '';
    $calificacion = $_POST['calificacion'] ?? null;
    $historialWrapper->agregarComentario($reserva_id, $comentario, $calificacion);
    header("Location: ../../Vista/VistaHistorisl/historial.php"); 
    exit();
}
?>
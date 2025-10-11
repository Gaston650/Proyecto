<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

// Verificar ID de reserva
if (!isset($_GET['id'])) {
    header("Location: reservas.php?error=faltan_datos");
    exit();
}

$id_reserva = intval($_GET['id']);
$id_cliente = $_SESSION['user_id'];

// Instanciar wrapper de reservas
$reservasWrapper = new reservasControladorWrapper();

// Cancelar reserva
if ($reservasWrapper->cancelarReserva($id_reserva, $id_cliente)) {
    header("Location: ../../Vista/VistaReservas/reservas.php?msg=cancelado");
    exit();
} else {
    header("Location: ../../Vista/VistaReservas/reservas.php?error=fallo_cancelar");
    exit();
}
?>

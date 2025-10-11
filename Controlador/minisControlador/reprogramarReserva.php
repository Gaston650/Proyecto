<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';

// Verificar sesión
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$id_cliente = $_SESSION['user_id'];
$id_reserva = $_GET['id'] ?? null;
$fecha = $_GET['fecha'] ?? null;
$hora = $_GET['hora'] ?? null;

// Validar parámetros
if (!$id_reserva || !$fecha || !$hora) {
    header("Location: ../../Vista/VistaReservas/reservas.php?error=datos_incompletos");
    exit();
}

// Validar que la fecha y hora no sean pasadas
$fechaHoraSeleccionada = strtotime($fecha . ' ' . $hora);
$fechaHoraActual = strtotime(date('Y-m-d H:i'));

if ($fechaHoraSeleccionada < $fechaHoraActual) {
    header("Location: ../../Vista/VistaReservas/reservas.php?error=fecha_invalida");
    exit();
}

// Instanciar wrapper de reservas
$reservasWrapper = new reservasControladorWrapper();

// Reprogramar reserva (envía notificación al proveedor automáticamente)
$resultado = $reservasWrapper->reprogramarReserva($id_reserva, $fecha, $hora, $id_cliente);

if ($resultado) {
    header("Location: ../../Vista/VistaReservas/reservas.php?msg=reprogramado");
} else {
    header("Location: ../../Vista/VistaReservas/reservas.php?error=fallo_reprogramar");
}
?>

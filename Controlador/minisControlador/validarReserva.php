<?php
session_start();

require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

// Validar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=" . urlencode("Debes iniciar sesión primero."));
    exit();
}

// Validar que los datos POST existan
if (!isset($_POST['id_servicio'], $_POST['fecha'], $_POST['hora'])) {
    header("Location: ../../Vista/VistaServicios/servicios.php?mensaje=" . urlencode("Datos incompletos para la reserva"));
    exit();
}

$id_cliente = $_SESSION['user_id'];
$id_servicio = $_POST['id_servicio'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];

// Crear reserva usando el wrapper
$reservaWrapper = new reservasControladorWrapper();
$exito = $reservaWrapper->crearReserva($id_cliente, $id_servicio, $fecha, $hora);

// Redireccionar según el resultado
if ($exito) {
    header("Location: ../../Vista/VistaServicios/servicios.php?mensaje=" . urlencode("Reserva creada con éxito"));
} else {
    header("Location: ../../Vista/VistaServicios/servicios.php?mensaje=" . urlencode("No se pudo crear la reserva. Verifique disponibilidad y datos."));
}
exit();
?>

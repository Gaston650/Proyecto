<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

// Validar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=" . urlencode("Debes iniciar sesión primero."));
    exit();
}

$reservaWrapper = new reservasControladorWrapper();

// Caso 1: Cliente creando reserva
if (isset($_POST['id_servicio'], $_POST['fecha'], $_POST['hora'])) {
    $id_cliente = $_SESSION['user_id'];
    $id_servicio = $_POST['id_servicio'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $comentarios = $_POST['comentarios'] ?? "";

    $exito = $reservaWrapper->crearReserva($id_cliente, $id_servicio, $fecha, $hora, $comentarios);

    if ($exito) {
        // 🔹 Mandamos parámetro `exito=1` para abrir modal en servicios.php
        header("Location: ../../Vista/VistaServicios/servicios.php?exito=1");
    } else {
        header("Location: ../../Vista/VistaServicios/servicios.php?error=" . urlencode("No se pudo crear la reserva. Verifique disponibilidad y datos."));
    }
    exit();
}

// Caso 2: Proveedor actualizando estado
if (isset($_POST['id_reserva'], $_POST['estado'])) {
    $id_reserva = $_POST['id_reserva'];
    $estado = $_POST['estado'];

    $exito = $reservaWrapper->actualizarEstado($id_reserva, $estado);

    // Redirigir según tipo de usuario
    if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'empresa') {
        $redirect = "reservasEmpresa.php";
    } else {
        $redirect = "reservas.php";
    }

    if ($exito) {
        header("Location: ../../Vista/VistaReservas/$redirect?mensaje=" . urlencode("Reserva actualizada con éxito"));
    } else {
        header("Location: ../../Vista/VistaReservas/$redirect?mensaje=" . urlencode("No se pudo actualizar la reserva"));
    }
    exit();
}

// Si no entra en ninguno de los dos casos
header("Location: ../../Vista/VistaPrincipal/home.php?error=" . urlencode("Acción inválida"));
exit();
?>

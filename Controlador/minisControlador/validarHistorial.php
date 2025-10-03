<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$id_cliente = $_SESSION['user_id'];
$historialWrapper = new historialControladorWrapper();

// --------------------
// Cancelación de reserva
// --------------------
if (isset($_POST['cancelar'])) {
    $reserva_id = intval($_POST['reserva_id']);
    $motivo = trim($_POST['motivo'] ?? null);

    $historialWrapper->cancelarReserva($reserva_id, $motivo);

    header("Location: ../../Vista/VistaHistorial/historial.php");
    exit();
}

// --------------------
// Guardar comentario / reseña
// --------------------
if (isset($_POST['guardar_comentario'])) {
    $reserva_id = intval($_POST['reserva_id']);
    $comentario = trim($_POST['comentarios_cliente'] ?? '');
    $calificacion = intval($_POST['calificacion'] ?? 0);

    if ($calificacion >= 1 && $calificacion <= 5) {
        $reservaWrapper = new reservasControladorWrapper();
        $reservas = $reservaWrapper->verReservasCliente($id_cliente);

        $id_servicio = null;
        foreach ($reservas as $r) {
            if ($r['id_reserva'] == $reserva_id) {
                $id_servicio = $r['id_servicio'];
                break;
            }
        }

        if ($id_servicio) {
            $historialWrapper->agregarComentario($id_cliente, $id_servicio, $comentario, $calificacion);

            // Guardamos session flash para mostrar modal
            $_SESSION['reseña_guardada'] = true;

            // Redirige sin parámetros GET
            header("Location: ../../Vista/VistaHistorial/historial.php");
            exit();
        }
    }

    // Si algo falla, vuelve sin parámetro
    header("Location: ../../Vista/VistaHistorial/historial.php");
    exit();
}
?>

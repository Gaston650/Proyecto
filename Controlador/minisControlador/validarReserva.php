<?php
session_start();
require_once __DIR__ . '/../../Modelo/modeloReservas.php';
require_once __DIR__ . '/../../Controlador/minisControlador/controladorServicio.php';

$conn = (new conexion())->conectar();
$controladorServicio = new controladorServicio($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_servicio = $_POST['id_servicio'] ?? null;
    $fecha = $_POST['fecha'] ?? null;
    $hora = $_POST['hora'] ?? null;
    $comentarios = $_POST['comentarios'] ?? '';
    $usuario = $_SESSION['user_id'] ?? null;

    if (!$id_servicio || !$usuario || !$fecha) {
        $_SESSION['error'] = 'Datos incompletos para reservar.';
        header('Location: ../../Vista/VistaReservas/reservas.php');
        exit;
    }

    $resultado = ModeloReservas::crearReserva($usuario, $id_servicio, $fecha, $hora, $comentarios);

    if ($resultado['success']) {
        $_SESSION['success'] = $resultado['mensaje'];
    } else {
        $_SESSION['error'] = $resultado['mensaje'];
    }

    header('Location: ../../Vista/VistaReservas/reservas.php');
    exit;
}

<?php
session_start();
require_once __DIR__ . '/../Modelo/conexion.php';
require_once __DIR__ . '/../Controlador/controladorServicio.php';

$conn = (new conexion())->conectar();
$controlador = new controladorServicio($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_servicio = $_POST['id_servicio'] ?? null;
    $usuario = $_SESSION['usuario']['id'] ?? null;

    if (!$id_servicio || !$usuario) {
        die("Datos incompletos para reservar.");
    }

    // Validar disponibilidad
    $servicio = $controlador->obtenerServicio($id_servicio);
    if (!$servicio || strtolower($servicio['disponibilidad']) !== 'disponible') {
        die("El servicio no está disponible.");
    }

    // Actualizar disponibilidad a "Reservado"
    $controlador->actualizarDisponibilidad($id_servicio, 'Reservado');

    // Aquí se podría agregar registro de reserva en tabla de reservas
    echo json_encode(['success' => true, 'mensaje' => 'Reserva realizada correctamente.']);
}
?>

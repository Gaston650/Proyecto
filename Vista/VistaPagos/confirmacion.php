<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';
require_once __DIR__ . '/../../Modelo/modeloReservas.php';

// Recibimos datos desde GET
$operacion = $_GET['pref_id'] ?? 'SIM-'.rand(100000,999999);
$precio = $_GET['price'] ?? 0;
$producto = $_GET['title'] ?? 'Producto';
$id_reserva = $_GET['id_reserva'] ?? null;
$tarjeta = "Visa **** 3704"; // simulado

if ($id_reserva) {
    // Registrar pago en la base de datos
    $pagoWrapper = new pagoControladorWrapper();
    $pagoWrapper->pagoRealizado($id_reserva, $precio);

    // Obtener información de la reserva
    $reserva = ModeloReservas::obtenerReservaPorId($id_reserva);
    $id_empresa = $reserva['id_empresa'] ?? null;
    $nombre_cliente = $reserva['nombre_cliente'] ?? 'Cliente';
    $nombre_servicio = $reserva['nombre_servicio'] ?? 'tu servicio';

    // Insertar notificación normal para la empresa
    if ($id_empresa) {
        $notifWrapper = new notificacionControladorWrapper();
        $mensaje = "$nombre_cliente ha realizado el pago de la reserva para '$nombre_servicio'.";
        $notifWrapper->insertarNotificacion($id_empresa, $mensaje);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pago Acreditado</title>
<style>
body { font-family: Arial, sans-serif; text-align: center; background: #f5f5f5; padding: 50px; }
.card { background: #fff; display: inline-block; padding: 40px 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); width: 360px; animation: fadeIn 0.5s ease; }
h1 { color: #4caf50; margin-bottom: 20px; font-size: 26px; }
p { font-size: 18px; margin: 8px 0; color: #333; }
strong { font-weight: bold; }
hr { margin: 20px 0; border: none; border-top: 1px solid #ddd; }
button { background: #009ee3; color: white; border: none; padding: 12px 25px; font-size: 16px; border-radius: 8px; cursor: pointer; margin-top: 20px; transition: background 0.3s ease, transform 0.2s ease; }
button:hover { background: #007bbf; transform: scale(1.05); }
button:active { transform: scale(0.98); }
@keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>
</head>
<body>

<div class="card">
    <h1>¡Listo! Tu pago ya se acreditó</h1>
    <p>Operación #<?= htmlspecialchars($operacion) ?></p>
    <p>Pagaste $ <?= number_format($precio, 0, ",", ".") ?></p>
    <p><?= htmlspecialchars($tarjeta) ?></p>
    <hr>
    <p><?= htmlspecialchars($producto) ?></p>
    <p><strong>Total: $ <?= number_format($precio, 0, ",", ".") ?></strong></p>
    <button onclick="window.location.href='../VistaReservas/reservas.php'">Volver a las reservas</button>
</div>

</body>
</html>


<?php
require_once 'mp_ngrok.php';

// Obtenemos datos de GET
$item = getPaymentItem();
$id_reserva = $_GET['id_reserva'] ?? null;
$title = $_GET['title'] ?? $item['title'];
$price = $_GET['price'] ?? $item['unit_price'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pagar (ir directo a confirmación)</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; text-align: center; padding-top: 50px; }
.card { background: #fff; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); padding: 40px; display: inline-block; width: 380px; }
button { background: #009ee3; border: none; color: white; padding: 12px 25px; font-size: 16px; border-radius: 50px; cursor: pointer; }
button:hover { background: #007bbf; }
.note { font-size: 13px; color: #666; margin-top: 12px; }
</style>
</head>
<body>

<div class="card">
    <h2><?= htmlspecialchars($title) ?></h2>
    <p><strong>Precio:</strong> $<?= number_format($price, 2) ?> UYU</p>

    <button id="pay-now">💳 Pagar</button>

    <p class="note">Procesaremos tu pago ahora mismo y, en unos segundos, verás el comprobante con los detalles de la transacción</p>
</div>

<script>
const price = <?= json_encode($price) ?>;
const title = <?= json_encode($title) ?>;
const id_reserva = <?= json_encode($id_reserva) ?>;

document.getElementById('pay-now').addEventListener('click', async function() {
    try {
        const resp = await fetch('create_preference.php?title=' + encodeURIComponent(title) + '&price=' + encodeURIComponent(price));
        const data = await resp.json();
        const prefId = data && data.id ? data.id : ('SIM-' + Math.floor(Math.random()*1000000000));

        const params = new URLSearchParams({
            pref_id: prefId,
            price: price,
            title: title,
            id_reserva: id_reserva // se asegura de pasar id_reserva
        });

        window.location.href = 'confirmacion.php?' + params.toString();
    } catch (e) {
        console.error('create_preference falló:', e);
        const prefId = 'SIM-' + Math.floor(Math.random()*1000000000);
        const params = new URLSearchParams({
            pref_id: prefId,
            price: price,
            title: title,
            id_reserva: id_reserva
        });
        window.location.href = 'confirmacion.php?' + params.toString();
    }
});
</script>

</body>
</html>


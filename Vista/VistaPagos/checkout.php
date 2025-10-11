<?php
require_once 'mp_ngrok.php';
$item = getPaymentItem();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pagar con Mercado Pago</title>
<script src="https://sdk.mercadopago.com/js/v2"></script>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; text-align: center; padding-top: 50px; }
.card { background: #fff; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); padding: 40px; display: inline-block; width: 350px; }
button { background: #009ee3; border: none; color: white; padding: 12px 25px; font-size: 16px; border-radius: 50px; cursor: pointer; }
button:hover { background: #007bbf; }
</style>
</head>
<body>

<div class="card">
    <h2><?= htmlspecialchars($item['title']) ?></h2>
    <p><strong>Precio:</strong> $<?= number_format($item['unit_price'], 2) ?> UYU</p>
    <button id="checkout-btn">💳 Pagar con Mercado Pago</button>
</div>

<script>
const mp = new MercadoPago("<?= MP_PUBLIC_KEY ?>", { locale: 'es-UY' });

document.getElementById('checkout-btn').addEventListener('click', async () => {
    const response = await fetch("create_preference.php?title=<?= urlencode($item['title']) ?>&price=<?= $item['unit_price'] ?>");
    const pref = await response.json();

    if (!pref.id) {
        alert("Error al crear la preferencia de pago.");
        return;
    }

    // Checkout embebido: modal de tarjeta
    mp.checkout({
        preference: { id: pref.id },
        render: {
            container: 'body',
            label: 'Pagar',
        },
        autoOpen: true,
        onApprove: function(data) {
            // Redirige a confirmacion.php al aprobar
            window.location.href = "confirmacion.php?pref_id=" + pref.id + "&price=<?= $item['unit_price'] ?>&title=<?= urlencode($item['title']) ?>";
        },
        onError: function(err) {
            alert("Hubo un error procesando el pago.");
            console.error(err);
        }
    });
});
</script>

</body>
</html>

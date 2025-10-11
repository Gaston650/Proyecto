<?php
require_once 'mp_ngrok.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pago Exitoso</title>
<style>
body { font-family: Arial; text-align: center; padding-top: 80px; background: #e6ffe6; }
a { text-decoration: none; color: #009ee3; font-weight: bold; }
</style>
</head>
<body>
<h1>✅ Pago aprobado correctamente</h1>
<p>Gracias por tu compra.</p>
<p>ID de pago: <?= htmlspecialchars($_GET['payment_id'] ?? '') ?></p>
<a href="checkout.php">Volver</a>
</body>
</html>
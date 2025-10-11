<?php
// webhook.php - recibe las notificaciones de Mercado Pago
$body = file_get_contents("php://input");

// Guardamos en un log (útil para ver si Mercado Pago está notificando)
file_put_contents("webhook_log.txt", date("Y-m-d H:i:s") . " - " . $body . PHP_EOL, FILE_APPEND);

// Siempre responder 200 OK
http_response_code(200);
echo "OK";
?>
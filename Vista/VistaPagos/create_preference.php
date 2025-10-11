<?php
require_once 'mp_ngrok.php';
header('Content-Type: application/json');

$item = getPaymentItem();

// Solo datos necesarios para checkout embebido
$data = [
    "items" => [
        [
            "title" => $item['title'],
            "quantity" => $item['quantity'] ?? 1,
            "unit_price" => (float)$item['unit_price'],
            "currency_id" => "UYU"
        ]
    ],
    "payment_methods" => [
        "excluded_payment_types" => [
            ["id" => "ticket"]
        ],
        "installments" => 1
    ]
];

// Creamos la preferencia vía cURL
$ch = curl_init("https://api.mercadopago.com/checkout/preferences");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . MP_ACCESS_TOKEN
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

$preference = json_decode($response, true);

// Retornamos solo el ID de preferencia
echo json_encode([
    "id" => $preference["id"] ?? null
]);
?>
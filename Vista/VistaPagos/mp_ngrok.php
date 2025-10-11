<?php
// mp_ngrok.php
// Configuración base para Mercado Pago con URL pública de ngrok

session_start();

// 🌐 URL pública (ngrok)
define('NGROK_URL', 'https://cagily-unaggressive-shaquita.ngrok-free.dev');

// ⚙️ Credenciales del comprador (modo prueba)
define('MP_PUBLIC_KEY', 'APP_USR-378ee3b6-6cce-43ec-a88d-8eefdb01e5d3');
define('MP_ACCESS_TOKEN', 'APP_USR-3714904521647037-101018-083b531381481c3f5455d92ac04763e2-2917962498');

// Función para construir URL absolutas hacia tus scripts
function mp_url($path) {
    return NGROK_URL . '/Vista/VistaPagos/' . ltrim($path, '/');
}

// Función para obtener el ítem a cobrar
function getPaymentItem() {
    $title = isset($_GET['title']) ? $_GET['title'] : 'Servicio ClickSoft';
    $price = isset($_GET['price']) ? floatval($_GET['price']) : 100.00;

    return [
        'title' => $title,
        'quantity' => 1,
        'unit_price' => $price
    ];
}
?>
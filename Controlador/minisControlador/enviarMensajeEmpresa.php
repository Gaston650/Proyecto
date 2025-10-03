<?php
session_start();
require_once __DIR__ . '/../../Modelo/modeloMensaje.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Debes iniciar sesión como empresa.");
    exit();
}

$id_emisor = $_SESSION['user_id']; // empresa
$tipo_emisor = 'empresa';
$id_receptor = isset($_POST['id_cliente']) ? (int)$_POST['id_cliente'] : null; // cliente
$tipo_receptor = 'usuario';
$id_reserva = isset($_POST['id_reserva']) ? (int)$_POST['id_reserva'] : null;
$contenido = trim($_POST['contenido'] ?? '');

if ($id_emisor && $id_receptor && $id_reserva && $contenido !== '') {
    $modelo = new modeloMensaje();
    $modelo->insertarMensaje($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva, $contenido);
    header("Location: ../../Vista/VistaMensajes/mensajeEmpresa.php?cliente=$id_receptor&reserva=$id_reserva");
    exit();
} else {
    header("Location: ../../Vista/VistaMensajes/mensajeEmpresa.php?cliente=$id_receptor&reserva=$id_reserva&error=1");
    exit();
}
?>
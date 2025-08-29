<?php
require_once __DIR__ . '/superControlador/superControlador.php';

$controlador = new cerrarSesionControladorWrapper();
if ($controlador->cerrarSesion()) {
    header("Location: ../index.php"); 
    exit();
}
?>

<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';
require_once __DIR__ . '/../../conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

$conexion = new conexion();
$conn = $conexion->conectar();

$promocionWrapper = new promocionControladorWrapper($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_promocion = $_POST['id_promocion'] ?? null;

    if ($id_promocion) {
        $resultado = $promocionWrapper->eliminar($id_promocion);

        if ($resultado) {
            header("Location: ../../Vista/VistaPromociones/promocionesEmpresa.php?success=Promoción eliminada correctamente");
        } else {
            header("Location: ../../Vista/VistaPromociones/promocionesEmpresa.php?error=No se pudo eliminar la promoción");
        }
    } else {
        header("Location: ../../Vista/VistaPromociones/promocionesEmpresa.php?error=ID de promoción inválido");
    }
    exit();
} else {
    header("Location: ../../Vista/VistaPromociones/promocionesEmpresa.php");
    exit();
}

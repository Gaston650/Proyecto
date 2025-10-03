<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';
require_once __DIR__ . '/../../conexion.php';

// Validar sesión
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}

// Crear conexión
$conexion = new conexion();
$conn = $conexion->conectar();

// Instanciar wrapper
$promocionWrapper = new promocionControladorWrapper($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? 'crear'; // por defecto crear

    // Usamos el mismo name para ambos formularios (crear y editar)
    $id_servicio = $_POST['id_servicio'];
    $porcentaje = (int) $_POST['porcentaje']; // <-- clave correcta
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $condiciones = $_POST['condiciones'] ?? '';

    switch ($accion) {
        case 'crear':
            $resultado = $promocionWrapper->crear($id_servicio, $porcentaje, $fecha_inicio, $fecha_fin, $condiciones);
            break;

        case 'editar':
            $id_promocion = $_POST['id_promocion'];
            $resultado = $promocionWrapper->editar($id_promocion, $porcentaje, $fecha_inicio, $fecha_fin, $condiciones);
            break;

        case 'eliminar':
            $id_promocion = $_POST['id_promocion'];
            $resultado = $promocionWrapper->eliminar($id_promocion);
            break;

        default:
            $resultado = false;
            break;
    }

    if ($resultado) {
        header("Location: ../../Vista/VistaPromociones/promocionesEmpresa.php?success=Operación realizada correctamente");
        exit();
    } else {
        header("Location: ../../Vista/VistaPromociones/promocionesEmpresa.php?error=No se pudo completar la operación");
        exit();
    }
}
?>

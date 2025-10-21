<?php
$modulo = $_GET['modulo'] ?? 'usuarios';

switch ($modulo) {
    case 'usuarios':
        include __DIR__ . '/modulos/gestionUsuarios.php';
        break;
    case 'servicios':
        include __DIR__ . '/modulos/gestionServicios.php';
        break;
    case 'categorias':
        include __DIR__ . '/modulos/gestionCategorias.php';
        break;
    case 'reportes':
        include __DIR__ . '/modulos/gestionReportes.php';
        break;
    case 'historial':
        include __DIR__ . '/modulos/gestionHistorial.php';
        break;
    default:
        echo "<h3>Seleccione un módulo desde el menú lateral.</h3>";
}
?>



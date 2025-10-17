<?php
require_once __DIR__ . '/controladorResena.php';

if (isset($_GET['id_servicio'])) {
    $id_servicio = intval($_GET['id_servicio']);
    $controlador = new controladorResena();
    $reseñas = $controlador->obtenerPorServicio($id_servicio);
    echo json_encode($reseñas);
} else {
    echo json_encode([]);
}
?>
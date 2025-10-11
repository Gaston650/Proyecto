<?php
function procesarMensajesEmpresa(array $mensajes) {
    $resultado = [];
    foreach ($mensajes as $m) {
        $cliente_id = $m['id_emisor'];
        if ($m['tipo_emisor'] === 'empresa') $cliente_id = $m['id_receptor'];

        if (!isset($resultado[$cliente_id])) {
            $resultado[$cliente_id] = [
                'id_reserva' => $m['id_reserva'],
                'nombre_cliente' => $m['nombre_cliente'] ?? 'Cliente',
                'ultimo_mensaje' => $m['contenido'],
                'fecha_envio' => $m['fecha_envio'],
                'no_leidos' => 0
            ];
        }

        // Actualiza último mensaje si es más reciente
        if (strtotime($m['fecha_envio']) > strtotime($resultado[$cliente_id]['fecha_envio'])) {
            $resultado[$cliente_id]['ultimo_mensaje'] = $m['contenido'];
            $resultado[$cliente_id]['fecha_envio'] = $m['fecha_envio'];
        }

        // Contar no leídos
        if ($m['leido'] == 0 && $m['tipo_receptor'] === 'empresa') {
            $resultado[$cliente_id]['no_leidos']++;
        }
    }
    return $resultado;
}
?>

<?php
function procesarMensajes($mensajes_raw) {
    $mensajes = [];

    if($mensajes_raw) {
        while($m = $mensajes_raw->fetch_assoc()) {
            // Determinar el id del cliente (usuario)
            if ($m['tipo_emisor'] === 'usuario') {
                $cliente_id = $m['id_emisor'];
            } elseif ($m['tipo_receptor'] === 'usuario') {
                $cliente_id = $m['id_receptor'];
            } else {
                continue; // Si no hay usuario, ignora este mensaje
            }

            $id_reserva = $m['id_reserva'];

            if(!isset($mensajes[$cliente_id])) {
                $mensajes[$cliente_id] = [
                    'ultimo_mensaje' => $m['contenido'],
                    'fecha_envio' => $m['fecha_envio'],
                    'nombre_cliente' => $m['nombre_cliente'],
                    'no_leidos' => $m['leido'] ? 0 : 1,
                    'id_reserva' => $id_reserva
                ];
            } else {
                if(!$m['leido']) $mensajes[$cliente_id]['no_leidos']++;
                if(strtotime($m['fecha_envio']) > strtotime($mensajes[$cliente_id]['fecha_envio'])) {
                    $mensajes[$cliente_id]['ultimo_mensaje'] = $m['contenido'];
                    $mensajes[$cliente_id]['fecha_envio'] = $m['fecha_envio'];
                    $mensajes[$cliente_id]['id_reserva'] = $id_reserva;
                }
            }
        }
    }

    return $mensajes;
}
?>
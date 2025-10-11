<?php
function procesarMensajesCliente(array $mensajes_raw) {
    $mensajes = [];

    foreach ($mensajes_raw as $row) {
        // Determinar el id de la empresa (emisor o receptor)
        if ($row['tipo_emisor'] === 'empresa') {
            $empresa_id = $row['id_emisor'];
            $nombre_empresa = $row['nombre_empresa'] ?? 'Empresa desconocida';
        } elseif ($row['tipo_receptor'] === 'empresa') {
            $empresa_id = $row['id_receptor'];
            $nombre_empresa = $row['nombre_empresa'] ?? 'Empresa desconocida';
        } else {
            continue; // Ignorar mensajes sin empresa
        }

        // Inicializar si no existe
        if (!isset($mensajes[$empresa_id])) {
            $mensajes[$empresa_id] = [
                'id_reserva' => $row['id_reserva'],
                'nombre_empresa' => $nombre_empresa,
                'ultimo_mensaje' => $row['contenido'],
                'fecha_envio' => $row['fecha_envio'],
                'no_leidos' => ($row['leido'] == 0) ? 1 : 0
            ];
        } else {
            // Mantener el último mensaje según fecha
            if (strtotime($row['fecha_envio']) > strtotime($mensajes[$empresa_id]['fecha_envio'])) {
                $mensajes[$empresa_id]['ultimo_mensaje'] = $row['contenido'];
                $mensajes[$empresa_id]['fecha_envio'] = $row['fecha_envio'];
            }

            // Contar mensajes no leídos
            if ($row['leido'] == 0) $mensajes[$empresa_id]['no_leidos']++;
        }
    }

    return $mensajes;
}
?>

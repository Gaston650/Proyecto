<?php
// Instanciamos el wrapper
$wrapper = new adminControladorWrapper();

// Obtenemos el historial
$historial = $wrapper->obtenerHistorial();
?>

<h2>Historial de Moderación</h2>

<?php if (!empty($historial) && is_array($historial)): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID Reserva</th>
                <th>Servicio</th>
                <th>Proveedor</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historial as $h): ?>
                <tr>
                    <td><?= htmlspecialchars($h['id_reserva'] ?? 'Desconocido'); ?></td>
                    <td><?= htmlspecialchars($h['titulo_servicio'] ?? 'Servicio eliminado'); ?></td>
                    <td><?= htmlspecialchars($h['proveedor'] ?? 'Desconocido'); ?></td>
                    <td><?= htmlspecialchars($h['cliente'] ?? 'Desconocido'); ?></td>
                    <td><?= htmlspecialchars($h['estado_reserva'] ?? 'Desconocido'); ?></td>
                    <td><?= htmlspecialchars($h['fecha_reserva'] ?? 'Desconocido'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay registros en el historial.</p>
<?php endif; ?>

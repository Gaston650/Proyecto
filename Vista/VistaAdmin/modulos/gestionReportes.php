    <?php
    $wrapper = new adminControladorWrapper();
    $reportes = $wrapper->obtenerReportes();

    // Mostrar mensaje si viene por GET
    if (isset($_GET['mensaje'])) {
        if ($_GET['mensaje'] === 'ok') {
            echo "<div class='alert alert-success'>✅ El reporte fue actualizado correctamente.</div>";
        } elseif ($_GET['mensaje'] === 'error') {
            echo "<div class='alert alert-error'>❌ Ocurrió un error al actualizar el reporte.</div>";
        }
    }
    ?>

    <h2>Gestión de Reportes de Servicios</h2>

    <?php if (!empty($reportes)): ?>
    <table class="tabla-reportes">
        <thead>
            <tr>
                <th>ID</th>
                <th>Servicio</th>
                <th>Reportante</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reportes as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['id_reporte']); ?></td>
                <td><?= htmlspecialchars($r['servicio'] ?? 'Servicio eliminado'); ?></td>
                <td><?= htmlspecialchars($r['usuario_reportante'] ?? 'Desconocido'); ?></td>
                <td><?= htmlspecialchars($r['motivo']); ?></td>
                <td>
                    <?php if($r['estado'] === 'pendiente'): ?>
                        <span class="estado pendiente">Pendiente</span>
                    <?php else: ?>
                        <span class="estado resuelto">Resuelto</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($r['fecha_reporte']); ?></td>
                <td>
                    <?php if($r['estado'] === 'pendiente'): ?>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="id_reporte" value="<?= $r['id_reporte']; ?>">
                            <input type="hidden" name="accion" value="resuelto">
                            <button type="submit" class="btn-resuelto">Marcar como resuelto</button>
                        </form>
                    <?php else: ?>
                        <span class="resuelto-text">✔</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No hay reportes.</p>
    <?php endif; ?>

    <?php
    // Procesar acción de resolver
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reporte'], $_POST['accion'])) {
        $resultado = $wrapper->gestionarReporte((int)$_POST['id_reporte'], $_POST['accion']);

        if ($resultado) {
            header("Location: dashboard.php?modulo=reportes&mensaje=ok");
        } else {
            header("Location: dashboard.php?modulo=reportes&mensaje=error");
        }
        exit;
    }
    ?>

    <!-- Estilos CSS -->
    <style>
    /* Tabla */
    .tabla-reportes {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-family: Arial, sans-serif;
    }

    .tabla-reportes th, .tabla-reportes td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .tabla-reportes th {
        background-color: #343a40;
        color: white;
    }

    /* Estados */
    .estado.pendiente {
        background-color: #ffc107;
        color: #212529;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: bold;
    }

    .estado.resuelto {
        background-color: #28a745;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: bold;
    }

    /* Botón resuelto limpio y moderno */
    .btn-resuelto {
        background-color: #007bff;
        color: white;
        border: none;
        outline: none;
        padding: 6px 12px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s, transform 0.1s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .btn-resuelto:hover {
        background-color: #0056b3;
        transform: translateY(-1px);
    }

    .btn-resuelto:active {
        transform: translateY(0);
    }

    .btn-resuelto:focus {
        outline: none;
    }

    /* Alertas */
    .alert {
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        font-weight: bold;
        font-family: Arial, sans-serif;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
    }

    /* Texto Resuelto */
    .resuelto-text {
        color: #28a745;
        font-weight: bold;
        font-size: 1.1em;
    }
    </style>

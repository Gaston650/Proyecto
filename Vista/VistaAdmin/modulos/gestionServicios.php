<?php
// Asegurarse de que $wrapper ya esté instanciado
if (!isset($wrapper)) {
    echo "<p>Error: controlador no instanciado.</p>";
    return;
}

// Obtener listado de servicios
$servicios = $wrapper->obtenerServicios();

// Obtener listado de categorías desde la tabla
$categoriasBD = $wrapper->obtenerCategorias();

// Obtener listado de empresas proveedoras
$empresas = $wrapper->obtenerEmpresas();

// Filtros
$filtroCategoria = $_GET['categoria'] ?? '';
$filtroEstado = $_GET['estado'] ?? '';

$serviciosFiltrados = array_filter($servicios, function($s) use ($filtroCategoria, $filtroEstado) {
    $matchCategoria = $filtroCategoria === '' || $s['categoria'] === $filtroCategoria;
    $matchEstado = $filtroEstado === '' || $s['estado'] === $filtroEstado;
    return $matchCategoria && $matchEstado;

    
});


?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<?php
$mensaje = $_GET['mensaje'] ?? '';
$error = $_GET['error'] ?? '';

if ($mensaje): ?>
    <div class="alert alert-success text-center" role="alert">
        <?= htmlspecialchars($mensaje); ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger text-center" role="alert">
        <?= htmlspecialchars($error); ?>
    </div>
<?php endif; ?>


<div class="gestion-servicios container mt-4">
    <!-- Filtros -->
    <form method="GET" class="mb-3">
        <input type="hidden" name="modulo" value="servicios">
        <label>Categoría:
            <select name="categoria" class="form-select d-inline-block w-auto">
                <option value="">Todas</option>
                <?php foreach ($categoriasBD as $cat): 
                    $selected = ($cat['nombre_categoria'] === $filtroCategoria) ? 'selected' : '';
                ?>
                    <option value="<?= htmlspecialchars($cat['nombre_categoria']); ?>" <?= $selected; ?>>
                        <?= htmlspecialchars($cat['nombre_categoria']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="ms-2">Estado:
            <select name="estado" class="form-select d-inline-block w-auto">
                <option value="">Todos</option>
                <option value="activo" <?= $filtroEstado==='activo' ? 'selected' : ''; ?>>Activo</option>
                <option value="pendiente" <?= $filtroEstado==='pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="rechazado" <?= $filtroEstado==='rechazado' ? 'selected' : ''; ?>>Rechazado</option>
            </select>
        </label>

        <button type="submit" class="btn btn-primary ms-2">Filtrar</button>
    </form>

    <!-- Tabla de servicios -->
    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Proveedor</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($serviciosFiltrados)): ?>
                <?php foreach ($serviciosFiltrados as $servicio): ?>
                    <tr>
                        <td><?= htmlspecialchars($servicio['id_servicio']); ?></td>
                        <td><?= htmlspecialchars($servicio['titulo']); ?></td>
                        <td><?= htmlspecialchars($servicio['proveedor']); ?></td>
                        <td><?= htmlspecialchars($servicio['categoria']); ?></td>
                        <td><?= htmlspecialchars($servicio['precio']); ?></td>
                        <td><?= htmlspecialchars($servicio['estado']); ?></td>
                        <td>
                            <button 
                                class="btn btn-sm btn-warning btn-editar-servicio"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarServicio"
                                data-id="<?= $servicio['id_servicio']; ?>"
                                data-titulo="<?= htmlspecialchars($servicio['titulo']); ?>"
                                data-descripcion="<?= htmlspecialchars($servicio['descripcion']); ?>"
                                data-precio="<?= htmlspecialchars($servicio['precio']); ?>"
                                data-categoria="<?= htmlspecialchars($servicio['categoria']); ?>"
                                data-estado="<?= htmlspecialchars($servicio['estado']); ?>"
                            >
                                Editar
                            </button>

                            <button 
                                class="btn btn-sm btn-danger btn-eliminar-servicio"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEliminarServicio"
                                data-id="<?= $servicio['id_servicio']; ?>">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No hay servicios para mostrar</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Botón para crear servicio -->
    <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#modalCrearServicio">
        Crear Servicio
    </button>
</div>

<!-- MODAL CREAR SERVICIO -->
<div class="modal fade" id="modalCrearServicio" tabindex="-1" aria-labelledby="modalCrearServicioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="/ClickSoft/Controlador/minisControlador/crearServicio.php" enctype="multipart/form-data">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalCrearServicioLabel">Crear Servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <!-- Selección de empresa desde empresas_proveedor -->
        <div class="mb-3">
          <label class="form-label">Empresa:</label>
        <select class="form-select" name="id_empresa" required>
    <option value="">Seleccione una empresa</option>
    <?php
    $empresasProveedor = $wrapper->obtenerEmpresasProveedor();
    foreach ($empresasProveedor as $empresa) {
        echo "<option value='".htmlspecialchars($empresa['id_usuario'])."'>".htmlspecialchars($empresa['nombre'])."</option>";
    }
    ?>
</select>

        </div>

        <div class="mb-3">
          <label class="form-label">Título:</label>
          <input type="text" class="form-control" name="titulo" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Descripción:</label>
          <textarea class="form-control" name="descripcion" rows="3" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Precio (UYU):</label>
          <input type="number" class="form-control" name="precio" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Categoría:</label>
          <select class="form-select" name="categoria" required>
            <option value="">Seleccione una categoría</option>
            <?php foreach ($categoriasBD as $cat): ?>
              <option value="<?= htmlspecialchars($cat['nombre_categoria']); ?>">
                <?= htmlspecialchars($cat['nombre_categoria']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Imagen:</label>
          <input type="file" class="form-control" name="imagen">
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Crear</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EDITAR SERVICIO -->
<div class="modal fade" id="modalEditarServicio" tabindex="-1" aria-labelledby="modalEditarServicioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="/ClickSoft/Controlador/minisControlador/editarServicio.php" enctype="multipart/form-data">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="modalEditarServicioLabel">Editar Servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id_servicio" id="edit-servicio-id">
        <div class="mb-3">
          <label class="form-label">Título:</label>
          <input type="text" class="form-control" id="edit-servicio-titulo" name="titulo" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Descripción:</label>
          <textarea class="form-control" id="edit-servicio-descripcion" name="descripcion" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Precio (UYU):</label>
          <input type="number" class="form-control" id="edit-servicio-precio" name="precio" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Categoría:</label>
          <select class="form-select" id="edit-servicio-categoria" name="categoria" required>
            <?php foreach ($categoriasBD as $cat): ?>
              <option value="<?= htmlspecialchars($cat['nombre_categoria']); ?>">
                <?= htmlspecialchars($cat['nombre_categoria']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Estado:</label>
          <select class="form-select" id="edit-servicio-estado" name="estado">
            <option value="disponible">Disponible</option>
            <option value="no-disponible">No disponible</option>
            <option value="pendiente">Pendiente</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Actualizar Imagen (opcional):</label>
          <input type="file" class="form-control" name="imagen">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-warning">Guardar Cambios</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL ELIMINAR SERVICIO -->
<div class="modal fade" id="modalEliminarServicio" tabindex="-1" aria-labelledby="modalEliminarServicioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="/ClickSoft/Controlador/minisControlador/eliminarServicio.php">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarServicioLabel">Eliminar Servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>¿Estás seguro de que deseas eliminar este servicio?</p>
        <input type="hidden" name="id_servicio" id="eliminar-servicio-id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger">Eliminar</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Pasar datos al modal de editar
  document.querySelectorAll('.btn-editar-servicio').forEach(btn => {
    btn.addEventListener('click', function() {
      document.getElementById('edit-servicio-id').value = this.dataset.id;
      document.getElementById('edit-servicio-titulo').value = this.dataset.titulo;
      document.getElementById('edit-servicio-descripcion').value = this.dataset.descripcion;
      document.getElementById('edit-servicio-precio').value = this.dataset.precio;
      document.getElementById('edit-servicio-categoria').value = this.dataset.categoria;

      // Seleccionar estado correctamente
      const estadoSelect = document.getElementById('edit-servicio-estado');
      const estadoActual = this.dataset.estado || 'disponible';
      estadoSelect.value = estadoActual;
    });
  });

  // Pasar id al modal de eliminar
  document.querySelectorAll('.btn-eliminar-servicio').forEach(btn => {
    btn.addEventListener('click', function() {
      document.getElementById('eliminar-servicio-id').value = this.dataset.id;
    });
  });
</script>
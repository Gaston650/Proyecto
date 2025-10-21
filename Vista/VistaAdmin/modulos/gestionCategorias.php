<?php
// adminControladorWrapper ya instanciado como $wrapper

// Crear nueva categoría
if (isset($_POST['crear_categoria'])) {
    $nombre = trim($_POST['nombre_categoria']);
    if ($nombre) {
        $wrapper->crearCategoria(['nombre_categoria' => $nombre]);
        echo "<p class='success'>Categoría creada correctamente.</p>";
    }
}

// Editar categoría
if (isset($_POST['editar_categoria'])) {
    $id = intval($_POST['id_categoria']);
    $nombre = trim($_POST['nombre_categoria']);
    if ($id && $nombre) {
        $wrapper->editarCategoria($id, ['nombre_categoria' => $nombre]);
        echo "<p class='success'>Categoría editada correctamente.</p>";
    }
}

// Eliminar categoría
if (isset($_POST['eliminar_categoria'])) {
    $id = intval($_POST['id_categoria']);
    if ($id) {
        $wrapper->eliminarCategoria($id);
        echo "<p class='success'>Categoría eliminada correctamente.</p>";
    }
}

// Obtener todas las categorías
$categorias = $wrapper->obtenerCategorias();
?>

<!-- ✅ LINKS BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<h2 class="mt-4 mb-3 text-center">Gestión de Categorías</h2>

<!-- Formulario para crear categoría -->
<form method="POST" class="form-categoria mb-4 d-flex justify-content-center gap-2">
    <input type="text" name="nombre_categoria" placeholder="Nombre de la categoría" required class="form-control w-50">
    <button type="submit" name="crear_categoria" class="btn btn-primary">Crear Categoría</button>
</form>

<!-- Tabla de categorías -->
<div class="container">
    <table class="table table-striped table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td><?= $cat['id_categoria'] ?></td>
                        <td><?= htmlspecialchars($cat['nombre_categoria']) ?></td>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id_categoria" value="<?= $cat['id_categoria'] ?>">
                                <input type="text" name="nombre_categoria" placeholder="Nuevo nombre" required class="form-control d-inline-block w-auto">
                                <button type="submit" name="editar_categoria" class="btn btn-warning btn-sm">Editar</button>
                            </form>

                            <!-- Botón que abre el modal -->
                            <button type="button"
                                class="btn btn-danger btn-sm ms-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEliminarCategoria"
                                data-id="<?= $cat['id_categoria'] ?>"
                                data-nombre="<?= htmlspecialchars($cat['nombre_categoria']) ?>">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No hay categorías registradas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="modalEliminarCategoria" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Seguro que deseas eliminar la categoría <strong id="nombreCategoriaEliminar"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" id="formEliminarCategoria" style="display:inline-block;">
                    <input type="hidden" name="id_categoria" id="inputIdEliminar">
                    <button type="submit" name="eliminar_categoria" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ✅ SCRIPTS BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para pasar los datos al modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEliminar = document.getElementById('modalEliminarCategoria');
    modalEliminar.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');

        document.getElementById('inputIdEliminar').value = id;
        document.getElementById('nombreCategoriaEliminar').textContent = nombre;
    });
});
</script>


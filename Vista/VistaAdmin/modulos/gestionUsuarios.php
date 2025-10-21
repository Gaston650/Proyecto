<?php
// Instanciar wrapper si no está hecho
if (!isset($wrapper)) {
    $wrapper = new adminControladorWrapper();
}

// Mostrar mensaje si existe
$mensaje = $_GET['mensaje'] ?? '';

// Obtener listado de usuarios
$usuarios = $wrapper->obtenerUsuarios();

// Filtros
$filtroRol = $_GET['rol'] ?? '';
$filtroEstado = $_GET['estado'] ?? '';

$usuariosFiltrados = array_filter($usuarios, function($u) use ($filtroRol, $filtroEstado) {
    $matchRol = $filtroRol === '' || $u['tipo_usuario'] === $filtroRol;
    $matchEstado = $filtroEstado === '' || $u['estado'] === $filtroEstado;
    return $matchRol && $matchEstado;
});
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="gestion-usuarios container mt-4">
    <h2>Gestión de Usuarios</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <form method="GET" class="mb-3">
        <input type="hidden" name="modulo" value="usuarios">
        <label>Rol:
            <select name="rol" id="filtroRol" class="form-select d-inline-block w-auto">
                <option value="">Todos</option>
                <option value="cliente" <?= $filtroRol==='cliente' ? 'selected' : ''; ?>>Cliente</option>
                <option value="empresa" <?= $filtroRol==='empresa' ? 'selected' : ''; ?>>Empresa</option>
                <option value="administrador" <?= $filtroRol==='administrador' ? 'selected' : ''; ?>>Administrador</option>
            </select>
        </label>

        <label class="ms-2">Estado:
             <select name="estado" class="form-select d-inline-block w-auto">
                <option value="">Todos</option>
                <option value="activo" <?= $filtroEstado==='activo' ? 'selected' : ''; ?>>Activo</option>
                <option value="inactivo" <?= $filtroEstado==='inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                <option value="bloqueado" <?= $filtroEstado==='bloqueado' ? 'selected' : ''; ?>>Bloqueado</option>
            </select>
        </label>

        <button type="submit" class="btn btn-primary ms-2">Filtrar</button>
    </form>

    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Extras / Logo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($usuariosFiltrados)): ?>
                <?php foreach ($usuariosFiltrados as $usuario): ?>
                    <?php
                        $isEmpresa = $usuario['tipo_usuario'] === 'empresa';
                        $id = $usuario['id_usuario'];
                        $nombre = $usuario['nombre'] ?? '-';
                        $email = $usuario['email'] ?? '-';
                        $estado = $usuario['estado'] ?? '-';
                        $rut = $usuario['rut'] ?? '-';
                        $zona = $usuario['zona_cobertura'] ?? '-';
                        $logo = $usuario['logo'] ?? '';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($id); ?></td>
                        <td><?= htmlspecialchars($nombre); ?></td>
                        <td><?= htmlspecialchars($email); ?></td>
                        <td><?= $isEmpresa ? 'Empresa' : htmlspecialchars($usuario['tipo_usuario']); ?></td>
                        <td><?= htmlspecialchars($estado); ?></td>
                        <td>
                            <?php if($isEmpresa): ?>
                                RUT: <?= htmlspecialchars($rut) ?><br>
                                Zona: <?= htmlspecialchars($zona) ?><br>
                                <img src="<?= $logo ? "/ClickSoft/IMG/empresas/".htmlspecialchars($logo) : "/ClickSoft/IMG/perfil-vacio.png"; ?>" alt="Logo" style="height:40px; object-fit:cover; border-radius:5px;">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <button 
                                class="btn btn-sm btn-warning btn-editar"
                                data-bs-toggle="modal"
                                data-bs-target="<?= $isEmpresa ? '#modalEditarEmpresa' : '#modalEditarUsuario' ?>"
                                data-id="<?= $id; ?>"
                                data-nombre="<?= htmlspecialchars($nombre); ?>"
                                data-email="<?= htmlspecialchars($email); ?>"
                                data-tipo="<?= htmlspecialchars($usuario['tipo_usuario']); ?>"
                                data-estado="<?= htmlspecialchars($estado); ?>"
                                data-rut="<?= htmlspecialchars($rut); ?>"
                                data-zona="<?= htmlspecialchars($zona); ?>"
                            >
                                Editar
                            </button>

                            <button 
                                class="btn btn-sm btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEliminar"
                                data-id="<?= $id; ?>">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No hay usuarios para mostrar</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#modalCrear">
        Crear Usuario
    </button>
</div>

<!-- MODAL EDITAR USUARIO -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="/ClickSoft/Controlador/minisControlador/editarUsuario.php">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id_usuario" id="edit-id">
        <div class="mb-3">
          <label for="edit-nombre" class="form-label">Nombre:</label>
          <input type="text" class="form-control" id="edit-nombre" name="nombre" required>
        </div>
        <div class="mb-3">
          <label for="edit-email" class="form-label">Email:</label>
          <input type="email" class="form-control" id="edit-email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="edit-tipo" class="form-label">Rol:</label>
          <select class="form-select" id="edit-tipo" name="tipo_usuario">
            <option value="cliente">Cliente</option>
            <option value="administrador">Administrador</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="edit-estado" class="form-label">Estado:</label>
          <select class="form-select" id="edit-estado" name="estado">
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
            <option value="bloqueado">Bloqueado</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-warning">Guardar Cambios</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EDITAR EMPRESA -->
<div class="modal fade" id="modalEditarEmpresa" tabindex="-1" aria-labelledby="modalEditarEmpresaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="/ClickSoft/Controlador/minisControlador/editarEmpresa.php">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="modalEditarEmpresaLabel">Editar Empresa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Campos ocultos -->
        <input type="hidden" name="id_usuario" id="edit-empresa-id">
        <input type="hidden" name="tipo_usuario" value="empresa">

        <!-- Nombre de la empresa -->
        <div class="mb-3">
          <label for="edit-empresa-nombre" class="form-label">Nombre de la Empresa:</label>
          <input type="text" class="form-control" id="edit-empresa-nombre" name="nombre" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label for="edit-empresa-email" class="form-label">Email:</label>
          <input type="email" class="form-control" id="edit-empresa-email" name="email" required>
        </div>

        <!-- RUT -->
        <div class="mb-3">
          <label for="edit-empresa-rut" class="form-label">RUT:</label>
          <input type="text" class="form-control" id="edit-empresa-rut" name="rut">
        </div>

        <!-- Zona de cobertura -->
        <div class="mb-3">
          <label for="edit-empresa-zona" class="form-label">Zona de Cobertura:</label>
          <input type="text" class="form-control" id="edit-empresa-zona" name="zona_cobertura">
        </div>

        <!-- Estado -->
        <div class="mb-3">
          <label for="edit-empresa-estado" class="form-label">Estado:</label>
          <select class="form-select" id="edit-empresa-estado" name="estado">
            <option value="activo">Activo</option>
            <option value="suspendido">Suspendido</option>
          </select>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-warning">Guardar Cambios</button>
      </div>
    </form>
  </div>
</div>


<!-- MODAL CREAR USUARIO -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="/ClickSoft/Controlador/minisControlador/crearUsuario.php">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalCrearLabel">Crear Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="crear-nombre" class="form-label">Nombre:</label>
          <input type="text" class="form-control" id="crear-nombre" name="nombre" required>
        </div>
        <div class="mb-3">
          <label for="crear-email" class="form-label">Email:</label>
          <input type="email" class="form-control" id="crear-email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="crear-tipo" class="form-label">Rol:</label>
          <select class="form-select" id="crear-tipo" name="tipo_usuario" required>
            <option value="cliente">Cliente</option>
            <option value="empresa">Empresa</option>
            <option value="administrador">Administrador</option>
          </select>
        </div>
        <div class="mb-3" id="input-rut" style="display:none;">
          <label for="crear-rut" class="form-label">RUT:</label>
          <input type="text" class="form-control" id="crear-rut" name="rut">
        </div>
        <div class="mb-3">
          <label for="crear-estado" class="form-label">Estado:</label>
          <select class="form-select" id="crear-estado" name="estado" required>
            <option value="activo">Activo</option>
            <option value="suspendido">Suspendido</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="crear-password" class="form-label">Contraseña:</label>
          <input type="password" class="form-control" id="crear-password" name="contraseña" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Crear Usuario</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL ELIMINAR USUARIO -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="/ClickSoft/Controlador/minisControlador/eliminarUsuario.php">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarLabel">Eliminar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>¿Estás seguro de que deseas eliminar este usuario?</p>
        <input type="hidden" name="id_usuario" id="eliminar-id">
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
// Mostrar RUT solo si es Empresa en crear usuario
document.getElementById('crear-tipo').addEventListener('change', function() {
    document.getElementById('input-rut').style.display = this.value === 'empresa' ? 'block' : 'none';
});

// Botones Editar
document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', function() {
        let tipo = this.dataset.tipo;
        if(tipo === 'empresa') {
            document.getElementById('edit-empresa-id').value = this.dataset.id;
            document.getElementById('edit-empresa-nombre').value = this.dataset.nombre;
            document.getElementById('edit-empresa-email').value = this.dataset.email;
            document.getElementById('edit-empresa-rut').value = this.dataset.rut;
            document.getElementById('edit-empresa-zona').value = this.dataset.zona;
            document.getElementById('edit-empresa-estado').value = this.dataset.estado;
        } else {
            document.getElementById('edit-id').value = this.dataset.id;
            document.getElementById('edit-nombre').value = this.dataset.nombre;
            document.getElementById('edit-email').value = this.dataset.email;
            document.getElementById('edit-tipo').value = tipo;
            document.getElementById('edit-estado').value = this.dataset.estado;
        }
    });
});

</script>
<script src="modulos/modulosJS/modalEliminarUser.js"></script>
<script src="modulos/modulosJS/rut.js"></script>

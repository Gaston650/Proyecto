console.log("✅ modalEditarUser.js cargado correctamente");

document.addEventListener("DOMContentLoaded", function () {
  const filtroRol = document.getElementById("filtroRol");
  const botonesEditar = document.querySelectorAll(".btn-editar");

  if (!filtroRol) {
    console.error("❌ No se encontró el filtro de rol (id='filtroRol').");
    return;
  }

  if (botonesEditar.length === 0) {
    console.warn("⚠️ No se encontraron botones con clase .btn-editar.");
  }

  botonesEditar.forEach((btn) => {
    btn.addEventListener("click", function () {
      const id = this.dataset.id;
      const nombre = this.dataset.nombre;
      const email = this.dataset.email;
      const tipo = this.dataset.tipo;
      const estado = this.dataset.estado;
      const rut = this.dataset.rut;

      console.log("🟡 Editando usuario:", { id, nombre, tipo });

      const rolSeleccionado = filtroRol.value;

      if (rolSeleccionado === "empresa" || tipo === "empresa") {
        const modalEmpresaEl = document.getElementById("modalEditarEmpresa");
        if (!modalEmpresaEl) {
          console.error("❌ No existe el modal con id='modalEditarEmpresa'.");
          return;
        }

        document.getElementById("edit-empresa-id").value = id;
        document.getElementById("edit-empresa-nombre").value = nombre;
        document.getElementById("edit-empresa-email").value = email;
        document.getElementById("edit-empresa-rut").value = rut || "";
        document.getElementById("edit-empresa-estado").value = estado;

        new bootstrap.Modal(modalEmpresaEl).show();
      } else {
        const modalUsuarioEl = document.getElementById("modalEditarUsuario");
        if (!modalUsuarioEl) {
          console.error("❌ No existe el modal con id='modalEditarUsuario'.");
          return;
        }

        document.getElementById("edit-id").value = id;
        document.getElementById("edit-nombre").value = nombre;
        document.getElementById("edit-email").value = email;
        document.getElementById("edit-tipo").value = tipo;
        document.getElementById("edit-estado").value = estado;

        new bootstrap.Modal(modalUsuarioEl).show();
      }
    });
  });
});

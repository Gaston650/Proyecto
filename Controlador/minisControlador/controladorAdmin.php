<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Modelo/modeloAdmin.php';
require_once __DIR__ . '/../../Modelo/modeloHistorial.php';

class ControladorAdmin {
    private $modeloAdmin;
    private $modeloHistorial;

    public function __construct() {
        $this->modeloAdmin = new ModeloAdmin();
        $this->modeloHistorial = new HistorialModelo();
    }

    // --- Usuarios ---
    public function obtenerUsuarios() {
        $usuarios = $this->modeloAdmin->obtenerUsuarios();
        $empresas = $this->modeloAdmin->obtenerEmpresas();

        return array_merge($usuarios, $empresas);
    }

    public function obtenerEmpresas() {
        return $this->modeloAdmin->obtenerEmpresas();
    }

    public function cambiarRol($id, $rol) {
        return $this->modeloAdmin->cambiarRol($id, $rol);
    }

    public function eliminarUsuario($id) {
        try {
            return $this->modeloAdmin->eliminarUsuarioPorId($id);
        } catch (Exception $e) {
            error_log("❌ Error en eliminarUsuario: " . $e->getMessage());
            return false;
        }
    }

    public function crearUsuario($data) {
        return $this->modeloAdmin->crearUsuario($data);
    }

    public function editarUsuario($id, $data) {
        $data['rut'] = $data['rut'] ?? '';
        $data['zona_cobertura'] = $data['zona_cobertura'] ?? '';
        $data['logo'] = $data['logo'] ?? '';

        return $this->modeloAdmin->editarUsuario($id, $data);
    }

    // --- Servicios ---
    public function obtenerServicios() {
        return $this->modeloAdmin->obtenerServicios();
    }

    public function crearServicio($id_empresa, $titulo, $descripcion, $precio, $categoria, $imagen = null) {
        return $this->modeloAdmin->crearServicio($id_empresa, $titulo, $descripcion, $precio, $categoria, $imagen);
    }

    public function editarServicio($id, $data) {
        $titulo = $data['titulo'] ?? '';
        $descripcion = $data['descripcion'] ?? '';
        $ubicacion = $data['ubicacion'] ?? '';
        $precio = $data['precio'] ?? 0;
        $categoria = $data['categoria'] ?? '';
        $estado = $data['estado'] ?? 'disponible';
        $imagen = $data['imagen'] ?? null;

        return $this->modeloAdmin->editarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $categoria, $estado, $imagen);
    }

    public function eliminarServicio($id) {
        return $this->modeloAdmin->eliminarServicio($id);
    }

    public function obtenerEmpresaPorUsuario($id_usuario) {
        return $this->modeloAdmin->obtenerEmpresaPorUsuario($id_usuario);
    }

    public function obtenerEmpresasProveedor() {
        return $this->modeloAdmin->obtenerEmpresas();
    }

    // --- Categorías ---
    public function obtenerCategorias() {
        return $this->modeloAdmin->obtenerCategorias();
    }

    public function crearCategoria($data) {
        return $this->modeloAdmin->crearCategoria($data);
    }

    public function editarCategoria($id, $data) {
        return $this->modeloAdmin->editarCategoria($id, $data);
    }

    public function eliminarCategoria($id) {
        return $this->modeloAdmin->eliminarCategoria($id);
    }

    // --- Historial ---
    public function obtenerHistorial() {
        return $this->modeloHistorial->obtenerHistorialCompleto();
    }

    // --- Reportes ---
    public function obtenerReportes() {
        return $this->modeloAdmin->obtenerReportes();
    }

    public function gestionarReporte($id_reporte, $accion) {
        return $this->modeloAdmin->gestionarReporte($id_reporte, $accion);
    }
}
?>


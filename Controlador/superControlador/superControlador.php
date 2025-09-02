<?php
require_once __DIR__ . '/../minisControlador/usuarioControlador.php';
require_once __DIR__ . '/../minisControlador/controladorEmpresa.php';
require_once __DIR__ . '/../minisControlador/controladorSesion.php';
require_once __DIR__ . '/../minisControlador/controladorCerrarSesion.php';
require_once __DIR__ . '/../minisControlador/controladorPerfil.php';
require_once __DIR__ . '/../minisControlador/controladorPerfilEmpresa.php';
require_once __DIR__ . '/../minisControlador/controladorServicio.php';
require_once __DIR__ . '/../minisControlador/controladorFavorito.php'; 
require_once __DIR__ . '/../minisControlador/controladorReservas.php';
require_once __DIR__ . '/../minisControlador/controladorHistorial.php';  


class usuarioControladorWrapper {
    private $controlador;
    public function __construct() {
        $this->controlador = new usuarioControlador();
    }
    public function registrar($nombre, $email, $password) {
        return $this->controlador->guardarUsuario($nombre, $email, $password);
    }
}

class empresaControladorWrapper {
    private $controlador;
    public function __construct() {
        $this->controlador = new empresaControlador();
    }
    public function registrar($nombre, $email, $zona, $logoNombre, $rut, $password, $telefono) {
        if ($this->controlador->obtenerEmpresa($email)) {
            return "EMAIL_DUPLICADO";
        }
        return $this->controlador->guardarEmpresa($nombre, $email, $zona, $logoNombre, $rut, $password, $telefono);
    }
}

class sesionControladorWrapper {
    private $controlador;
    public function __construct() {
        $this->controlador = new controladorSesion();
    }
    public function login($tipo, $email, $password) {
        if ($tipo === 'empresa') return $this->controlador->iniciarSesionEmpresa($email, $password);
        else if ($tipo === 'cliente') return $this->controlador->iniciarSesionUsuario($email, $password);
        return false;
    }
}

class cerrarSesionControladorWrapper {
    private $controlador;
    public function __construct() {
        $this->controlador = new controladorCerrarSesion();
    }
    public function cerrarSesion() {
        return $this->controlador->cerrar();
    }
}

class perfilControladorWrapper {
    private $controlador;
    public function __construct() {
        $this->controlador = new perfilControlador();
    }
    public function obtenerPerfil($id_usuario) {
        return $this->controlador->obtenerPerfil($id_usuario);
    }
    public function guardarPerfil($id_usuario, $direccion, $ciudad, $biografia, $foto) {
        return $this->controlador->guardarPerfil($id_usuario, $direccion, $ciudad, $biografia, $foto);
    }
    public function obtenerMetodoPago($id_usuario) {
        return $this->controlador->obtenerMetodoPago($id_usuario);
    }
    public function guardarMetodoPago($id_usuario, $tipo) {
        return $this->controlador->guardarMetodoPago($id_usuario, $tipo);
    }
}

class perfilEmpresaControladorWrapper {
    private $controlador;
    public function __construct() {
        $this->controlador = new controladorPerfilEmpresa();
    }
    public function editarPerfilEmpresa($idEmpresa, $descripcion, $habilidades, $experiencia, $zona, $telefono) {
        return $this->controlador->editarPerfilEmpresa($idEmpresa, $descripcion, $habilidades, $experiencia, $zona, $telefono);
    }
}

class servicioControladorWrapper {
    private $controlador;
    public function __construct() {
        $this->controlador = new controladorServicio();
    }
    public function obtenerServicios($id_empresa = null) {
        if ($id_empresa) {
            return $this->controlador->listarServiciosEmpresa($id_empresa);
        } else {
            return $this->controlador->listarServiciosActivos(); 
        }
    }
    public function publicarServicio($id_empresa, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado) {
        return $this->controlador->publicarServicio($id_empresa, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado);
    }
    public function eliminarServicio($id_servicio, $id_empresa) {
        return $this->controlador->borrarServicio($id_servicio, $id_empresa);
    }
    public function actualizarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado) {
        return $this->controlador->editarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado);
    }

    public function obtenerServiciosFiltrados($buscar = '', $zona = '', $categoria = '') {
        return $this->controlador->listarServiciosFiltrados($buscar, $zona, $categoria);
    }

}

class reservasControladorWrapper {
    private $controlador;

    public function __construct() {
        $this->controlador = new controladorReservas();
    }

    public function crearReserva($id_cliente, $id_servicio, $fecha, $hora) {
        return $this->controlador->crearReserva($id_cliente, $id_servicio, $fecha, $hora);
    }

    public function verReservasCliente($id_cliente) {
        return $this->controlador->verReservasCliente($id_cliente);
    }

    public function verReservasProveedor($id_proveedor) {
        return $this->controlador->verReservasProveedor($id_proveedor);
    }

    public function cancelarReserva($id_reserva) {
        return $this->controlador->cancelarReserva($id_reserva);
    }

    public function reprogramarReserva($id_reserva, $fecha, $hora) {
        return $this->controlador->reprogramarReserva($id_reserva, $fecha, $hora);
    }

    public function actualizarEstado($id_reserva, $estado) {
        return $this->controlador->actualizarEstado($id_reserva, $estado);
    }

     public function verReservasProveedorFiltradas($id_proveedor, $estado = '', $fecha_inicio = '', $fecha_fin = '') {
        return $this->controlador->verReservasProveedorFiltradas($id_proveedor, $estado, $fecha_inicio, $fecha_fin);
    }
}

class favoritosControladorWrapper {
    private $controlador;
    public function __construct() {
        $this->controlador = new controladorFavorito();
    }
    public function agregarFavorito($id_cliente, $id_servicio) {
        return $this->controlador->agregarFavorito($id_cliente, $id_servicio);
    }
    public function quitarFavorito($id_cliente, $id_servicio) {
        return $this->controlador->quitarFavorito($id_cliente, $id_servicio);
    }
    public function esFavorito($id_cliente, $id_servicio) {
        return $this->controlador->esFavorito($id_cliente, $id_servicio);
    }
    public function obtenerFavoritos($id_cliente) {
        return $this->controlador->obtenerFavoritos($id_cliente);
    }
}

class historialControladorWrapper {
    private $controlador;

    public function __construct() {
        $this->controlador = new controladorHistorial();
    }

    public function listarHistorial($id_cliente, $estado = null) {
        return $this->controlador->listarHistorial($id_cliente, $estado);
    }

    public function listarReservasEmpresa($id_empresa, $estado = null) {
        return $this->controlador->listarReservasEmpresa($id_empresa, $estado);
    }

    public function agregarComentario($id_reserva, $comentarios_cliente, $calificacion, $comentario_calificacion = null) {
        return $this->controlador->agregarComentario($id_reserva, $comentarios_cliente, $calificacion, $comentario_calificacion);
    }

    public function cancelarReserva($id_reserva, $motivo = null) {
        return $this->controlador->cancelarReserva($id_reserva, $motivo);
    }
}

?>

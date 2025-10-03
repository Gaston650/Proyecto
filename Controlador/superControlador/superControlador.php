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
require_once __DIR__ . '/../minisControlador/controladorPromocion.php';   
require_once __DIR__ . '/../minisControlador/controladorResena.php';
require_once __DIR__ . '/../minisControlador/controladorMensaje.php';
require_once __DIR__ . '/../minisControlador/controladorMensajesEmpresa.php';


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
        if ($this->controlador->obtenerEmpresa($email)) return "EMAIL_DUPLICADO";
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
        if ($id_empresa) return $this->controlador->listarServiciosEmpresa($id_empresa);
        return $this->controlador->listarServiciosActivos(); 
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
  
    public function verReservasProveedorFiltradas($id_proveedor, $estado = '', $fecha_inicio = '', $fecha_fin = '') {
        return $this->controlador->verReservasProveedorFiltradas($id_proveedor, $estado, $fecha_inicio, $fecha_fin);
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
    public function confirmarReserva($id_reserva) {
        return $this->controlador->actualizarEstado($id_reserva, 'confirmada');
    }
    public function rechazarReserva($id_reserva) {
        return $this->controlador->actualizarEstado($id_reserva, 'rechazada');
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

    public function agregarComentario($id_cliente, $id_servicio, $comentario, $calificacion) {
        $resenaWrapper = new controladorResena();
        return $resenaWrapper->guardar($id_cliente, $id_servicio, $comentario, $calificacion);
    }

    public function cancelarReserva($id_reserva, $motivo = null) {
        return $this->controlador->cancelarReserva($id_reserva, $motivo);
    }
}


class promocionControladorWrapper {
    private $modelo;
    public function __construct($conn) {
        $this->modelo = new modeloPromocion($conn);
    }
    public function crear($id_servicio, $porcentaje, $inicio, $fin, $condiciones) {
        return $this->modelo->crearPromocion($id_servicio, $porcentaje, $inicio, $fin, $condiciones);
    }
    public function editar($id_promocion, $porcentaje, $inicio, $fin, $condiciones) {
        return $this->modelo->editarPromocion($id_promocion, $porcentaje, $inicio, $fin, $condiciones);
    }
    public function eliminar($id_promocion) {
        return $this->modelo->eliminarPromocion($id_promocion);
    }
    public function listar($empresa_id) {
        return $this->modelo->listarPorEmpresa($empresa_id);
    }
    public function obtenerPromocionPorServicio($id_servicio) {
        return $this->modelo->obtenerPromocionPorServicio($id_servicio);
    }
}

class mensajesControladorWrapper {
    private $controlador;

    public function __construct() {
        require_once __DIR__ . '/../minisControlador/controladorMensajesEmpresa.php';
        $this->controlador = new mensajeControladorEmpresa();
    }

    public function obtenerMensajesEmpresa($id_empresa) {
        return $this->controlador->obtenerMensajesParaEmpresa($id_empresa);
    }

    public function obtenerConversacion($id_cliente, $id_empresa, $id_reserva) {
        return $this->controlador->obtenerConversacionPorCliente($id_cliente, $id_empresa, $id_reserva);
    }

    // Ahora requiere los tipos de emisor y receptor
    public function enviarMensaje($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva, $contenido) {
        return $this->controlador->insertarMensaje($id_emisor, $tipo_emisor, $id_receptor, $tipo_receptor, $id_reserva, $contenido);
    }

    public function marcarMensajesLeidos($id_empresa, $id_cliente, $id_reserva) {
        return $this->controlador->marcarMensajesLeidosEmpresa($id_empresa, $id_cliente, $id_reserva);
    }

    public function contarNoLeidos($id_empresa) {
        return $this->controlador->contarNoLeidos($id_empresa);
    }
}
?>

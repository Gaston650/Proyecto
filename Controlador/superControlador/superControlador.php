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
require_once __DIR__ . '/../minisControlador/procesarMensajes.php';
require_once __DIR__ . '/../minisControlador/controladorNotificaciones.php';

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
    public function crearReserva($id_cliente, $id_servicio, $fecha, $hora, $comentarios = '') {
        return $this->controlador->crearReserva($id_cliente, $id_servicio, $fecha, $hora, $comentarios);
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
    public function cancelarReserva($id_reserva, $id_cliente) {
        return $this->controlador->cancelarReserva($id_reserva, $id_cliente);
    }
    public function reprogramarReserva($id_reserva, $fecha, $hora, $id_cliente) {
        return $this->controlador->reprogramarReserva($id_reserva, $fecha, $hora, $id_cliente);
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

class mensajesClienteWrapper {
    private $controladorCliente;

    public function __construct() {
        $this->controladorCliente = new mensajeControlador();
    }

    public function obtenerMensajesCliente($id_cliente) {
        return $this->controladorCliente->obtenerMensajesCliente($id_cliente);
    }

    public function obtenerConversacion($id_cliente, $id_empresa, $id_reserva) {
        return $this->controladorCliente->manejarConversacion($id_cliente, 'usuario', $id_empresa, 'empresa', $id_reserva);
    }

    public function enviarMensaje($id_cliente, $id_empresa, $id_reserva, $contenido) {
        return $this->controladorCliente->enviarMensaje($id_cliente, 'usuario', $id_empresa, 'empresa', $id_reserva, $contenido);
    }

    public function marcarMensajesLeidos($id_cliente, $id_empresa, $id_reserva) {
        return $this->controladorCliente->marcarMensajesLeidos($id_empresa, $id_cliente, $id_reserva);
    }

    public function contarMensajesNoLeidos($id_cliente) {
        $mensajes_raw = $this->obtenerMensajesCliente($id_cliente);
        $mensajes = procesarMensajesCliente($mensajes_raw);
        $total = 0;
        foreach ($mensajes as $m) {
            $total += $m['no_leidos'];
        }
        return $total;
    }
}

class mensajesEmpresaWrapper {
    private $controlador;

    public function __construct() {
        $this->controlador = new mensajeControladorEmpresa();
    }

    public function obtenerMensajes($id_empresa) {
        return $this->controlador->obtenerMensajesParaEmpresa($id_empresa); 
    }

    public function obtenerConversacion($id_cliente, $id_empresa, $id_reserva) {
        return $this->controlador->obtenerConversacion($id_cliente, $id_empresa, $id_reserva);
    }

    public function enviarMensaje($id_empresa, $id_cliente, $id_reserva, $contenido) {
        return $this->controlador->insertarMensaje($id_empresa, 'empresa', $id_cliente, 'usuario', $id_reserva, $contenido);
    }

    public function marcarMensajesLeidos($id_empresa, $id_cliente, $id_reserva) {
        return $this->controlador->marcarMensajesLeidos($id_empresa, $id_cliente, $id_reserva);
    }
}


class notificacionControladorWrapper {
    private $controlador;

    public function __construct() {
        $this->controlador = new notificacionControlador();
    }

    // Obtener notificaciones no leídas
    public function obtenerNoLeidas($id_empresa) {
        return $this->controlador->obtenerNoLeidas($id_empresa);
    }

    // Obtener todas las notificaciones (sin cambiar su estado)
    public function obtenerTodas($id_empresa) {
        return $this->controlador->obtenerTodas($id_empresa);
    }

    // Insertar notificación
    public function insertarNotificacion($id_empresa, $mensaje, $tipo = 'sistema') {
        return $this->controlador->insertarNotificacion($id_empresa, $mensaje, $tipo);
    }

    // Marcar todas las notificaciones como leídas
    public function marcarLeidas($id_empresa) {
        return $this->controlador->marcarLeidas($id_empresa);
    }

    // Contar notificaciones no leídas
    public function contarNoLeidas($id_empresa) {
        return $this->controlador->contarNoLeidas($id_empresa);
    }
}
?>


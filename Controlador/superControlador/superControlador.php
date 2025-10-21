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
require_once __DIR__ . '/../minisControlador/controladorPago.php';
require_once __DIR__ . '/../minisControlador/controladorNotificacionPago.php';
require_once __DIR__ . '/../minisControlador/controladorAdmin.php';
require_once __DIR__ . '/../minisControlador/controladorReporte.php';

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
        // Llama al único método genérico pasando el tipo seleccionado
        return $this->controlador->iniciarSesion($email, $password, $tipo);
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

    public function editarPerfilEmpresa($idEmpresa, $descripcion, $habilidades, $experiencia, $zona, $telefono, $logo) {
        return $this->controlador->editarPerfilEmpresa($idEmpresa, $descripcion, $habilidades, $experiencia, $zona, $telefono, $logo);
    }

    public function obtenerPerfil($idEmpresa) {
        return $this->controlador->obtenerPerfil($idEmpresa);
    }
}

class servicioControladorWrapper {
    private $controlador;

    public function __construct() {
        $this->controlador = new controladorServicio();
    }

    // Obtener servicios activos o de una empresa específica
    public function obtenerServicios($id_empresa = null) {
        if ($id_empresa) {
            // Devuelve servicios de la empresa con columna 'imagen'
            return $this->controlador->listarServiciosEmpresa($id_empresa);
        }
        // Devuelve servicios activos con columna 'imagen'
        return $this->controlador->listarServiciosActivos(); 
    }

    // Publicar un nuevo servicio incluyendo categoría
    public function publicarServicio($id_empresa, $titulo, $descripcion, $categoria, $ubicacion, $precio, $disponibilidad, $estado, $imagen = null) {
        return $this->controlador->publicarServicio($id_empresa, $titulo, $descripcion, $categoria, $ubicacion, $precio, $disponibilidad, $estado, $imagen);
    }

    // Eliminar un servicio
    public function eliminarServicio($id_servicio, $id_empresa) {
        return $this->controlador->borrarServicio($id_servicio, $id_empresa);
    }

    // Actualizar/editar un servicio
    public function actualizarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado, $imagen = null) {
        return $this->controlador->editarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $disponibilidad, $estado, $imagen);
    }

    // Obtener servicios activos con filtros
    public function obtenerServiciosFiltrados($buscar = '', $zona = '', $categoria = '') {
        // Devuelve servicios filtrados con columna 'imagen'
        return $this->controlador->listarServiciosFiltrados($buscar, $zona, $categoria);
    }

    // Obtener todas las categorías
    public function obtenerCategorias() {
        return $this->controlador->listarCategorias();
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

class pagoControladorWrapper {
    private $controlador;

    public function __construct() {
        $this->controlador = new pagoControlador();
    }

    public function guardarPago($id_reserva, $monto, $estado = 'pendiente') {
        return $this->controlador->guardarPago($id_reserva, $monto, $estado);
    }

    public function pagoRealizado($id_reserva, $monto) {
        return $this->guardarPago($id_reserva, $monto, 'realizado');
    }

    public function pagoFallido($id_reserva, $monto) {
        return $this->guardarPago($id_reserva, $monto, 'fallido');
    }

    public function pagoPendiente($id_reserva, $monto) {
        return $this->guardarPago($id_reserva, $monto, 'pendiente');
    }

    public function obtenerPagosPorReserva($id_reserva) {
        return $this->controlador->obtenerPagosPorReserva($id_reserva);
    }

    public function obtenerVentasDelMes($id_empresa) {
        return $this->controlador->obtenerVentasDelMes($id_empresa);
    }

}

class notificacionPagoControladorWrapper {
    private $controlador;

    public function __construct() {
        $this->controlador = new notificacionPagoControlador();
    }

    // Genera notificación de pago para el proveedor
    public function generarNotificacion($id_reserva, $monto) {
        return $this->controlador->generarNotificacion($id_reserva, $monto);
    }
}

class resenaControladorWrapper {
    private $controlador;
    public function __construct() {
        $this->controlador = new controladorResena();
    }

    // Guardar reseña
    public function guardar($id_cliente, $id_servicio, $comentario, $calificacion) {
        return $this->controlador->guardar($id_cliente, $id_servicio, $comentario, $calificacion);
    }

    // Obtener todas las reseñas de un servicio
    public function obtenerPorServicio($id_servicio) {
        return $this->controlador->obtenerPorServicio($id_servicio);
    }

    // Obtener reseña de un cliente a un servicio
    public function obtenerPorClienteYServicio($id_cliente, $id_servicio) {
        return $this->controlador->obtenerPorClienteYServicio($id_cliente, $id_servicio);
    }

    // Obtener calificación promedio de una empresa
    public function obtenerPromedioPorEmpresa($id_empresa) {
        return $this->controlador->obtenerPromedioPorEmpresa($id_empresa);
    }
}

class adminControladorWrapper {
    private $controlador;

    public function __construct() {
        $this->controlador = new ControladorAdmin();
    }

    // --- Usuarios ---
    public function obtenerUsuarios() {
        $usuarios = $this->controlador->obtenerUsuarios();

        // Eliminar posibles duplicados por ID
        $usuariosUnicos = [];
        foreach ($usuarios as $u) {
            $usuariosUnicos[$u['id_usuario']] = $u;
        }

        return array_values($usuariosUnicos);
    }

    public function crearUsuario($data) {
        return $this->controlador->crearUsuario($data);
    }

    public function editarUsuario($id, $data) {
        $data['rut'] = $data['rut'] ?? '';
        $data['zona_cobertura'] = $data['zona_cobertura'] ?? '';
        $data['logo'] = $data['logo'] ?? '';
        return $this->controlador->editarUsuario($id, $data);
    }

    public function cambiarRol($id, $rol) {
        return $this->controlador->cambiarRol($id, $rol);
    }

    public function eliminarUsuario($id) {
        return $this->controlador->eliminarUsuario($id);
    }

    // --- Servicios ---
    public function obtenerServicios() {
        return $this->controlador->obtenerServicios();
    }

    public function crearServicio($id_empresa, $titulo, $descripcion, $precio, $categoria, $imagen = null) {
        return $this->controlador->crearServicio($id_empresa, $titulo, $descripcion, $precio, $categoria, $imagen);
    }

    public function editarServicio($id, $data) {
        // Llamada al controlador con array de datos
        return $this->controlador->editarServicio($id, $data);
    }

    // Para compatibilidad con scripts que usan parámetros separados
    public function actualizarServicio($id, $titulo, $descripcion, $ubicacion, $precio, $categoria, $estado, $imagen = null) {
        $data = [
            'titulo'      => $titulo,
            'descripcion' => $descripcion,
            'ubicacion'   => $ubicacion,
            'precio'      => $precio,
            'categoria'   => $categoria,
            'estado'      => $estado,
            'imagen'      => $imagen
        ];
        return $this->controlador->editarServicio($id, $data);
    }

    public function eliminarServicio($id) {
        return $this->controlador->eliminarServicio($id);
    }

    public function obtenerEmpresaPorUsuario($id_usuario) {
        return $this->controlador->obtenerEmpresaPorUsuario($id_usuario);
    }

    // Métodos para listar empresas proveedoras
    public function obtenerEmpresas() {
        return $this->controlador->obtenerEmpresas();
    }

    public function obtenerEmpresasProveedor() {
        return $this->controlador->obtenerEmpresasProveedor();
    }

    // --- Categorías ---
    public function obtenerCategorias() {
        return $this->controlador->obtenerCategorias();
    }

    public function crearCategoria($data) {
        return $this->controlador->crearCategoria($data);
    }

    public function editarCategoria($id, $data) {
        return $this->controlador->editarCategoria($id, $data);
    }

    public function eliminarCategoria($id) {
        return $this->controlador->eliminarCategoria($id);
    }

    // --- Historial ---
    public function obtenerHistorial() {
        return $this->controlador->obtenerHistorial();
    }

    // --- Reportes ---
    public function obtenerReportes() {
        return $this->controlador->obtenerReportes();
    }

    public function gestionarReporte($id_reporte, $accion) {
        return $this->controlador->gestionarReporte($id_reporte, $accion);
    }
}

class reporteControladorWrapper {
    private $controlador;

    public function __construct($conexion) {
        $this->controlador = new controladorReporte($conexion);
    }

    public function crearReporte($id_usuario, $id_servicio, $motivo) {
        return $this->controlador->crearReporte($id_usuario, $id_servicio, $motivo);
    }

    public function obtenerPorServicio($id_servicio) {
        return $this->controlador->obtenerPorServicio($id_servicio);
    }
}
?>


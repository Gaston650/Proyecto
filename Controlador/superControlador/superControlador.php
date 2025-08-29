    <?php
    require_once __DIR__ . '/../minisControlador/usuarioControlador.php';
    require_once __DIR__ . '/../minisControlador/controladorEmpresa.php';
    require_once __DIR__ . '/../minisControlador/controladorSesion.php';
    require_once __DIR__ . '/../minisControlador/controladorCerrarSesion.php';
    require_once __DIR__ . '/../minisControlador/controladorPerfil.php';
    require_once __DIR__ . '/../minisControlador/controladorPerfilEmpresa.php';
    require_once __DIR__ . '/../minisControlador/controladorServicio.php';
 

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

        public function obtenerServicios($id_empresa) {
            return $this->controlador->listarServiciosEmpresa($id_empresa);
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

    }
    ?>

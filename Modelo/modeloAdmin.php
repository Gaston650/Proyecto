<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/modeloHistorial.php';

class ModeloAdmin {
    private $conexion;
    private $historialModelo;

    public function __construct() {
        $db = new conexion();
        $this->conexion = $db->conectar();
        $this->historialModelo = new HistorialModelo();
    }

    // --- Usuarios ---
    public function obtenerUsuarios() {
        $query = "SELECT id_usuario, nombre, email, tipo_usuario, fecha_registro, estado
                  FROM usuarios";

        $resultado = $this->conexion->query($query);
        $usuarios = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $usuarios[] = $fila;
            }
        }
        return $usuarios;
    }

    // --- Empresas ---
    public function obtenerEmpresas() {
        $query = "SELECT id_empresa, nombre_empresa, email_empresa, rut, zona_cobertura, logo, estado
                  FROM empresas_proveedor";

        $resultado = $this->conexion->query($query);
        $empresas = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $empresas[] = [
                    'id_usuario'    => $fila['id_empresa'],
                    'nombre'        => $fila['nombre_empresa'],
                    'email'         => $fila['email_empresa'],
                    'tipo_usuario'  => 'empresa',
                    'estado'        => $fila['estado'],
                    'rut'           => $fila['rut'],
                    'zona_cobertura'=> $fila['zona_cobertura'],
                    'logo'          => $fila['logo']
                ];
            }
        }
        return $empresas;
    }

    // --- Historial ---
    public function obtenerHistorialCompleto() {
        $reservas = [];
        $query = "SELECT * FROM reservas";
        $resultado = $this->conexion->query($query);

        if ($resultado && $resultado->num_rows > 0) {
            while ($reserva = $resultado->fetch_assoc()) {
                $id_cliente = $reserva['id_cliente'];
                $historialCliente = $this->historialModelo->obtenerHistorialReserva($id_cliente);
                foreach ($historialCliente as $item) {
                    $reservas[] = $item;
                }
            }
        }
        return $reservas;
    }

    // --- Servicios ---
    public function obtenerServicios() {
        $query = "SELECT s.id_servicio, s.titulo, s.descripcion, s.categoria, s.precio, s.estado,
                         e.nombre_empresa AS proveedor
                  FROM servicios s
                  LEFT JOIN empresas_proveedor e ON s.id_empresa = e.id_empresa
                  ORDER BY s.id_servicio DESC";

        $resultado = $this->conexion->query($query);
        $servicios = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $servicios[] = $fila;
            }
        }

        return $servicios;
    }

    public function crearServicio($id_empresa, $titulo, $descripcion, $precio, $categoria, $imagen = null) {
        $id_empresa = (int)$id_empresa;
        $titulo = $this->conexion->real_escape_string($titulo);
        $descripcion = $this->conexion->real_escape_string($descripcion);
        $precio = (float)$precio;
        $categoria = $this->conexion->real_escape_string($categoria);
        $imagen = $this->conexion->real_escape_string($imagen ?? '');

        $query = "INSERT INTO servicios (id_empresa, titulo, descripcion, precio, categoria, imagen, estado)
                  VALUES ($id_empresa, '$titulo', '$descripcion', $precio, '$categoria', '$imagen', 'Disponible')";

        return $this->conexion->query($query);
    }

    public function obtenerEmpresaPorUsuario($id_usuario) {
        $id_usuario = (int)$id_usuario;
        $sql = "SELECT id_empresa FROM empresas_proveedor WHERE id_usuario = $id_usuario";
        $res = $this->conexion->query($sql);
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return $row['id_empresa'];
        }
        return null;
    }

    public function editarServicio($id_servicio, $titulo, $descripcion, $ubicacion, $precio, $categoria, $estado, $imagen = null) {
        $id_servicio = (int)$id_servicio;
        $titulo = $this->conexion->real_escape_string($titulo);
        $descripcion = $this->conexion->real_escape_string($descripcion);
        $ubicacion = $this->conexion->real_escape_string($ubicacion);
        $precio = (float)$precio;
        $categoria = $this->conexion->real_escape_string($categoria);
        $estado = $estado ?: 'disponible'; // nunca null
        $imagen = $imagen ? $this->conexion->real_escape_string($imagen) : null;
        
        if ($imagen) {
            $query = "UPDATE servicios 
                      SET titulo='$titulo', descripcion='$descripcion', ubicacion='$ubicacion', precio=$precio,
                          categoria='$categoria', estado='$estado', imagen='$imagen'
                      WHERE id_servicio=$id_servicio";
        } else {
            $query = "UPDATE servicios 
                      SET titulo='$titulo', descripcion='$descripcion', ubicacion='$ubicacion', precio=$precio,
                          categoria='$categoria', estado='$estado'
                      WHERE id_servicio=$id_servicio";
        }
    
        return $this->conexion->query($query);
    }

    public function eliminarServicio($id) {
        try {
            $id = (int)$id;
            $query = "DELETE FROM servicios WHERE id_servicio = $id";
            return $this->conexion->query($query);
        } catch (Exception $e) {
            error_log("❌ Error en eliminarServicio: " . $e->getMessage());
            return false;
        }
    }



    // --- Reportes ---
    public function obtenerReportes() {
        $query = "SELECT rp.id_reporte, rp.motivo, rp.estado, rp.fecha_reporte,
                         s.titulo AS servicio,
                         u.nombre AS usuario_reportante
                  FROM reporte rp
                  LEFT JOIN servicios s ON rp.id_servicio = s.id_servicio
                  LEFT JOIN usuarios u ON rp.id_usuario_reportante = u.id_usuario
                  ORDER BY rp.fecha_reporte DESC";

        $resultado = $this->conexion->query($query);
        $reportes = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $reportes[] = $fila;
            }
        }

        return $reportes;
    }

    public function gestionarReporte($id_reporte, $accion) {
        $nuevo_estado = $accion === 'resuelto' ? 'resuelto' : 'pendiente';
        $query = "UPDATE reporte SET estado = '$nuevo_estado' WHERE id_reporte = $id_reporte";
        return $this->conexion->query($query);
    }

    // --- Categorías ---
    public function obtenerCategorias() {
        $query = "SELECT id_categoria, nombre_categoria FROM categorias ORDER BY nombre_categoria ASC";
        $resultado = $this->conexion->query($query);
        $categorias = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $categorias[] = $fila;
            }
        }
        return $categorias;
    }

    public function crearCategoria($data) {
        $nombre = $this->conexion->real_escape_string($data['nombre_categoria']);
        $query = "INSERT INTO categorias (nombre_categoria) VALUES ('$nombre')";
        return $this->conexion->query($query);
    }

    public function editarCategoria($id, $data) {
        $nombre = $this->conexion->real_escape_string($data['nombre_categoria']);
        $query = "UPDATE categorias SET nombre_categoria = '$nombre' WHERE id_categoria = $id";
        return $this->conexion->query($query);
    }

    public function eliminarCategoria($id) {
        $query = "DELETE FROM categorias WHERE id_categoria = $id";
        return $this->conexion->query($query);
    }

    // --- Usuarios / Empresas ---
    public function crearUsuario($data) {
        $nombre = $data['nombre'];
        $email = $data['email'];
        $tipo = $data['tipo_usuario'];
        $estado = $data['estado'];
        $contraseña = $data['contraseña'];

        if ($tipo === 'empresa') {
            $rut = $data['rut'] ?? null;
            if (!$rut) throw new Exception("RUT requerido para empresa.");

            $zona_cobertura = $data['zona_cobertura'] ?? '';
            $logo = $data['logo'] ?? '';

            $query = "INSERT INTO empresas_proveedor 
                        (nombre_empresa, email_empresa, contraseña, rut, zona_cobertura, logo, estado) 
                      VALUES ('$nombre', '$email', '$contraseña', '$rut', '$zona_cobertura', '$logo', '$estado')";
        } else {
            $query = "INSERT INTO usuarios 
                        (nombre, email, contraseña, tipo_usuario, estado) 
                      VALUES ('$nombre', '$email', '$contraseña', '$tipo', '$estado')";
        }

        return $this->conexion->query($query);
    }

    public function editarUsuario($id, $data) {
    try {
        $id = intval($id);
        $nombre = $this->conexion->real_escape_string($data['nombre'] ?? '');
        $email = $this->conexion->real_escape_string($data['email'] ?? '');
        $estado = $this->conexion->real_escape_string($data['estado'] ?? '');
        $tipo = $this->conexion->real_escape_string($data['tipo_usuario'] ?? '');

        // Validar estado
        $estadosValidos = ['activo','inactivo','bloqueado'];
        if (!in_array($estado, $estadosValidos)) $estado = 'activo';

        $checkEmpresa = $this->conexion->query("SELECT id_empresa FROM empresas_proveedor WHERE id_empresa = $id");
        if ($checkEmpresa && $checkEmpresa->num_rows > 0) {
            $rut = $this->conexion->real_escape_string($data['rut'] ?? '');
            $zona = $this->conexion->real_escape_string($data['zona_cobertura'] ?? '');
            $logo = $this->conexion->real_escape_string($data['logo'] ?? '');
            $query = "UPDATE empresas_proveedor 
                      SET nombre_empresa='$nombre', email_empresa='$email', rut='$rut', zona_cobertura='$zona', logo='$logo', estado='$estado'
                      WHERE id_empresa=$id";
        } else {
            $query = "UPDATE usuarios 
                      SET nombre='$nombre', email='$email', tipo_usuario='$tipo', estado='$estado'
                      WHERE id_usuario=$id";
        }

        return $this->conexion->query($query);
    } catch (Exception $e) {
        error_log("❌ Excepción en editarUsuario: " . $e->getMessage());
        return false;
    }
}


    public function eliminarUsuarioPorId($id) {
        try {
            $id = (int)$id;
            $resEmpresa = $this->conexion->query("SELECT id_empresa FROM empresas_proveedor WHERE id_empresa=$id");
            if ($resEmpresa && $resEmpresa->num_rows > 0) {
                return $this->conexion->query("DELETE FROM empresas_proveedor WHERE id_empresa=$id");
            }
            $resUsuario = $this->conexion->query("SELECT id_usuario FROM usuarios WHERE id_usuario=$id");
            if ($resUsuario && $resUsuario->num_rows > 0) {
                return $this->conexion->query("DELETE FROM usuarios WHERE id_usuario=$id");
            }
            throw new Exception("Usuario no encontrado.");
        } catch (Exception $e) {
            error_log("❌ Error en eliminarUsuarioPorId: " . $e->getMessage());
            return false;
        }
    }

    public function cambiarRol($id, $rol) {
        $rol = $this->conexion->real_escape_string($rol);
        $query = "UPDATE usuarios SET tipo_usuario = '$rol' WHERE id_usuario = $id";
        return $this->conexion->query($query);
    }
}
?>

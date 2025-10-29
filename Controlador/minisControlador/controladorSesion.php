<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Modelo/modeloUsuario.php';
require_once __DIR__ . '/../../Modelo/modeloRegistrarEmpresa.php';
require_once __DIR__ . '/../../Modelo/modeloPerfil.php'; // Importar modeloPerfil

class controladorSesion {
    private $usuarioModelo;
    private $empresaModelo;
    private $perfilModelo;

    public function __construct($conn) {
        // Pasar la conexión a los modelos
        $this->usuarioModelo = new usuario2Modelo($conn); // modelo de usuarios
        $this->empresaModelo = new empresaModelo($conn);  // modelo de empresas
        $this->perfilModelo = new perfilModelo($conn);    // modelo de perfiles
    }

    // Iniciar sesión según tipo seleccionado
    public function iniciarSesion($email, $password, $tipoSeleccionado){
        if ($tipoSeleccionado === 'empresa') {
            $empresa = $this->empresaModelo->obtenerEmpresa($email);
            if (!$empresa || !password_verify($password, $empresa['contraseña'])) {
                $_SESSION['error_login'] = "Debes iniciar sesión como empresa o verificar tus credenciales.";
                return false;
            }

            // Verificar estado de la empresa
            if ($empresa['estado'] === 'bloqueado') {
                $_SESSION['error_login'] = "Usuario bloqueado, contacta al administrador";
                return false;
            }

            // ✅ Datos de sesión uniformes
            $_SESSION['user_id'] = $empresa['id_empresa'];
            $_SESSION['user_nombre'] = $empresa['nombre_empresa'];
            $_SESSION['tipo_usuario'] = 'empresa';
            $_SESSION['email'] = $empresa['email'] ?? '';
            $_SESSION['user_image'] = $empresa['logo'] ?? '../../IMG/perfil-vacio.png';

            return true;
        } else {
            $usuario = $this->usuarioModelo->obtenerUsuario($email);
            if (!$usuario || !password_verify($password, $usuario['contraseña'])) {
                $_SESSION['error_login'] = "Usuario o contraseña incorrectos.";
                return false;
            }

            // Verificar estado del usuario
            if ($usuario['estado'] === 'bloqueado') {
                $_SESSION['error_login'] = "Usuario bloqueado, contacta al administrador";
                return false;
            }

            // Verifica que coincida el tipo seleccionado
            if ($tipoSeleccionado === 'administrador' && $usuario['tipo_usuario'] !== 'administrador') {
                $_SESSION['error_login'] = "Debes iniciar sesión como administrador.";
                return false;
            }
            if ($tipoSeleccionado === 'cliente' && $usuario['tipo_usuario'] !== 'cliente') {
                $_SESSION['error_login'] = "Debes iniciar sesión como cliente.";
                return false;
            }

            // ✅ Datos de sesión uniformes
            $_SESSION['user_id'] = $usuario['id_usuario'] ?? $usuario['id'];
            $_SESSION['user_nombre'] = $usuario['nombre'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
            $_SESSION['email'] = $usuario['email'] ?? '';

            // 🔹 Obtener foto de perfil desde la tabla de perfiles
            $perfil = $this->perfilModelo->obtenerPerfil($_SESSION['user_id']);
            if ($perfil && !empty($perfil['foto_perfil'])) {
                $_SESSION['user_image'] = $perfil['foto_perfil'];
            } else {
                $_SESSION['user_image'] = '../../IMG/perfil-vacio.png';
            }

            return true;
        }
    }
}
?>

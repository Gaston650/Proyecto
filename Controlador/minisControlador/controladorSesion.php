<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Modelo/modeloUsuario.php';
require_once __DIR__ . '/../../Modelo/modeloRegistrarEmpresa.php';

class controladorSesion {
    private $usuarioModelo;
    private $empresaModelo;

    public function __construct() {
        $this->usuarioModelo = new usuario2Modelo(); // modelo de usuarios
        $this->empresaModelo = new empresaModelo();  // modelo de empresas
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

            // Datos de sesión
            $_SESSION['user_id'] = $empresa['id_empresa'];
            $_SESSION['user_nombre'] = $empresa['nombre_empresa'];
            $_SESSION['empresa_logo'] = $empresa['logo'];
            $_SESSION['tipo_usuario'] = 'empresa';

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

            // Datos de sesión
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nombre'] = $usuario['nombre'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            return true;
        }
    }
}
?>

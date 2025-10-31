<?php
require_once __DIR__ . '/../../Modelo/modeloRegistro.php';

class UsuarioControlador {
    private $usuarioModelo;

    // Permitimos inyectar un modelo para tests
    public function __construct($conn = null, $modelo = null) {
        if ($modelo !== null) {
            $this->usuarioModelo = $modelo;
        } else {
            $conn = $conn ?? new conexion();
            $this->usuarioModelo = new usuarioModelo($conn);
        }
    }

    public function guardarUsuario($nombre, $email, $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        return $this->usuarioModelo->insertarUsuario($nombre, $email, $hashed);
    }

    public function loginUsuario($email, $password, $recordarme = false) {
        $usuario = $this->usuarioModelo->obtenerUsuarioPorEmail($email);

        if (!$usuario || !password_verify($password, $usuario['password'] ?? '')) {
            return ['ok' => false, 'msg' => 'Correo o contraseña incorrectos.'];
        }

        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION['user_id'] = $usuario['id_usuario'];
        $_SESSION['user_nombre'] = $usuario['nombre'];
        $_SESSION['user_email'] = $usuario['email'];
        $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'] ?? 'cliente';

        if ($recordarme) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (86400 * 30), "/", "", false, true);
            $this->usuarioModelo->guardarToken($usuario['id_usuario'], $token);
        }

        return ['ok' => true, 'msg' => 'Inicio de sesión exitoso.'];
    }

    public function verificarSesionPersistente() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (isset($_SESSION['user_id'])) return;

        if (isset($_COOKIE['remember_token'])) {
            $usuario = $this->usuarioModelo->obtenerUsuarioPorToken($_COOKIE['remember_token']);
            if ($usuario) {
                $_SESSION['user_id'] = $usuario['id_usuario'];
                $_SESSION['user_nombre'] = $usuario['nombre'];
                $_SESSION['user_email'] = $usuario['email'];
                $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'] ?? 'cliente';
            } else {
                setcookie('remember_token', '', time() - 3600, "/", "", false, true);
            }
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (isset($_SESSION['user_id'])) {
            $this->usuarioModelo->guardarToken($_SESSION['user_id'], null);
        }

        setcookie('remember_token', '', time() - 3600, "/", "", false, true);
        session_destroy();
        header("Location: ../../index.php");
        exit();
    }

    public function loginGoogle($nombre, $email) {
        $usuario = $this->usuarioModelo->obtenerUsuarioPorEmail($email);
        if (!$usuario) {
            $id = $this->usuarioModelo->registrarUsuarioGoogle($nombre, $email);
            $usuario = $this->usuarioModelo->obtenerUsuarioPorId($id);
        }
        return $usuario;
    }
}
?>

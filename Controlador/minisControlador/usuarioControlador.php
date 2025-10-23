<?php
require_once __DIR__ . '/../../Modelo/modeloUsuario.php';

class UsuarioControlador {
    private $usuarioModelo;

    public function __construct($conn) {
        $this->usuarioModelo = new usuario2Modelo($conn);
    }

    public function guardarUsuario($nombre, $email, $password) {
        return $this->usuarioModelo->insertarUsuario($nombre, $email, $password);
    }

    public function loginGoogle($nombre, $email) {
        $usuario = $this->usuarioModelo->obtenerUsuario($email);
        if (!$usuario) {
            $this->usuarioModelo->registrarUsuarioGoogle($nombre, $email);
            $usuario = $this->usuarioModelo->obtenerUsuario($email);
        }
        return $usuario;
    }

    public function solicitarReset($email) {
        $usuario = $this->usuarioModelo->obtenerUsuario($email);
        if (!$usuario) {
            return ['ok' => true, 'msg' => 'Si el correo existe, recibirás un enlace de recuperación.'];
        }
        $token = $this->usuarioModelo->crearRecuperacion($usuario['id_usuario']);
        $resetLink = "http://localhost/ClickSoft/Vista/VistaReset/reset_password.php?token=" . urlencode($token);
        return ['ok' => true, 'msg' => 'Si el correo existe, usa este enlace para restablecer tu contraseña:', 'link' => $resetLink];
    }

    public function validarToken($token) {
        return $this->usuarioModelo->validarToken($token);
    }

    public function obtenerUsuarioPorId($id) {
        return $this->usuarioModelo->obtenerUsuarioPorId($id);
    }

    // Cambiar contraseña y hacer login automático
    public function cambiarContrasena($token, $nueva, $confirmar) {
    if ($nueva !== $confirmar) {
        return ['ok' => false, 'msg' => 'Las contraseñas no coinciden.'];
    }

    $datosToken = $this->usuarioModelo->validarToken($token);
    if (!$datosToken) {
        return ['ok' => false, 'msg' => 'El enlace de recuperación no es válido.'];
    }
    if (strtotime($datosToken['expiracion']) < time()) {
        return ['ok' => false, 'msg' => 'El enlace de recuperación ha expirado.'];
    }

    // Cambiar contraseña
    $this->usuarioModelo->actualizarContrasena($datosToken['id_usuario'], $nueva);
    $this->usuarioModelo->marcarTokenUsado($datosToken['id_recuperacion']);

    // Crear sesión automáticamente
    session_start();
    $usuario = $this->usuarioModelo->obtenerUsuarioPorId($datosToken['id_usuario']);
    $_SESSION['user_id'] = $usuario['id'];
    $_SESSION['user_nombre'] = $usuario['nombre'];
    $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
    $_SESSION['email'] = $usuario['email'] ?? '';
    $_SESSION['imagen'] = $usuario['imagen'] ?? 'default.png';

    // Redirigir al home del cliente automáticamente
    header("Location: ../../Vista/VistaPrincipal/home.php");
    exit();
}

}
?>

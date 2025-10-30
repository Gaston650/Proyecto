<?php
require_once __DIR__ . '/../../Modelo/modeloRegistrarEmpresa.php';

class controladorEmpresa {
    private $empresaModelo;

    public function __construct() {
        $this->empresaModelo = new empresaModelo();
    }

    // Registrar empresa
    public function registrarEmpresa($nombre, $email, $zona, $logoNombre, $telefono, $password, $rut) {
        if ($this->empresaModelo->obtenerEmpresa($email)) {
            return ['ok' => false, 'msg' => 'El correo ya está registrado.'];
        }

        // Pasa la contraseña sin hashear, el modelo la hashea
        $resultado = $this->empresaModelo->insertarEmpresa($nombre, $email, $zona, $logoNombre, $password, $rut);

        if ($resultado) {
            $empresa = $this->empresaModelo->obtenerEmpresa($email);
            $this->empresaModelo->insertarTelefono($empresa['id_empresa'], $telefono);
            return ['ok' => true, 'msg' => 'Empresa registrada correctamente.'];
        }

        return ['ok' => false, 'msg' => 'Error al registrar la empresa.'];
    }

    // Login empresa
    public function loginEmpresa($email, $password, $recordarme = false) {
        $empresa = $this->empresaModelo->obtenerEmpresa($email);

        if (!$empresa || !password_verify($password, $empresa['contraseña'] ?? '')) {
            return ['ok' => false, 'msg' => 'Correo o contraseña incorrectos.'];
        }

        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION['empresa_id'] = $empresa['id_empresa'];
        $_SESSION['empresa_nombre'] = $empresa['nombre_empresa'];
        $_SESSION['empresa_email'] = $empresa['email_empresa'];
        $_SESSION['tipo_usuario'] = 'empresa';

        if ($recordarme) {
            $token = bin2hex(random_bytes(32));
            setcookie('empresa_remember_token', $token, time() + (86400 * 30), "/", "", false, true);
            $this->empresaModelo->guardarToken($empresa['id_empresa'], $token);
        }

        return ['ok' => true, 'msg' => 'Inicio de sesión exitoso.'];
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['empresa_id'])) {
            $this->empresaModelo->guardarToken($_SESSION['empresa_id'], null);
        }
        setcookie('empresa_remember_token', '', time() - 3600, "/", "", false, true);
        session_destroy();
        header("Location: ../../index.php");
        exit();
    }
}
?>
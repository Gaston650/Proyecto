<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../Modelo/modeloRegistro.php';
require_once __DIR__ . '/../../Modelo/modeloRegistrarEmpresa.php';
require_once __DIR__ . '/../../Modelo/modeloAdmin.php';
require_once __DIR__ . '/../../Modelo/modeloPerfil.php';

// Crear instancias de modelos
$perfilModelo   = new perfilModelo();
$usuarioModelo  = new usuarioModelo();
$empresaModelo  = new empresaModelo();
$adminModelo    = new ModeloAdmin();

if (isset($_POST['iniciar_sesion'])) {
    $correo        = strtolower(trim($_POST['correo']));
    $contrasena    = trim($_POST['contrasena']);
    $tipo_usuario  = $_POST['tipo_usuario'] ?? 'cliente';
    $recordarme    = isset($_POST['recordarme']);

    $sesion = [];

    switch ($tipo_usuario) {
        case 'cliente':
            $usuario = $usuarioModelo->obtenerUsuarioPorEmail($correo);
            if (!$usuario || !password_verify($contrasena, $usuario['password'] ?? '')) {
                header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Correo o contraseña incorrectos");
                exit();
            }
            $perfil = $perfilModelo->obtenerPerfil($usuario['id_usuario']);
            $sesion = [
                'user_id'     => $usuario['id_usuario'],
                'user_nombre' => $usuario['nombre'],
                'tipo_usuario'=> 'cliente',
                'email'       => $usuario['email'] ?? '',
                'user_image'  => !empty($usuario['imagen_google']) 
                                  ? $usuario['imagen_google'] 
                                  : ($perfil['foto_perfil'] ?? '../../IMG/perfil-vacio.png')
            ];
            break;

        case 'empresa':
            $empresa = $empresaModelo->obtenerEmpresa($correo);
            if (!$empresa || !password_verify($contrasena, $empresa['contraseña'] ?? '')) {
                header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Correo o contraseña incorrectos");
                exit();
            }
            if (!empty($empresa['estado']) && $empresa['estado'] !== 'activo') {
                header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Empresa inactiva o pendiente");
                exit();
            }
            $sesion = [
                'user_id'     => $empresa['id_empresa'],
                'user_nombre' => $empresa['nombre_empresa'],
                'tipo_usuario'=> 'empresa',
                'email'       => $empresa['email_empresa'] ?? '',
                'user_image'  => !empty($empresa['logo']) ? '../../IMG/empresas/' . $empresa['logo'] : '../../IMG/perfil-vacio.png'
            ];
            break;

        case 'administrador':
            $usuario = $usuarioModelo->obtenerUsuarioPorEmail($correo);
            if (!$usuario || $usuario['tipo_usuario'] !== 'administrador' || !password_verify($contrasena, $usuario['password'] ?? '')) {
                header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Correo o contraseña incorrectos o sin permisos");
                exit();
            }
            $sesion = [
                'user_id'     => $usuario['id_usuario'],
                'user_nombre' => $usuario['nombre'],
                'tipo_usuario'=> 'administrador',
                'email'       => $usuario['email'] ?? '',
                'user_image'  => '../../IMG/admin.png'
            ];
            break;

        default:
            header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Tipo de usuario inválido");
            exit();
    }

    // Guardar sesión
    foreach ($sesion as $key => $value) {
        $_SESSION[$key] = $value;
    }

    // Recordarme
    if ($recordarme) {
        $token = bin2hex(random_bytes(32));
        if (in_array($tipo_usuario, ['cliente', 'administrador'])) {
            $usuarioModelo->guardarToken($_SESSION['user_id'], $token);
        } elseif ($tipo_usuario === 'empresa') {
            $empresaModelo->actualizarEmpresaRememberToken($_SESSION['user_id'], $token);
        }
        setcookie('remember_token', $token, time() + (86400 * 30), "/", "", false, true);
        setcookie('tipo_usuario', $tipo_usuario, time() + (86400 * 30), "/", "", false, true);
    } else {
        setcookie('remember_token', '', time() - 3600, "/", "", false, true);
        setcookie('tipo_usuario', '', time() - 3600, "/", "", false, true);
        if (in_array($tipo_usuario, ['cliente', 'administrador'])) {
            $usuarioModelo->guardarToken($_SESSION['user_id'], null);
        } elseif ($tipo_usuario === 'empresa') {
            $empresaModelo->actualizarEmpresaRememberToken($_SESSION['user_id'], null);
        }
    }

    // Redirección según tipo
    $redirect = match($tipo_usuario) {
        'cliente' => '../../Vista/VistaPrincipal/home.php',
        'empresa' => '../../Vista/VistaPrincipal/homeEmpresa.php',
        'administrador' => '../../Vista/VistaAdmin/dashboard.php',
        default => '../../Vista/VistaSesion/inicioSesion.php',
    };

    header("Location: $redirect");
    exit();
}
?>

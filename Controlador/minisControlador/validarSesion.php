<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Modelos
require_once __DIR__ . '/../../Modelo/modeloRegistro.php';
require_once __DIR__ . '/../../Modelo/modeloRegistrarEmpresa.php';
require_once __DIR__ . '/../../Modelo/modeloAdmin.php';
require_once __DIR__ . '/../../Modelo/modeloPerfil.php';

$perfilModelo = new perfilModelo();
$usuarioModelo = new usuarioModelo();
$empresaModelo = new empresaModelo();
$adminModelo = new ModeloAdmin(); 

if (isset($_POST['iniciar_sesion'])) {
    $correo = strtolower(trim($_POST['correo']));
    $contrasena = trim($_POST['contrasena']);
    $tipo_usuario = $_POST['tipo_usuario'] ?? 'cliente';
    $recordarme = isset($_POST['recordarme']);

    // --- Iniciar sesión según tipo ---
    if ($tipo_usuario === 'cliente') {
        $usuario = $usuarioModelo->obtenerUsuarioPorEmail($correo);
        if (!$usuario || !password_verify($contrasena, $usuario['password'] ?? '')) {
            header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Correo o contraseña incorrectos");
            exit();
        }
        $_SESSION['user_id'] = $usuario['id_usuario'];
        $_SESSION['user_nombre'] = $usuario['nombre'];
        $_SESSION['tipo_usuario'] = 'cliente';
        $_SESSION['email'] = $usuario['email'] ?? '';

        if (!empty($usuario['imagen_google'])) {
            $_SESSION['imagen'] = $usuario['imagen_google'];
            $_SESSION['user_image'] = $usuario['imagen_google'];
        } else {
            $perfil = $perfilModelo->obtenerPerfil($usuario['id_usuario']);
            $_SESSION['user_image'] = ($perfil['foto_perfil'] ?? '../../IMG/perfil-vacio.png');
        }

    } elseif ($tipo_usuario === 'empresa') {
        $empresa = $empresaModelo->obtenerEmpresa($correo);
        if (!$empresa || !password_verify($contrasena, $empresa['contraseña'] ?? '')) {
            header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Correo o contraseña incorrectos");
            exit();
        }
        if (isset($empresa['estado']) && $empresa['estado'] !== 'activo') {
            header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Empresa inactiva o pendiente");
            exit();
        }
        $_SESSION['user_id'] = $empresa['id_empresa'];
        $_SESSION['user_nombre'] = $empresa['nombre_empresa'];
        $_SESSION['tipo_usuario'] = 'empresa';
        $_SESSION['email'] = $empresa['email_empresa'] ?? '';
        $_SESSION['user_image'] = $empresa['logo'] ?? '../../IMG/perfil-vacio.png';

    } elseif ($tipo_usuario === 'administrador') {
        $usuario = $usuarioModelo->obtenerUsuarioPorEmail($correo);
        if (!$usuario || $usuario['tipo_usuario'] !== 'administrador' || !password_verify($contrasena, $usuario['password'] ?? '')) {
            header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Correo o contraseña incorrectos o sin permisos");
            exit();
        }
        $_SESSION['user_id'] = $usuario['id_usuario'];
        $_SESSION['user_nombre'] = $usuario['nombre'];
        $_SESSION['tipo_usuario'] = 'administrador';
        $_SESSION['email'] = $usuario['email'] ?? '';
        $_SESSION['user_image'] = '../../IMG/admin.png';
    }

    // --- Recordarme ---
    if ($recordarme) {
        $token = bin2hex(random_bytes(32));
        if ($tipo_usuario === 'cliente' || $tipo_usuario === 'administrador') {
            $usuarioModelo->guardarToken($_SESSION['user_id'], $token);
        } elseif ($tipo_usuario === 'empresa') {
            $empresaModelo->actualizarEmpresaRememberToken($_SESSION['user_id'], $token);
        }
        setcookie('remember_token', $token, time() + (86400*30), "/", "", false, true);
        setcookie('tipo_usuario', $tipo_usuario, time() + (86400*30), "/", "", false, true);
    } else {
        // ❌ Borrar token y cookies previas si NO marcó recordarme
        setcookie('remember_token', '', time() - 3600, "/", "", false, true);
        setcookie('tipo_usuario', '', time() - 3600, "/", "", false, true);
        if ($tipo_usuario === 'cliente' || $tipo_usuario === 'administrador') {
            $usuarioModelo->guardarToken($_SESSION['user_id'], null);
        } elseif ($tipo_usuario === 'empresa') {
            $empresaModelo->actualizarEmpresaRememberToken($_SESSION['user_id'], null);
        }
    }

    // --- Redirección final ---
    if ($tipo_usuario === 'cliente') {
        header("Location: ../../Vista/VistaPrincipal/home.php");
    } elseif ($tipo_usuario === 'empresa') {
        header("Location: ../../Vista/VistaPrincipal/homeEmpresa.php");
    } elseif ($tipo_usuario === 'administrador') {
        header("Location: ../../Vista/VistaAdmin/dashboard.php");
    }
    exit();
}
?>


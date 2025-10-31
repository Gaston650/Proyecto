<?php
// validarRegistroEmpresa.php - VERSIÓN CON MANEJO DE ERRORES SQL
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Incluir el controlador
$rutaControlador = __DIR__ . '/../../Controlador/superControlador/superControlador.php';
if (!file_exists($rutaControlador)) {
    die("ERROR: No se encuentra el archivo controlador.");
}

require_once $rutaControlador;

// Solo procesar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_empresa'])) {
    
    $nombre = trim($_POST['nombre_empresa'] ?? '');
    $email = trim($_POST['correo_empresa'] ?? '');
    $zona = trim($_POST['zona_cobertura'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $rut = trim($_POST['rut'] ?? '');
    $password = $_POST['contrasena'] ?? '';
    $confirmar = $_POST['confirmar_contrasena'] ?? '';

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($password)) {
        $_SESSION['mensaje_registro'] = "Todos los campos son obligatorios.";
        $_SESSION['tipo_mensaje'] = "error";
        header("Location: ../../Vista/VistaRegistro/registrarEmpresa.php");
        exit();
    }

    if ($password !== $confirmar) {
        $_SESSION['mensaje_registro'] = "Las contraseñas no coinciden.";
        $_SESSION['tipo_mensaje'] = "error";
        header("Location: ../../Vista/VistaRegistro/registrarEmpresa.php");
        exit();
    }

    // Procesar logo
    $logoNombre = 'default_logo.png';
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $directorioDestino = __DIR__ . '/../../IMG/empresas/';
        
        if (!file_exists($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $fileType = mime_content_type($_FILES['logo']['tmp_name']);
        
        if (in_array($fileType, $allowedTypes)) {
            $extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $logoNombre = time() . '_' . uniqid() . '.' . strtolower($extension);
            $rutaDestino = $directorioDestino . $logoNombre;
            
            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $rutaDestino)) {
                $logoNombre = 'default_logo.png';
            }
        }
    }

    try {
        $controlador = new empresaControladorWrapper();
        $resultado = $controlador->registrar($nombre, $email, $zona, $logoNombre, $telefono, $password, $rut);

        if (is_array($resultado)) {
            if ($resultado['ok']) {
                $_SESSION['mensaje_registro'] = $resultado['msg'];
                $_SESSION['tipo_mensaje'] = "exito";
            } else {
                $_SESSION['mensaje_registro'] = $resultado['msg'];
                $_SESSION['tipo_mensaje'] = "error";
                
                // Limpiar logo si falló
                if ($logoNombre !== 'default_logo.png') {
                    $rutaLogo = __DIR__ . '/../../IMG/empresas/' . $logoNombre;
                    if (file_exists($rutaLogo)) @unlink($rutaLogo);
                }
            }
        } else {
            $_SESSION['mensaje_registro'] = "Error inesperado en el registro.";
            $_SESSION['tipo_mensaje'] = "error";
            
            if ($logoNombre !== 'default_logo.png') {
                $rutaLogo = __DIR__ . '/../../IMG/empresas/' . $logoNombre;
                if (file_exists($rutaLogo)) @unlink($rutaLogo);
            }
        }
        
    } catch (Exception $e) {
        // Capturar específicamente errores de SQL
        $errorMessage = $e->getMessage();
        
        if (strpos($errorMessage, 'SQL syntax') !== false) {
            $_SESSION['mensaje_registro'] = "Error en el sistema de base de datos. Por favor, contacte al administrador.";
            error_log("ERROR SQL en registro empresa: " . $errorMessage);
        } else {
            $_SESSION['mensaje_registro'] = "Error del sistema: " . $e->getMessage();
        }
        
        $_SESSION['tipo_mensaje'] = "error";
        
        // Limpiar logo
        if ($logoNombre !== 'default_logo.png') {
            $rutaLogo = __DIR__ . '/../../IMG/empresas/' . $logoNombre;
            if (file_exists($rutaLogo)) @unlink($rutaLogo);
        }
    }

    header("Location: ../../Vista/VistaRegistro/registrarEmpresa.php");
    exit();
    
} else {
    header("Location: ../../Vista/VistaRegistro/registrarEmpresa.php");
    exit();
}
?>
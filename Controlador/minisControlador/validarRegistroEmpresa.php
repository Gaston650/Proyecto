<?php
session_start();

// Incluir el wrapper correcto desde el superControlador
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';

// Solo procesar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_empresa'])) {
    $nombre = trim($_POST['nombre_empresa']);
    $email = trim($_POST['correo_empresa']);
    $zona = trim($_POST['zona_cobertura']);
    $telefono = trim($_POST['telefono']);
    $rut = trim($_POST['rut']);
    $password = $_POST['contrasena'];
    $confirmar = $_POST['confirmar_contrasena'];

    // Validar contraseñas
    if ($password !== $confirmar) {
        $_SESSION['mensaje_registro'] = "Las contraseñas no coinciden.";
        $_SESSION['tipo_mensaje'] = "error";
        header("Location: ../../Vista/VistaRegistro/registrarEmpresa.php");
        exit();
    }

    // Subida de logo
    if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['mensaje_registro'] = "Error al subir el logo.";
        $_SESSION['tipo_mensaje'] = "error";
        header("Location: ../../Vista/VistaRegistro/registrarEmpresa.php");
        exit();
    }

    $logoNombre = time() . '_' . basename($_FILES['logo']['name']);
    $logoTemp = $_FILES['logo']['tmp_name'];
    $rutaDestino = __DIR__ . '/../../IMG/empresas/' . $logoNombre;

    if (!file_exists(__DIR__ . '/../../IMG/empresas/')) {
        mkdir(__DIR__ . '/../../IMG/empresas/', 0777, true);
    }

    if (!move_uploaded_file($logoTemp, $rutaDestino)) {
        $_SESSION['mensaje_registro'] = "Error al mover el logo al directorio destino.";
        $_SESSION['tipo_mensaje'] = "error";
        header("Location: ../../Vista/VistaRegistro/registrarEmpresa.php");
        exit();
    }

    // Registrar empresa usando el wrapper
    $controlador = new empresaControladorWrapper();
    $resultado = $controlador->registrar($nombre, $email, $zona, $logoNombre, $telefono, $password, $rut);

    // Manejo de la respuesta según lo que devuelve controladorEmpresa
    if (is_array($resultado)) {
        if ($resultado['ok']) {
            $_SESSION['mensaje_registro'] = $resultado['msg'];
            $_SESSION['tipo_mensaje'] = "exito";
        } else {
            $_SESSION['mensaje_registro'] = $resultado['msg'];
            $_SESSION['tipo_mensaje'] = "error";
        }
    } else {
        $_SESSION['mensaje_registro'] = "Ocurrió un error inesperado al registrar la empresa.";
        $_SESSION['tipo_mensaje'] = "error";
    }

    // Redirigir a la vista de registro
    header("Location: ../../Vista/VistaRegistro/registrarEmpresa.php");
    exit();
}
?>

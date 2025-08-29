<?php
session_start();

require_once __DIR__ . '/controladorEmpresa.php';

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
        $_SESSION['mensaje_registro'] = "Error al subir el logo.";
        $_SESSION['tipo_mensaje'] = "error";
        header("Location: ../../Vista/VistaRegistro/registrarEmpresa.php");
        exit();
    }

    // Registrar empresa
    $controlador = new empresaControladorWrapper2();
    $resultado = $controlador->registrar($nombre, $email, $zona, $logoNombre, $rut, $password, $telefono);

    if ($resultado === "EMAIL_DUPLICADO") {
        $_SESSION['mensaje_registro'] = "El correo ya está registrado.";
        $_SESSION['tipo_mensaje'] = "error";
    } elseif ($resultado) {
        $_SESSION['mensaje_registro'] = "¡Registro exitoso! Ahora puedes iniciar sesión.";
        $_SESSION['tipo_mensaje'] = "exito";
    } else {
        $_SESSION['mensaje_registro'] = "No se pudo registrar la empresa.";
        $_SESSION['tipo_mensaje'] = "error";
    }

    // Redirigir de nuevo a la vista de registro
    header("Location: ../../Vista/VistaRegistro/registrarEmpresa.php");
    exit();
}
?>

<?php
session_start();
require_once __DIR__ . '/../superControlador/superControlador.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../Vista/VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero");
    exit();
}

$id_usuario = $_SESSION['user_id'];
$nombre     = $_POST['nombre'] ?? '';
$direccion  = $_POST['direccion'] ?? '';
$ciudad     = $_POST['ciudad'] ?? '';
$biografia  = $_POST['biografia'] ?? '';
$password   = $_POST['password'] ?? '';
$metodo_pago = $_POST['metodo_pago'] ?? null;
$foto = null;

// Imagen
if (isset($_FILES['imagenPerfil']) && $_FILES['imagenPerfil']['error'] === UPLOAD_ERR_OK) {
    $carpeta = __DIR__ . '/../../uploads/perfiles/';
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
    $nombreArchivo = "perfil_" . $id_usuario . "_" . time() . ".jpg";
    $rutaDestino = $carpeta . $nombreArchivo;
    move_uploaded_file($_FILES['imagenPerfil']['tmp_name'], $rutaDestino);

    $foto = "../../uploads/perfiles/" . $nombreArchivo;
}

// SuperControlador
$perfilControlador = new perfilControladorWrapper();

// Guardar perfil
$resultadoPerfil = $perfilControlador->guardarPerfil($id_usuario, $direccion, $ciudad, $biografia, $foto);

// Guardar método de pago
if ($metodo_pago) {
    $perfilControlador->guardarMetodoPago($id_usuario, $metodo_pago);
}

// Actualizo sesión
if (!empty($nombre)) {
    $_SESSION['user_nombre'] = $nombre;
}
if ($foto) {
    $_SESSION['user_image'] = $foto;
}

// Cambio de contraseña
if (!empty($password)) {
    require_once __DIR__ . '/../../Modelo/modeloRegistro.php';
    $usuarioModelo = new usuarioModelo();
    $hash = password_hash($password, PASSWORD_BCRYPT);

    $sql = "UPDATE usuarios SET contraseña = ? WHERE id_usuario = ?";
    $conn = (new conexion())->conectar();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hash, $id_usuario);
    $stmt->execute();
    $stmt->close();
}

if ($resultadoPerfil) {
     header("Location: ../../Vista/VistaEditarPerfil/editarPerfil.php?exito=1");
    exit();
} else {
    header("Location: ../../Vista/VistaEditarPerfil/editarPerfil.php?error=No se pudieron guardar los cambios");
    exit();
}
?>
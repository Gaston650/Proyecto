<?php
require_once __DIR__ . '../superControlador/superControlador.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_empresa'];
    $email = $_POST['correo_empresa'];
    $zona = $_POST['zona_cobertura'];
    $telefono = $_POST['telefono'];
    $rut = $_POST['rut'];
    $password = $_POST['contrasena'];
    $confirmar = $_POST['confirmar_contrasena'];

    if ($password !== $confirmar) {
        header("Location: ../Vista/VistaRegistro/registrarEmpresa.php?error=Las contraseñas no coinciden");
        exit();
    }

   
    $logoNombre = $_FILES['logo']['name'];
    $logoTemp = $_FILES['logo']['tmp_name'];
    $rutaDestino = '../uploads/' . $logoNombre;

    if (move_uploaded_file($logoTemp, $rutaDestino)) {
        $controlador = new empresaControlador();
        $resultado = $controlador->registrar($nombre, $email, $zona, $logoNombre, $rut, $password, $telefono);

        if ($resultado) {
            header("Location: ../Vista/VistaSesion/inicioSesion.php?exito=1");
        } else {
            header("Location: ../Vista/VistaRegistro/registrarEmpresa.php?error=No se pudo registrar la empresa");
        }
    } else {
        header("Location: ../Vista/VistaRegistro/registrarEmpresa.php?error=Error al subir el logo");
    }
}

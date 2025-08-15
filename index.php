<?php
include "Controlador/superControlador/superControlador.php";

if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['contrasena'];

    $controlador = new usuarioControlador();
    $controlador->guardarUsuario($nombre, $email, $password);

    header("Location: index.php?mensaje=ok");
    exit();
}
?>

<?php include "index.html"; ?>
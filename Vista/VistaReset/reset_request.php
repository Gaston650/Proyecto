<?php
require_once '../../Controlador/superControlador/superControlador.php';
require_once '../../conexion.php';

$conn = (new conexion())->conectar();
$controlador = new usuarioControladorWrapper($conn);

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $resultado = $controlador->solicitarReset($email);

    $msg = $resultado['msg'] ?? '';

    // Redirigir automáticamente al link con el token
    if (isset($resultado['link'])) {
        header("Location: " . $resultado['link']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="reset_request.css">
</head>
<body>
    <div class="container">
        <h2>Recuperar contraseña</h2>

        <?php if($msg): ?>
            <p class="message"><?=htmlspecialchars($msg)?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Ingresa tu correo electrónico" required>
            <button type="submit">Continuar</button>
        </form>

        <p class="back-login">
            <a href="../VistaSesion/inicioSesion.php">Volver a iniciar sesión</a>
        </p>
    </div>
</body>
</html>

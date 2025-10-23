<?php
session_start();
require_once __DIR__ . '/../../Controlador/superControlador/superControlador.php';
require_once __DIR__ . '/../../conexion.php';

$conn = (new conexion())->conectar();
$wrapper = new usuarioControladorWrapper($conn);

$token = $_GET['token'] ?? '';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $token = $_POST['token'] ?? '';

    $res = $wrapper->cambiarContrasena($token, $password, $password2);

    if (!$res['ok']) {
        $msg = $res['msg'];
    } else {
        $usuario = $wrapper->obtenerUsuarioPorId($res['id_usuario']);
        if ($usuario) {
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nombre'] = $usuario['nombre'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
            $_SESSION['email'] = $usuario['email'] ?? '';
            $_SESSION['user_image'] = $usuario['imagen'] ?? '../../IMG/perfil-vacio.png';
        }
        header("Location: ../../Vista/VistaPrincipal/home.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar contraseña</title>
    <link rel="stylesheet" href="reset_password.css">
    <style>
        .password-wrapper {
            position: relative;
            margin-bottom: 15px;
        }
        .password-wrapper input {
            width: 100%;
            padding: 10px 40px 10px 10px; /* espacio para el toggle */
            border-radius: 5px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
        }
        .password-wrapper .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #3a86ff;
            font-size: 0.9rem;
            user-select: none;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Cambiar contraseña</h2>

        <?php if($msg): ?>
            <p class="msg-error"><?=htmlspecialchars($msg)?></p>
        <?php endif; ?>

        <form method="POST" class="reset-form">
            <input type="hidden" name="token" value="<?=htmlspecialchars($token)?>">
            
            <label for="password">Nueva contraseña</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Escribe tu nueva contraseña" required>
                <span class="toggle-password" onclick="togglePassword('password')">Mostrar</span>
            </div>

            <label for="password2">Confirmar contraseña</label>
            <div class="password-wrapper">
                <input type="password" id="password2" name="password2" placeholder="Confirma tu nueva contraseña" required>
                <span class="toggle-password" onclick="togglePassword('password2')">Mostrar</span>
            </div>

            <button type="submit">Cambiar contraseña</button>
        </form>
        <p class="back-login">
        <a href="../VistaSesion/inicioSesion.php">Volver a iniciar sesión</a>
    </p>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'Ocultar';
            } else {
                input.type = 'password';
                icon.textContent = 'Mostrar';
            }
        }
    </script>
</body>
</html>

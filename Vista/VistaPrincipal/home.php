<?php
session_start();
// Verifico si el usuario o la empresa ha iniciado sesión
if (!isset($_SESSION['user_id']) && !isset($_SESSION['empresa_id'])) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
   <nav>
       <div class="foto-perfil" style="background-image: url(
        '<?php 
        if (isset($_SESSION['user_image'])) {
                echo $_SESSION['user_image'];
            } else {
                echo '../../img/perfil-vacio.png';
            }
        ?>'
        );"></div>


    <h1>
        ¡Bienvenido,
        <?php
            if (isset($_SESSION['user_nombre'])) {
                echo htmlspecialchars($_SESSION['user_nombre']);
            } elseif (isset($_SESSION['nombre'])) {
                echo htmlspecialchars($_SESSION['nombre']);
            } elseif (isset($_SESSION['nombre_empresa'])) {
                echo htmlspecialchars($_SESSION['nombre_empresa']);
            } else {
                echo 'usuario';
            }
        ?>!
    </h1>
    <form action="../../Controlador/cerrar_sesion.php" method="POST">
        <button type="submit">Cerrar Sesión</button>
    </form>
   </nav>

   <main class="main-content">
       <?php if (isset($_SESSION['empresa_id']) && !empty($_SESSION['logo_empresa'])): ?>
           <div class="logo-empresa">
               <img src="<?php echo htmlspecialchars($_SESSION['logo_empresa']); ?>" alt="Logo Empresa" />
           </div>
       <?php endif; ?>

       <p>Has iniciado sesión correctamente.</p>
   </main>
</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class controladorCerrarSesion {
    public function cerrar() {
        // Limpiamos todas las variables de sesión
        $_SESSION = [];

        // Destruimos la sesión
        session_destroy();

        // Redirigimos al inicio de sesión
        header("Location: ../index.php.");
        exit();
    }
}
?>

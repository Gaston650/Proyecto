<?php
class controladorCerrarSesion {
    public function cerrar() {
        session_start();
        session_unset();
        session_destroy();
        return true;
    }
}

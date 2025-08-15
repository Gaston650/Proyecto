<?php
class conexion {
    public function conectar() {
        $conn = new mysqli("localhost", "root", "gaston2006", "ClickSoft");
        if ($conn->connect_error) {
            die("Error de conexion: " . $conn->connect_error);
        } else {
            return $conn;
        }
    }
}
?>
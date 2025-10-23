<?php
require_once __DIR__ . '/../../Modelo/modeloRecEmpresa.php';

class empresaControlador {
    private $modelo;

    public function __construct($conn = null) {
        $this->modelo = new modeloRecEmpresa();
    }

    public function solicitarResetEmpresa($email) {
        $empresa = $this->modelo->obtenerEmpresaPorEmail($email);
        if (!$empresa) return ['msg' => 'No se encontró ninguna empresa con ese correo electrónico.'];

        $tokenData = $this->modelo->crearRecuperacionEmpresa($empresa['id_empresa']);
        if (!$tokenData['exito']) return ['msg' => 'Error al generar el enlace de recuperación.'];

        $link = "http://localhost/ClickSoft/Vista/VistaReset/reset_password_empresa.php?token=" . $tokenData['token'];

        return [
            'msg' => 'Enlace de recuperación generado correctamente.',
            'link' => $link
        ];
    }

    public function validarTokenEmpresa($token) {
        $datos = $this->modelo->validarTokenEmpresa($token);
        if (!$datos) return ['valido' => false, 'msg' => 'Token inválido o expirado.'];

        return ['valido' => true, 'datos' => $datos];
    }

    public function resetearContrasenaEmpresa($token, $nuevaContrasena) {
        $tokenInfo = $this->modelo->validarTokenEmpresa($token);
        if (!$tokenInfo) return ['exito' => false, 'msg' => 'El token no es válido o ha expirado.'];

        $id_empresa = $tokenInfo['id_empresa'];

        $this->modelo->actualizarContrasenaEmpresa($id_empresa, $nuevaContrasena);
        $this->modelo->marcarTokenUsadoEmpresa($tokenInfo['id_recuperacion']);

        return ['exito' => true, 'msg' => 'Contraseña actualizada correctamente.'];
    }

    public function obtenerEmpresaPorId($id_empresa) {
        return $this->modelo->obtenerEmpresaPorId($id_empresa);
    }
}
?>


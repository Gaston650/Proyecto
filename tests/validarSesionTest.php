<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Controlador/minisControlador/validarSesion.php';

class ValidarSesionTest extends TestCase {
    private $usuarioMock;
    private $empresaMock;
    private $perfilMock;

    protected function setUp(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $_SESSION = [];

        // Mock para usuarioModelo
        $this->usuarioMock = $this->getMockBuilder(stdClass::class)
            ->addMethods(['obtenerUsuarioPorEmail', 'guardarToken'])
            ->getMock();

        // Mock para empresaModelo
        $this->empresaMock = $this->getMockBuilder(stdClass::class)
            ->addMethods(['obtenerEmpresa', 'actualizarEmpresaRememberToken'])
            ->getMock();

        // Mock para perfilModelo
        $this->perfilMock = $this->getMockBuilder(stdClass::class)
            ->addMethods(['obtenerPerfil'])
            ->getMock();
    }

    private function runLogin(array $postData, string $tipoUsuario) {
        $_POST = $postData;

        // Reemplazar los modelos en el script
        $GLOBALS['usuarioModelo'] = $this->usuarioMock;
        $GLOBALS['empresaModelo'] = $this->empresaMock;
        $GLOBALS['perfilModelo'] = $this->perfilMock;

        // Capturar headers para no romper PHPUnit
        $headers = [];
        set_error_handler(function(){}); // Ignorar headers
        ob_start();
        include __DIR__ . '/../Controlador/minisControlador/validarSesion.php';
        ob_end_clean();
        restore_error_handler();
    }

    public function testClienteExitoso() {
        $hashed = password_hash('123456', PASSWORD_DEFAULT);

        $this->usuarioMock->method('obtenerUsuarioPorEmail')
            ->willReturn([
                'id_usuario' => 1,
                'nombre' => 'Juan',
                'email' => 'juan@test.com',
                'password' => $hashed,
                'imagen_google' => ''
            ]);

        $this->perfilMock->method('obtenerPerfil')
            ->willReturn(['foto_perfil' => 'perfil.png']);

        $this->runLogin([
            'iniciar_sesion' => true,
            'correo' => 'juan@test.com',
            'contrasena' => '123456',
            'tipo_usuario' => 'cliente'
        ], 'cliente');

        $this->assertEquals(1, $_SESSION['user_id']);
        $this->assertEquals('Juan', $_SESSION['user_nombre']);
        $this->assertEquals('cliente', $_SESSION['tipo_usuario']);
        $this->assertEquals('perfil.png', $_SESSION['user_image']);
    }

    public function testEmpresaExitoso() {
        $hashed = password_hash('123456', PASSWORD_DEFAULT);

        $this->empresaMock->method('obtenerEmpresa')
            ->willReturn([
                'id_empresa' => 2,
                'nombre_empresa' => 'Empresa Test',
                'email_empresa' => 'empresa@test.com',
                'contraseña' => $hashed,
                'logo' => 'logo.png',
                'estado' => 'activo'
            ]);

        $this->runLogin([
            'iniciar_sesion' => true,
            'correo' => 'empresa@test.com',
            'contrasena' => '123456',
            'tipo_usuario' => 'empresa'
        ], 'empresa');

        $this->assertEquals(2, $_SESSION['user_id']);
        $this->assertEquals('Empresa Test', $_SESSION['user_nombre']);
        $this->assertEquals('empresa', $_SESSION['tipo_usuario']);
        $this->assertStringContainsString('logo.png', $_SESSION['user_image']);
    }

    public function testAdministradorExitoso() {
        $hashed = password_hash('admin123', PASSWORD_DEFAULT);

        $this->usuarioMock->method('obtenerUsuarioPorEmail')
            ->willReturn([
                'id_usuario' => 3,
                'nombre' => 'Admin',
                'email' => 'admin@test.com',
                'password' => $hashed,
                'tipo_usuario' => 'administrador'
            ]);

        $this->runLogin([
            'iniciar_sesion' => true,
            'correo' => 'admin@test.com',
            'contrasena' => 'admin123',
            'tipo_usuario' => 'administrador'
        ], 'administrador');

        $this->assertEquals(3, $_SESSION['user_id']);
        $this->assertEquals('Admin', $_SESSION['user_nombre']);
        $this->assertEquals('administrador', $_SESSION['tipo_usuario']);
        $this->assertEquals('../../IMG/admin.png', $_SESSION['user_image']);
    }
}
?>

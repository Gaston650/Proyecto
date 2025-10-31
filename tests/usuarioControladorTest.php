<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Controlador/minisControlador/usuarioControlador.php';

class UsuarioControladorTest extends TestCase {
    private $modeloMock;
    private $controlador;

    protected function setUp(): void {
        // Crear mock del modelo
        $this->modeloMock = $this->getMockBuilder(stdClass::class)
            ->addMethods([
                'insertarUsuario',
                'obtenerUsuarioPorEmail',
                'guardarToken',
                'registrarUsuarioGoogle',
                'obtenerUsuarioPorId',
                'obtenerUsuarioPorToken'
            ])
            ->getMock();

        // Crear el controlador inyectando el mock
        $this->controlador = new UsuarioControlador(null, $this->modeloMock);
    }

    /** @test */
    public function testGuardarUsuario() {
        $this->modeloMock->method('insertarUsuario')->willReturn(true);

        $result = $this->controlador->guardarUsuario('Juan', 'juan@test.com', '123456');
        $this->assertTrue($result);
    }

    /** @test */
    public function testLoginUsuarioExitoso() {
        $hashed = password_hash('123456', PASSWORD_DEFAULT);
        $this->modeloMock->method('obtenerUsuarioPorEmail')->willReturn([
            'id_usuario' => 1,
            'nombre' => 'Juan',
            'email' => 'juan@test.com',
            'password' => $hashed,
            'tipo_usuario' => 'cliente'
        ]);

        $result = $this->controlador->loginUsuario('juan@test.com', '123456');
        $this->assertTrue($result['ok']);
        $this->assertEquals('Inicio de sesión exitoso.', $result['msg']);
    }

    /** @test */
    public function testLoginUsuarioFallido() {
        $this->modeloMock->method('obtenerUsuarioPorEmail')->willReturn(null);

        $result = $this->controlador->loginUsuario('noexiste@test.com', '123456');
        $this->assertFalse($result['ok']);
        $this->assertEquals('Correo o contraseña incorrectos.', $result['msg']);
    }

    /** @test */
    public function testLoginGoogleUsuarioExistente() {
        $this->modeloMock->method('obtenerUsuarioPorEmail')->willReturn([
            'id_usuario' => 1,
            'nombre' => 'Juan',
            'email' => 'juan@test.com'
        ]);

        $usuario = $this->controlador->loginGoogle('Juan', 'juan@test.com');
        $this->assertEquals(1, $usuario['id_usuario']);
        $this->assertEquals('Juan', $usuario['nombre']);
    }

    /** @test */
    public function testLoginGoogleUsuarioNuevo() {
        $this->modeloMock->method('obtenerUsuarioPorEmail')->willReturn(null);
        $this->modeloMock->method('registrarUsuarioGoogle')->willReturn(2);
        $this->modeloMock->method('obtenerUsuarioPorId')->willReturn([
            'id_usuario' => 2,
            'nombre' => 'Ana',
            'email' => 'ana@test.com'
        ]);

        $usuario = $this->controlador->loginGoogle('Ana', 'ana@test.com');
        $this->assertEquals(2, $usuario['id_usuario']);
        $this->assertEquals('Ana', $usuario['nombre']);
    }
}
?>

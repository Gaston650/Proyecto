<?php
use PHPUnit\Framework\TestCase;

// Ajusta la ruta a tu controlador
require_once __DIR__ . '/../Controlador/minisControlador/controladorEmpresa.php';

class ControladorEmpresaTest extends TestCase {
    private $modeloMock;
    private $controlador;

    protected function setUp(): void {
        // Crear mock del modelo de empresa
        $this->modeloMock = $this->getMockBuilder(stdClass::class)
            ->addMethods([
                'obtenerEmpresa',
                'insertarEmpresa',
                'insertarTelefono',
                'guardarToken'
            ])
            ->getMock();

        // Crear el controlador real con el mock
        $this->controlador = new controladorEmpresa($this->modeloMock);
    }

    /** @test */
    public function testRegistrarEmpresaExito() {
        $this->modeloMock->method('obtenerEmpresa')
            ->will($this->onConsecutiveCalls(false, ['id_empresa' => 1])); // primera llamada no existe, segunda existe
        $this->modeloMock->method('insertarEmpresa')->willReturn(true);
        $this->modeloMock->method('insertarTelefono')->willReturn(true);

        $result = $this->controlador->registrarEmpresa(
            'Empresa Test',
            'empresa@test.com',
            'Zona 1',
            'logo.png',
            '099999999',
            '123456',
            '12345678-9'
        );

        $this->assertTrue($result['ok']);
        $this->assertEquals('Empresa registrada correctamente.', $result['msg']);
    }

    /** @test */
    public function testRegistrarEmpresaCorreoExistente() {
        $this->modeloMock->method('obtenerEmpresa')->willReturn(['id_empresa' => 1]);

        $result = $this->controlador->registrarEmpresa(
            'Empresa Test',
            'empresa@test.com',
            'Zona 1',
            'logo.png',
            '099999999',
            '123456',
            '12345678-9'
        );

        $this->assertFalse($result['ok']);
        $this->assertEquals('El correo ya está registrado.', $result['msg']);
    }

    /** @test */
    public function testLoginEmpresaExitoso() {
        $hashed = password_hash('123456', PASSWORD_DEFAULT);
        $this->modeloMock->method('obtenerEmpresa')->willReturn([
            'id_empresa' => 1,
            'nombre_empresa' => 'Empresa Test',
            'email_empresa' => 'empresa@test.com',
            'contraseña' => $hashed
        ]);

        $result = $this->controlador->loginEmpresa('empresa@test.com', '123456');

        $this->assertTrue($result['ok']);
        $this->assertEquals('Inicio de sesión exitoso.', $result['msg']);
    }

    /** @test */
    public function testLoginEmpresaFallido() {
        $this->modeloMock->method('obtenerEmpresa')->willReturn(null);

        $result = $this->controlador->loginEmpresa('noexiste@test.com', '123456');

        $this->assertFalse($result['ok']);
        $this->assertEquals('Correo o contraseña incorrectos.', $result['msg']);
    }
}
?>

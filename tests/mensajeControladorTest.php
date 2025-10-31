<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../Controlador/minisControlador/controladorMensaje.php';

class MensajeControladorTest extends TestCase {
    private $modeloMock;
    private $controlador;

    protected function setUp(): void {
        // Crear mock del modeloMensaje
        $this->modeloMock = $this->getMockBuilder(stdClass::class)
            ->addMethods([
                'obtenerConversacion',
                'obtenerMensajesParaCliente',
                'obtenerMensajesParaEmpresa',
                'insertarMensaje',
                'marcarMensajesLeidos'
            ])
            ->getMock();

        // Inyectar el mock en el controlador
        $this->controlador = new mensajeControlador($this->modeloMock);
    }

    /** @test */
    public function testEnviarMensajeExito() {
        $this->modeloMock->method('insertarMensaje')->willReturn(true);
        $result = $this->controlador->enviarMensaje(1, 'cliente', 2, 'empresa', 10, 'Hola');
        $this->assertTrue($result);
    }

    /** @test */
    public function testObtenerMensajesCliente() {
        $this->modeloMock->method('obtenerMensajesParaCliente')->willReturn([['mensaje' => 'Hola']]);
        $mensajes = $this->controlador->obtenerMensajesCliente(1);
        $this->assertIsArray($mensajes);
        $this->assertCount(1, $mensajes);
        $this->assertEquals('Hola', $mensajes[0]['mensaje']);
    }

    /** @test */
    public function testObtenerMensajesEmpresa() {
        $this->modeloMock->method('obtenerMensajesParaEmpresa')->willReturn([['mensaje' => 'Hola empresa']]);
        $mensajes = $this->controlador->obtenerMensajesEmpresa(2);
        $this->assertIsArray($mensajes);
        $this->assertCount(1, $mensajes);
        $this->assertEquals('Hola empresa', $mensajes[0]['mensaje']);
    }

    /** @test */
    public function testMarcarMensajesLeidos() {
        $this->modeloMock->method('marcarMensajesLeidos')->willReturn(true);
        $result = $this->controlador->marcarMensajesLeidos(2, 1, 10);
        $this->assertTrue($result);
    }

    /** @test */
    public function testManejarConversacionMensajeVacio() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['contenido'] = '';

        $this->modeloMock->method('obtenerConversacion')->willReturn([]);
        $result = $this->controlador->manejarConversacion(1, 'cliente', 2, 'empresa', 10);
        $this->assertEquals("⚠️ El mensaje no puede estar vacío.", $result['error']);
    }

    /** @test */
    public function testManejarConversacionMensajeEnviado() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['contenido'] = 'Hola';

        $this->modeloMock->method('insertarMensaje')->willReturn(true);
        $this->modeloMock->method('obtenerConversacion')->willReturn([['mensaje' => 'Hola']]);

        $result = $this->controlador->manejarConversacion(1, 'cliente', 2, 'empresa', 10);
        $this->assertEquals("✅ Mensaje enviado correctamente.", $result['exito']);
        $this->assertCount(1, $result['mensajes']);
    }
}
?>

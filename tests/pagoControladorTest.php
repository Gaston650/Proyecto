<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../Controlador/minisControlador/controladorPago.php';

class PagoControladorTest extends TestCase {
    private $modeloMock;
    private $controlador;

    protected function setUp(): void {
        // Mock del modeloPago
        $this->modeloMock = $this->getMockBuilder(stdClass::class)
            ->addMethods([
                'insertarPago',
                'obtenerPagos',
                'obtenerPagosPorReserva',
                'obtenerVentasDelMes'
            ])
            ->getMock();

        // Controlador con inyección del mock
        $this->controlador = new pagoControlador($this->modeloMock);
    }

    /** @test */
    public function testGuardarPagoExito() {
        $this->modeloMock->method('insertarPago')->willReturn(true);

        $result = $this->controlador->guardarPago(1, 1000);
        $this->assertTrue($result);
    }

    /** @test */
    public function testObtenerPagos() {
        $this->modeloMock->method('obtenerPagos')->willReturn([['id_pago' => 1, 'monto' => 1000]]);
        
        $pagos = $this->controlador->obtenerPagos();
        $this->assertIsArray($pagos);
        $this->assertCount(1, $pagos);
    }

    /** @test */
    public function testObtenerPagosPorReserva() {
        $this->modeloMock->method('obtenerPagosPorReserva')->willReturn([['id_pago' => 1, 'monto' => 1000]]);
        
        $pagos = $this->controlador->obtenerPagosPorReserva(1);
        $this->assertIsArray($pagos);
        $this->assertEquals(1000, $pagos[0]['monto']);
    }

    /** @test */
    public function testObtenerVentasDelMes() {
        $this->modeloMock->method('obtenerVentasDelMes')->willReturn(5000);

        $ventas = $this->controlador->obtenerVentasDelMes(1);
        $this->assertEquals(5000, $ventas);
    }
}
?>

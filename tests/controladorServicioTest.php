<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../Controlador/minisControlador/controladorServicio.php';

class controladorServicioTest extends TestCase {
    private $modeloMock;
    private $controlador;

    protected function setUp(): void {
        $this->modeloMock = $this->getMockBuilder(stdClass::class)
            ->addMethods([
                'obtenerServiciosPorEmpresa',
                'obtenerServiciosActivos',
                'publicarServicio',
                'eliminarServicio',
                'editarServicio',
                'obtenerServiciosFiltrados',
                'obtenerCategorias',
                'obtenerServicioPorId',
                'actualizarDisponibilidad'
            ])
            ->getMock();

        // 🔹 Inyectamos el mock directamente
        $this->controlador = new controladorServicio($this->modeloMock);
    }

    public function testListarServiciosEmpresa() {
        $this->modeloMock->method('obtenerServiciosPorEmpresa')
            ->with(5)
            ->willReturn([['id_servicio'=>1,'titulo'=>'Plomería']]);

        $result = $this->controlador->listarServiciosEmpresa(5);
        $this->assertCount(1, $result);
        $this->assertEquals('Plomería', $result[0]['titulo']);
    }

    public function testListarServiciosActivos() {
        $this->modeloMock->method('obtenerServiciosActivos')
            ->willReturn([['id_servicio'=>1], ['id_servicio'=>2]]);

        $result = $this->controlador->listarServiciosActivos();
        $this->assertCount(2, $result);
    }

    public function testPublicarServicio() {
        $this->modeloMock->expects($this->once())
            ->method('publicarServicio')
            ->willReturn(true);

        $result = $this->controlador->publicarServicio(1,'Limpieza','Completo','Hogar','Zona',1000,'Lunes a Viernes');
        $this->assertTrue($result);
    }

    public function testBorrarServicio() {
        $this->modeloMock->method('eliminarServicio')->willReturn(true);
        $this->assertTrue($this->controlador->borrarServicio(1,1));
    }

    public function testEditarServicio() {
        $this->modeloMock->method('editarServicio')->willReturn(true);
        $this->assertTrue($this->controlador->editarServicio(1,'T','D','C','Z',100,'Lunes'));
    }

    public function testListarServiciosFiltrados() {
        $this->modeloMock->method('obtenerServiciosFiltrados')->willReturn([['id_servicio'=>1]]);
        $result = $this->controlador->listarServiciosFiltrados('bus','zona','cat');
        $this->assertCount(1,$result);
    }

    public function testListarCategorias() {
        $this->modeloMock->method('obtenerCategorias')
            ->willReturn([['nombre_categoria'=>'Hogar'],['nombre_categoria'=>'Jardinería']]);
        $result = $this->controlador->listarCategorias();
        $this->assertCount(2,$result);
    }

    public function testObtenerServicio() {
        $this->modeloMock->method('obtenerServicioPorId')
            ->willReturn(['id_servicio'=>1,'titulo'=>'Prueba']);
        $result = $this->controlador->obtenerServicio(1);
        $this->assertEquals('Prueba',$result['titulo']);
    }

    public function testActualizarDisponibilidad() {
        $this->modeloMock->method('actualizarDisponibilidad')->willReturn(true);
        $this->assertTrue($this->controlador->actualizarDisponibilidad(1,'Lunes a Viernes'));
    }
}

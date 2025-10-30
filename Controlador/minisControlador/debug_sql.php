<?php
// debug_sql.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$rutaControlador = __DIR__ . '/../../Controlador/superControlador/superControlador.php';
require_once $rutaControlador;

echo "<h3>Depurando consulta SQL</h3>";

$controlador = new empresaControladorWrapper();

// Usar Reflection para ver el código del método
$reflection = new ReflectionClass($controlador);
try {
    $method = $reflection->getMethod('registrar');
    echo "Método registrar encontrado.<br>";
    
    // Mostrar el archivo y línea donde está definido
    echo "Definido en: " . $method->getFileName() . "<br>";
    echo "Línea: " . $method->getStartLine() . " a " . $method->getEndLine() . "<br>";
    
} catch (ReflectionException $e) {
    echo "No se pudo obtener información del método.<br>";
}

// Probar con datos simples
echo "<h4>Probando con datos simples:</h4>";
try {
    $resultado = $controlador->registrar(
        'Empresa Test',
        'test@test.com', 
        'Centro',
        'logo.png',
        '123456789',
        'password123',
        '12345678-9'
    );
    
    echo "Resultado: ";
    echo "<pre>" . print_r($resultado, true) . "</pre>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    
    // Mostrar traza completa
    echo "<h4>Traza del error:</h4>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
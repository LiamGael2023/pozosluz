<?php
/**
 * Punto de entrada de la aplicación
 * Calculadora de Pozos de Luz - RNE Perú
 */

// Configurar zona horaria
date_default_timezone_set('America/Lima');

// Mostrar errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir constantes
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Detectar BASE_URL automáticamente
$scriptName = $_SERVER['SCRIPT_NAME'];
$baseUrl = rtrim(dirname($scriptName), '/\\');
define('BASE_URL', $baseUrl ?: '');

// Autoloader simple
spl_autoload_register(function ($class) {
    // Convertir namespace a path
    $prefix = 'App\\';

    if (strpos($class, $prefix) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = APP_PATH . '/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Cargar configuración
$config = require BASE_PATH . '/config/app.php';

// Crear router y definir rutas
$router = new App\Core\Router();

// Rutas de la aplicación
$router->get('/', [App\Controllers\CalculadoraController::class, 'index']);
$router->post('/calcular', [App\Controllers\CalculadoraController::class, 'calcular']);
$router->get('/historial', [App\Controllers\CalculadoraController::class, 'historial']);

// Despachar la solicitud
$router->dispatch();

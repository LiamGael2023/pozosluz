<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remover el path base si existe
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = $uri ?: '/';

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "Página no encontrada";
            return;
        }

        $handler = $this->routes[$method][$uri];
        $controllerClass = $handler[0];
        $method = $handler[1];

        if (!class_exists($controllerClass)) {
            die("Controlador no encontrado: {$controllerClass}");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            die("Método no encontrado: {$method}");
        }

        $controller->$method();
    }
}

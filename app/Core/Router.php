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
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Limpiar la URI - remover trailing slashes excepto para root
        $uri = $uri === '/' ? '/' : rtrim($uri, '/');

        // Si la URI está vacía, es root
        if (empty($uri)) {
            $uri = '/';
        }

        if (!isset($this->routes[$requestMethod][$uri])) {
            http_response_code(404);
            echo "Página no encontrada: {$requestMethod} {$uri}";
            return;
        }

        $handler = $this->routes[$requestMethod][$uri];
        $controllerClass = $handler[0];
        $actionMethod = $handler[1];

        if (!class_exists($controllerClass)) {
            die("Controlador no encontrado: {$controllerClass}");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $actionMethod)) {
            die("Método no encontrado: {$actionMethod}");
        }

        $controller->$actionMethod();
    }
}

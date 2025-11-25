<?php
declare(strict_types=1);

namespace App\Core;

final class Router {
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, array $handler): void {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, array $handler): void {
        // Expresión regular para convertir /ruta/{param} en /ruta/(\w+)
        $pattern = preg_replace('/\{(\w+)\}/', '(?<$1>\d+)', $path);
        $this->routes[$method]['#^' . $pattern . '$#'] = $handler;
    }

    public function dispatch(string $method, string $uri): void {
        $path = $this->normalizePath(parse_url($uri, PHP_URL_PATH) ?: '/');

        foreach ($this->routes[strtoupper($method)] as $pattern => $handler) {
            if (preg_match($pattern, $path, $matches)) {

                // Extraer parámetros (solo los nombrados)
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                [$class, $action] = $handler;

                if (!class_exists($class)) {
                    // Carga simple (si no usás Composer)
                    $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
                    if (file_exists($file)) require_once $file;
                }

                $controller = new $class();
                // Pasar parámetros al método
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        http_response_code(404);
        echo '404 - Ruta no encontrada';
    }

    private function normalizePath(string $path): string {
        $path = '/' . trim($path, '/');
        return $path === '//' ? '/' : $path;
    }
}

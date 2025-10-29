<?php
declare(strict_types=1);

namespace App\Core;

final class Router {
  private array $routes = ['GET'=>[], 'POST'=>[]];

  public function get(string $path, array $handler): void {
    $this->routes['GET'][$this->n($path)] = $handler;
  }
  public function post(string $path, array $handler): void {
    $this->routes['POST'][$this->n($path)] = $handler;
  }

  public function dispatch(string $method, string $uri): void {
    $path = $this->n(parse_url($uri, PHP_URL_PATH) ?: '/');
    $handler = $this->routes[strtoupper($method)][$path] ?? null;

    if (!$handler) { http_response_code(404); echo '404'; return; }

    [$class, $action] = $handler;

    // Carga simple (si no usás Composer)
    if (!class_exists($class)) {
      $file = __DIR__ . '/../' . str_replace('\\','/',$class) . '.php';
      if (file_exists($file)) require_once $file;
    }

    $controller = new $class();
    $controller->$action();
  }

  private function n(string $p): string { $p = '/'.trim($p,'/'); return $p === '//' ? '/' : $p; }
}

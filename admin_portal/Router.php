<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn): void
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn): void
    {
        $this->postRoutes[$url] = $fn;
    }

    public function checkRoutes()
    {

        $current_url = $_SERVER['PATH_INFO'] ?? $_SERVER["REQUEST_URI"] ?? "/";
        $current_url = strtok($current_url, "?"); // Eliminar querys params
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = $this->getRoutes[$current_url] ?? null;
        } else {
            $fn = $this->postRoutes[$current_url] ?? null;
        }

        if ($fn && is_callable($fn)) {
            call_user_func($fn, $this);
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 - Página No Encontrada</h1>";;
        }
    }

    public function render(string $view, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once __DIR__ . "/views/$view.php";
        $content = ob_get_clean(); // Limpia el Buffer
        include_once __DIR__ . '/views/layout.php';
    }
}

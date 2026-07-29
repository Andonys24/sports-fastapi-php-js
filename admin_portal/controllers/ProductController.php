<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class ProductController
{

    public static function index(Router $router)
    {
        session_start();
        isAdmin();

        $api = new ApiClient();

        // Datos de Prueba
        $products = [
            ['id' => 1, 'name' => 'Balon de Futbol Nike', 'price' => '45.00', 'category_id' => 1],
            ['id' => 2, 'name' => 'Camiseta Deportiva Adidas', 'price' => '30.00', 'category_id' => 2],
        ];

        $categories = [
            ['id' => 1, 'name' => 'Futbol'],
            ['id' => 2, 'name' => 'Ropa Deportivo']
        ];

        // $products = $api->get("/products") ?? [];
        // $categories = $api->get("/categories") ?? [];

        $router->render("products/index", [
            "title" => "Productos",
            "products" => $products,
            "categories" => $categories
        ]);
    }

    public static function create(Router $router)
    {
        session_start();
        isAdmin();

        $alerts = [];
        $product = [];
        $api = new ApiClient();
        $categories = $api->get('/categories') ?? [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = [
                'name'        => $_POST['nombre'] ?? '',
                'price'       => $_POST['precio'] ?? 0,
                'category_id' => $_POST['categoria_id'] ?? ''
            ];

            $response = $api->post('/products', $payload);

            if ($response && !isset($response['error'])) {
                header('Location: /products');
                exit;
            }

            $alerts['error'][] = $response['error'] ?? 'Error al crear el producto';
        }

        $router->render('products/create', [
            'title'     => 'Nuevo Producto',
            'alerts'     => $alerts,
            'categories' => $categories,
            'product'    => $product
        ]);
    }

    public static function update(Router $router)
    {
        session_start();
        isAdmin();
        $alerts = [];
        $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            header("Location: /products");
            exit;
        }

        $api = new ApiClient();
        $product = $api->get("/products/{$id}");
        $categories = $api->get("/categories") ?? [];

        if (!$product) {
            header('Location: /products');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = [
                'name'        => $_POST['nombre'] ?? '',
                'price'       => $_POST['precio'] ?? 0,
                'category_id' => $_POST['categoria_id'] ?? ''
            ];

            $response = $api->put("/products/{$id}", $payload);

            if ($response && !isset($response['error'])) {
                header('Location: /products');
                exit;
            }

            $alerts['error'][] = $response['error'] ?? 'Error al actualizar el producto';
        }

        $router->render('products/update', [
            'title'     => 'Actualizar Producto',
            'alerts'     => $alerts,
            'product'    => $product,
            'categories' => $categories
        ]);
    }

    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            isAdmin();

            $api = new ApiClient();
            $id = $_POST['id'] ?? null;
            $response = $api->delete("/products/{$id}");

            if ($response) {
                header('Content-Type: application/json');
                echo json_encode([
                    'resultado' => true,
                    'mensaje'   => 'Producto Eliminado Exitosamente'
                ]);
                exit;
            }
        }
    }
}

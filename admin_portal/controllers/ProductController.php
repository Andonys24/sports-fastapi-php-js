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

        $products = $api->get("/products") ?? [];
        $categories = $api->get("/categories") ?? [];

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

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /products');
                exit;
            }

            $alerts['error'][] = $response['detail'] ?? $response['error'] ?? 'Error al crear el producto';
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

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /products');
                exit;
            }

            $alerts['error'][] = $response['detail'] ?? $response['error'] ?? 'Error al actualizar el producto';
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
                if (!isset($response['detail']) && !isset($response['error'])) {
                    echo json_encode([
                        'resultado' => true,
                        'mensaje'   => 'Producto Eliminado Exitosamente'
                    ]);
                } else {
                    echo json_encode([
                        'resultado' => false,
                        'mensaje'   => $response['detail'] ?? $response['error'] ?? 'Error al eliminar el producto'
                    ]);
                }
                exit;
            }
        }
    }
}

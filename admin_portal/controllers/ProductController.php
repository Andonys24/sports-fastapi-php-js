<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class ProductController
{

    public static function index(Router $router)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        isAdmin();

        $api = new ApiClient();

        $products = $api->get("/products") ?? [];
        $categoriesRaw = $api->get("/categories") ?? [];
        $categories = array_reduce($categoriesRaw, function ($acc, $cat) {
            if (isset($cat['id'])) {
                $acc[$cat['id']] = $cat['name'] ?? '';
            }
            return $acc;
        }, []);


        $providersResponse = $api->get('/suppliers');
        $providersRaw = is_array($providersResponse) && array_is_list($providersResponse) ? $providersResponse : [];
        $providers = array_reduce($providersRaw, function ($acc, $prov) {
            if (isset($prov['id'])) {
                $acc[$prov['id']] = $prov['name'] ?? '';
            }
            return $acc;
        }, []);

        $router->render("products/index", [
            "title" => "Productos",
            "products" => $products,
            "categories" => $categories,
            'providers' => $providers,
        ]);
    }

    private static function getPayload(): array
    {
        return [
            'category_id' => $_POST['categoria_id'] ?? 0,
            'provider_id' => $_POST['provider_id'] ?? 0,
            'name'        => $_POST['nombre'] ?? '',
            'price'       => (float) ($_POST['precio'] ?? 0),
            'brand'       => $_POST['brand'] ?? '',
            'stock'       => (int) ($_POST['stock'] ?? 0),
            'img_url'     => $_POST['img_url'] ?? ''
        ];
    }

    public static function create(Router $router)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        isAdmin();

        $alerts = [];
        $product = [];
        $api = new ApiClient();
        $categories = $api->get('/categories') ?? [];
        $providersResponse = $api->get('/suppliers');
        $providers = is_array($providersResponse) && array_is_list($providersResponse) ? $providersResponse : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();

            $response = $api->post('/products', $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /products');
                exit;
            }

            // Capturar error de forma segura
            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al crear el producto';

            if (is_array($rawError)) {
                $errorMessage = $rawError[0]['msg'] ?? json_encode($rawError);
            } else {
                $errorMessage = $rawError;
            }

            $alerts['error'][] = is_string($errorMessage) ? $errorMessage : 'Error desconocido en la API';
        }

        $router->render('products/create', [
            'title'     => 'Nuevo Producto',
            'alerts'     => $alerts,
            'categories' => $categories,
            'product'    => $product,
            'providers' => $providers,
        ]);
    }

    public static function update(Router $router)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
        $providersResponse = $api->get('/suppliers');
        $providers = is_array($providersResponse) && array_is_list($providersResponse) ? $providersResponse : [];

        if (!$product) {
            header('Location: /products');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();

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
            'categories' => $categories,
            'providers' => $providers,
        ]);
    }

    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
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

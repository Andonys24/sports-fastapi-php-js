<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class ProductController extends BaseController
{

    public static function index(Router $router)
    {
        isAdmin();

        $api = new ApiClient();
        // Treer todos los arreglos de la API
        $productsList      = $api->get("/products") ?? [];
        $categoriesList = $api->get("/categories/") ?? [];
        $suppliersList  = $api->get("/suppliers/") ?? [];

        // Asegurar que los arreglos sean planos
        $products       = (is_array($productsList) && array_is_list($productsList)) ? $productsList : [];
        $categoriesList = is_array($categoriesList) ? $categoriesList : [];
        $suppliersList  = is_array($suppliersList) ? $suppliersList : [];

        // Crear diccionario para busqueda
        $categories = array_column($categoriesList, 'name', 'id');
        $suppliers  = array_column($suppliersList, 'name', 'id');

        $router->render('products/index', [
            'title'      => 'Administración de Productos',
            'products'   => $products,
            'categories' => $categories,
            'suppliers'  => $suppliers
        ]);
    }



    public static function create(Router $router)
    {
        isAdmin();

        $alerts = [];
        $product = [];
        $api = new ApiClient();

        $categoriesList = $api->get('/categories');
        $categories = is_array($categoriesList) && array_is_list($categoriesList) ? $categoriesList : [];

        $suppliersList = $api->get('/suppliers');
        $suppliers = is_array($suppliersList) && array_is_list($suppliersList) ? $suppliersList : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload(['stock' => 0]);
            $response = $api->post('/products', $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /products');
                exit;
            }

            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al crear el producto';
            $alerts['error'][] = self::formatErrorMessage($rawError);
            $product = self::populateFormData();
        }

        $router->render('products/create', [
            'title'      => 'Nuevo Producto',
            'alerts'     => $alerts,
            'categories' => $categories,
            'suppliers'  => $suppliers,
            'product'    => $product,
        ]);
    }

    public static function update(Router $router): void
    {
        isAdmin();

        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /products');
            exit;
        }

        $alerts = [];
        $api = new ApiClient();

        $categoriesList = $api->get('/categories');
        $categories = is_array($categoriesList) && array_is_list($categoriesList) ? $categoriesList : [];

        $suppliersList = $api->get('/suppliers');
        $suppliers = is_array($suppliersList) && array_is_list($suppliersList) ? $suppliersList : [];

        $product = $api->get("/products/{$id}");

        if (!$product || isset($product['detail'])) {
            header('Location: /products');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Preservar el stock actual
            $currentStock = (int) ($product['stock'] ?? 0);
            $payload = self::getPayload(['stock' => $currentStock]);

            $response = $api->put("/products/{$id}", $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /products');
                exit;
            }

            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al actualizar el producto';
            $alerts['error'][] = self::formatErrorMessage($rawError);
            $product = self::populateFormData($product);
        }

        $router->render('products/update', [
            'title'      => 'Actualizar Producto',
            'alerts'     => $alerts,
            'categories' => $categories,
            'suppliers'  => $suppliers,
            'product'    => $product,
        ]);
    }

    public static function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        isAdmin();

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            self::jsonResponse(false, 'ID de producto no válido', 400);
        }

        $api = new ApiClient();
        $response = $api->delete("/products/{$id}");

        if ($response && !isset($response['detail']) && !isset($response['error'])) {
            self::jsonResponse(true, 'Producto Eliminado Exitosamente');
        }

        $rawError = $response['detail'] ?? $response['error'] ?? 'Error al eliminar el producto';
        self::jsonResponse(false, self::formatErrorMessage($rawError), 400);
    }

    protected static function getPayload(array $extraData = []): array
    {
        return [
            'category_id' => (int) ($_POST['categoria_id'] ?? 0),
            'supplier_id' => (int) ($_POST['supplier_id'] ?? $_POST['provider_id'] ?? 0),
            'name'        => trim((string) ($_POST['nombre'] ?? '')),
            'price'       => (float) ($_POST['precio'] ?? 0.0),
            'brand'       => trim((string) ($_POST['brand'] ?? '')),
            'stock'       => (int) ($extraData['stock'] ?? 0),
            'img_url'     => trim((string) ($_POST['img_url'] ?? '')),
        ];
    }

    protected static function populateFormData(array $default = []): array
    {
        return array_merge($default, [
            'category_id' => $_POST['categoria_id'] ?? $default['category_id'] ?? '',
            'supplier_id' => $_POST['supplier_id'] ?? $_POST['provider_id'] ?? $default['supplier_id'] ?? '',
            'name'        => $_POST['nombre'] ?? $default['name'] ?? '',
            'price'       => $_POST['precio'] ?? $default['price'] ?? '',
            'brand'       => $_POST['brand'] ?? $default['brand'] ?? '',
            'img_url'     => $_POST['img_url'] ?? $default['img_url'] ?? '',
        ]);
    }
}

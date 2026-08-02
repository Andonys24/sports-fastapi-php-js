<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class CategoryController
{
    public static function index(Router $router)
    {
        isAdmin();

        $api = new ApiClient();
        $categories = $api->get('/categories') ?? [];

        $router->render('categories/index', [
            'title'     => 'Administrar Categorías',
            'categories' => $categories
        ]);
    }

    private static function getPayload(): array
    {
        return [
            'name' => $_POST['nombre'] ?? '',
            'description' => $_POST['descripcion'] ?? ''
        ];
    }

    public static function create(Router $router)
    {
        isAdmin();

        $alerts = [];
        $category = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();
            $api = new ApiClient();
            $response = $api->post('/categories', $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /categories');
                exit;
            }

            $alerts['error'][] = $response['detail'] ?? $response['error'] ?? 'Error al crear la categoría';
        }

        $router->render('categories/create', [
            'title'   => 'Agregar Categoría',
            'alerts'   => $alerts,
            'category' => $category
        ]);
    }

    public static function update(Router $router)
    {
        isAdmin();

        $alerts = [];
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /categories');
            exit;
        }

        $api = new ApiClient();
        $category = $api->get("/categories/{$id}");

        if (!$category) {
            header('Location: /categories');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();

            $response = $api->put("/categories/{$id}", $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /categories');
                exit;
            }

            $alerts['error'][] = $response['detail'] ?? $response['error'] ?? 'Error al actualizar la categoría';
        }

        $router->render('categories/update', [
            'title'   => 'Actualizar Categoría',
            'alerts'   => $alerts,
            'category' => $category
        ]);
    }

    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            isAdmin();

            $id = $_POST['id'] ?? null;

            header('Content-Type: application/json');

            if (!$id) {
                echo json_encode([
                    'resultado' => false,
                    'mensaje'   => 'ID de categoría no válido'
                ]);
                exit;
            }

            $api = new ApiClient();
            $response = $api->delete("/categories/{$id}");

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                echo json_encode([
                    'resultado' => true,
                    'mensaje'   => 'Categoría Eliminada Exitosamente'
                ]);
            } else {
                $rawError = $response['detail'] ?? $response['error'] ?? 'Error al eliminar la categoría';
                $errorMessage = is_array($rawError) ? ($rawError[0]['msg'] ?? json_encode($rawError)) : $rawError;

                echo json_encode([
                    'resultado' => false,
                    'mensaje'   => is_string($errorMessage) ? $errorMessage : 'Error desconocido en la API'
                ]);
            }
            exit;
        }
    }
}

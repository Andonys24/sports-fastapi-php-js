<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class CategoryController
{
    public static function index(Router $router)
    {
        session_start();
        isAdmin();

        $api = new ApiClient();
        $categories = $api->get('/categories') ?? [];

        $router->render('categories/index', [
            'title'     => 'Administrar Categorías',
            'categories' => $categories
        ]);
    }

    public static function create(Router $router)
    {
        session_start();
        isAdmin();

        $alerts = [];
        $category = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = [
                'name' => $_POST['nombre'] ?? '',
                'description' => $_POST['descripcion'] ?? ''
            ];
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
        session_start();
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
            $payload = [
                'name' => $_POST['nombre'] ?? '',
                'description' => $_POST['descripcion'] ?? ''
            ];

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
            session_start();
            isAdmin();

            $id = $_POST['id'] ?? null;
            $api = new ApiClient();
            $response = $api->delete("/categories/{$id}");

            header('Content-Type: application/json');
            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                echo json_encode([
                    'resultado' => true,
                    'mensaje'   => 'Categoría Eliminada Exitosamente'
                ]);
            } else {
                echo json_encode([
                    'resultado' => false,
                    'mensaje'   => $response['detail'] ?? $response['error'] ?? 'Error al eliminar la categoría'
                ]);
            }
            exit;
        }
    }
}

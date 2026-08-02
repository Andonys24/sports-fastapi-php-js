<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class CategoryController extends BaseController
{
    /**
     * Listado de categorías
     */
    public static function index(Router $router): void
    {
        isAdmin();

        $api = new ApiClient();
        $categoriesList = $api->get('/categories') ?? [];

        $categories = is_array($categoriesList) && array_is_list($categoriesList) ? $categoriesList : [];

        $router->render('categories/index', [
            'title'      => 'Administrar Categorías',
            'categories' => $categories
        ]);
    }

    /**
     * Crear una nueva categoría
     */
    public static function create(Router $router): void
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

            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al crear la categoría';
            $alerts['error'][] = self::formatErrorMessage($rawError);

            // Repoblar datos en caso de error
            $category = self::populateFormData();
        }

        $router->render('categories/create', [
            'title'    => 'Agregar Categoría',
            'alerts'   => $alerts,
            'category' => $category
        ]);
    }

    /**
     * Actualizar una categoría existente
     */
    public static function update(Router $router): void
    {
        isAdmin();

        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /categories');
            exit;
        }

        $alerts = [];
        $api = new ApiClient();
        $category = $api->get("/categories/{$id}");

        if (!$category || isset($category['detail'])) {
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

            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al actualizar la categoría';
            $alerts['error'][] = self::formatErrorMessage($rawError);

            // Preservar cambios intentados sobre los datos actuales
            $category = self::populateFormData($category);
        }

        $router->render('categories/update', [
            'title'    => 'Actualizar Categoría',
            'alerts'   => $alerts,
            'category' => $category
        ]);
    }

    /**
     * Eliminar una categoría vía FETCH
     */
    public static function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        isAdmin();

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            self::jsonResponse(false, 'ID de categoría no válido', 400);
        }

        $api = new ApiClient();
        $response = $api->delete("/categories/{$id}");

        if ($response && !isset($response['detail']) && !isset($response['error'])) {
            self::jsonResponse(true, 'Categoría Eliminada Exitosamente');
        }

        $rawError = $response['detail'] ?? $response['error'] ?? 'Error al eliminar la categoría';
        self::jsonResponse(false, self::formatErrorMessage($rawError), 400);
    }

    protected static function getPayload(array $extraData = []): array
    {
        return [
            'name'        => trim((string) ($_POST['nombre'] ?? '')),
            'description' => trim((string) ($_POST['descripcion'] ?? '')),
        ];
    }

    protected static function populateFormData(array $default = []): array
    {
        return array_merge($default, [
            'name'        => $_POST['nombre'] ?? $default['name'] ?? '',
            'description' => $_POST['descripcion'] ?? $default['description'] ?? '',
        ]);
    }
}

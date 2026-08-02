<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class InventoryController
{
    public static function index(Router $router)
    {
        isAdmin();

        $api = new ApiClient();
        $response = $api->get('/inventory');

        // Verificamos si la respuesta es un arreglo numérico/lista real
        $inventory = [];
        if (is_array($response) && array_is_list($response)) {
            $inventory = $response;
        }

        $router->render('inventory/index', [
            'title'     => 'Inventario de Productos',
            'inventory' => $inventory
        ]);
    }

    private static function getPayload(): array
    {
        return [
            'stock'  => (int) ($_POST['stock'] ?? 0),
        ];
    }

    public static function update(Router $router)
    {
        isAdmin();

        $alerts = [];
        $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            header("Location: /inventory");
            exit;
        }

        $api = new ApiClient();
        // Intentar obtener el registro del producto/inventario
        $item = $api->get("/inventory/{$id}") ?? $api->get("/products/{$id}");

        if (!$item) {
            header('Location: /inventory');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();
            $response = $api->put("/inventory/{$id}", $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /inventory');
                exit;
            }

            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al actualizar el inventario';
            $alerts['error'][] = is_string($rawError) ? $rawError : 'Error en la actualización';
        }

        $router->render('inventory/update', [
            'title'     => 'Ajustar Inventario',
            'alerts'    => $alerts,
            'inventory' => $item
        ]);
    }
}

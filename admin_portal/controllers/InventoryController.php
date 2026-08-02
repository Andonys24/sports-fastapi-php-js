<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class InventoryController
{
    /**
     * Muestra el reporte/dashboard de existencias en inventario (Solo Lectura)
     */
    public static function index(Router $router): void
    {
        isAdmin();

        $api = new ApiClient();
        $response = $api->get('/inventory');

        $inventory = (is_array($response) && array_is_list($response)) ? $response : [];

        // Filtro opcional por búsqueda rápida si el usuario escribe un nombre
        $search = trim((string) ($_GET['search'] ?? ''));

        if (!empty($search)) {
            $inventory = array_values(array_filter($inventory, function ($item) use ($search) {
                $name = $item['product_name'] ?? $item['name'] ?? '';
                return stripos($name, $search) !== false;
            }));
        }

        $router->render('inventory/index', [
            'title'     => 'Control de Inventario',
            'inventory' => $inventory,
            'search'    => $search
        ]);
    }
}

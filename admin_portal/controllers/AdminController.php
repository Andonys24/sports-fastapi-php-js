<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class AdminController
{

    public static function index(Router $router)
    {
        isAdmin();

        $api = new ApiClient();

        // 1. Obtener respuestas de la API de forma segura
        $productsRaw   = $api->get('/products');
        $categoriesRaw = $api->get('/categories');
        $suppliersRaw  = $api->get('/suppliers');
        $purchasesRaw  = $api->get('/purchases');
        $inventoryRaw  = $api->get('/inventory');

        // 2. Validar que sean arreglos planos/listas reales
        $products   = (is_array($productsRaw) && array_is_list($productsRaw)) ? $productsRaw : [];
        $categories = (is_array($categoriesRaw) && array_is_list($categoriesRaw)) ? $categoriesRaw : [];
        $suppliers  = (is_array($suppliersRaw) && array_is_list($suppliersRaw)) ? $suppliersRaw : [];
        $purchases  = (is_array($purchasesRaw) && array_is_list($purchasesRaw)) ? $purchasesRaw : [];
        $inventory  = (is_array($inventoryRaw) && array_is_list($inventoryRaw)) ? $inventoryRaw : [];

        // 3. Contar productos con stock crítico (<= 5 unidades)
        $lowStockCount = 0;
        foreach ($inventory as $item) {
            if (is_array($item) && isset($item['stock']) && (int)$item['stock'] <= 5) {
                $lowStockCount++;
            }
        }

        // 4. Renderizar vista pasando datos numéricos validados
        $router->render('admin/index', [
            'title'           => 'Panel de Administración',
            'totalProducts'   => count($products),
            'totalCategories' => count($categories),
            'totalSuppliers'  => count($suppliers),
            'totalPurchases'  => count($purchases),
            'lowStockCount'   => $lowStockCount
        ]);
    }
}

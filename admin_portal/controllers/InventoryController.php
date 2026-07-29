<?php

namespace Controllers;

use MVC\Router;

class InventoryController
{
    public static function index(Router $router)
    {
        session_start();
        isAdmin();

        $router->render('inventory/index', [
            'title' => 'Inventario'
        ]);
    }

    public static function update(Router $router)
    {
        session_start();
        isAdmin();

        $alerts = [];
        $inventory = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alerts['error'][] = 'Módulo preparado. Falta integrar el endpoint de inventario con FastAPI.';
        }

        $router->render('inventory/update', [
            'title' => 'Actualizar Inventario',
            'alerts' => $alerts,
            'inventory' => $inventory
        ]);
    }

    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            isAdmin();

            header('Content-Type: application/json');
            echo json_encode([
                'resultado' => false,
                'mensaje' => 'Módulo de inventario pendiente de integración con FastAPI'
            ]);
            exit;
        }
    }
}
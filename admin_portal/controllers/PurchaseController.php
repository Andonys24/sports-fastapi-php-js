<?php

namespace Controllers;

use MVC\Router;

class PurchaseController
{
    public static function index(Router $router)
    {
        session_start();
        isAdmin();

        $router->render('purchases/index', [
            'title' => 'Compras para Inventario'
        ]);
    }

    public static function create(Router $router)
    {
        session_start();
        isAdmin();

        $alerts = [];
        $purchase = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alerts['error'][] = 'Módulo preparado. Falta integrar el endpoint de compras con FastAPI.';
        }

        $router->render('purchases/create', [
            'title' => 'Registrar Compra',
            'alerts' => $alerts,
            'purchase' => $purchase
        ]);
    }

    public static function update(Router $router)
    {
        session_start();
        isAdmin();

        $alerts = [];
        $purchase = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alerts['error'][] = 'Módulo preparado. Falta integrar el endpoint de compras con FastAPI.';
        }

        $router->render('purchases/update', [
            'title' => 'Actualizar Compra',
            'alerts' => $alerts,
            'purchase' => $purchase
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
                'mensaje' => 'Módulo de compras pendiente de integración con FastAPI'
            ]);
            exit;
        }
    }
}
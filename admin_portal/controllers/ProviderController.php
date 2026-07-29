<?php

namespace Controllers;

use MVC\Router;

class ProviderController
{
    public static function index(Router $router)
    {
        session_start();
        isAdmin();

        $router->render('providers/index', [
            'title' => 'Proveedores'
        ]);
    }

    public static function create(Router $router)
    {
        session_start();
        isAdmin();

        $alerts = [];
        $provider = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alerts['error'][] = 'Módulo preparado. Falta integrar el endpoint de proveedores con FastAPI.';
        }

        $router->render('providers/create', [
            'title' => 'Nuevo Proveedor',
            'alerts' => $alerts,
            'provider' => $provider
        ]);
    }

    public static function update(Router $router)
    {
        session_start();
        isAdmin();

        $alerts = [];
        $provider = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alerts['error'][] = 'Módulo preparado. Falta integrar el endpoint de proveedores con FastAPI.';
        }

        $router->render('providers/update', [
            'title' => 'Actualizar Proveedor',
            'alerts' => $alerts,
            'provider' => $provider
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
                'mensaje' => 'Módulo de proveedores pendiente de integración con FastAPI'
            ]);
            exit;
        }
    }
}

<?php

namespace Controllers;

use MVC\Router;

class UserController
{
    public static function index(Router $router)
    {
        session_start();
        isAdmin();

        $router->render('users/index', [
            'title' => 'Usuarios'
        ]);
    }

    public static function create(Router $router)
    {
        session_start();
        isAdmin();

        $alerts = [];
        $user = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alerts['error'][] = 'Módulo preparado. Falta integrar el endpoint de usuarios con FastAPI.';
        }

        $router->render('users/create', [
            'title' => 'Nuevo Usuario',
            'alerts' => $alerts,
            'user' => $user
        ]);
    }

    public static function update(Router $router)
    {
        session_start();
        isAdmin();

        $alerts = [];
        $user = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alerts['error'][] = 'Módulo preparado. Falta integrar el endpoint de usuarios con FastAPI.';
        }

        $router->render('users/update', [
            'title' => 'Actualizar Usuario',
            'alerts' => $alerts,
            'user' => $user
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
                'mensaje' => 'Módulo de usuarios pendiente de integración con FastAPI'
            ]);
            exit;
        }
    }
}

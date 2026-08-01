<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class UserController
{
    public static function index(Router $router)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        isAdmin();

        $api = new ApiClient();
        $users = $api->get("/users") ?? [];
        $users = array_filter($users, function ($user) {
            return ($user['admin'] ?? 0) != 1;
        });

        $router->render('users/index', [
            'title' => 'Usuarios',
            'users' => $users,
        ]);
    }

    private static function getPayload(): array
    {
        return [
            'username'  => $_POST['username'] ?? '',
            'full_name' => $_POST['nombre'] ?? '',
            'email'     => $_POST['email'] ?? '',
            'password'  => $_POST['password'] ?? '',
            'admin'     => 0 // <--- Forzado por convención a 0 (usuario normal/cliente)
        ];
    }

    public static function create(Router $router)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        isAdmin();

        $alerts = [];
        $user = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();
            $api = new ApiClient();
            $response = $api->post("/users", $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /users');
                exit;
            }

            // Capturar error de forma segura (igual que en productos)
            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al crear el usuario';

            if (is_array($rawError)) {
                $errorMessage = $rawError[0]['msg'] ?? json_encode($rawError);
            } else {
                $errorMessage = $rawError;
            }

            $alerts['error'][] = is_string($errorMessage) ? $errorMessage : 'Error desconocido en la API';
        }

        $router->render('users/create', [
            'title' => 'Nuevo Usuario',
            'alerts' => $alerts,
            'user' => $user
        ]);
    }

    public static function update(Router $router)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        isAdmin();

        $alerts = [];
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /users');
            exit;
        }

        $api = new ApiClient();
        $user = $api->get("/users/{$id}");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();

            $response = $api->put("/users/{$id}", $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /users');
                exit;
            }

            // Capturar error de forma segura
            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al actualizar el usuario';

            if (is_array($rawError)) {
                $errorMessage = $rawError[0]['msg'] ?? json_encode($rawError);
            } else {
                $errorMessage = $rawError;
            }

            $alerts['error'][] = is_string($errorMessage) ? $errorMessage : 'Error desconocido en la API';
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
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            isAdmin();

            $id = $_POST['id'] ?? null;

            header('Content-Type: application/json');

            if (!$id) {
                echo json_encode([
                    'resultado' => false,
                    'mensaje'   => 'ID de usuario no válido'
                ]);
                exit;
            }

            $api = new ApiClient();
            $response = $api->delete("/users/{$id}");

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                echo json_encode([
                    'resultado' => true,
                    'mensaje'   => 'Usuario Eliminado Exitosamente'
                ]);
            } else {
                $rawError = $response['detail'] ?? $response['error'] ?? 'Error al eliminar el usuario';
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

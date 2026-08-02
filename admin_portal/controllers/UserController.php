<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class UserController extends BaseController
{
    /**
     * Listado de usuarios (clientes / no administradores)
     */
    public static function index(Router $router): void
    {
        isAdmin();

        $api = new ApiClient();
        $usersResponse = $api->get('/users') ?? [];

        // Filtrar usuarios no administradores y reindexar el arreglo
        $users = array_values(array_filter($usersResponse, function ($user) {
            return ($user['admin'] ?? 0) != 1;
        }));

        $router->render('users/index', [
            'title' => 'Usuarios',
            'users' => $users,
        ]);
    }

    /**
     * Crear un nuevo usuario
     */
    public static function create(Router $router): void
    {
        isAdmin();

        $alerts = [];
        $user = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();
            $api = new ApiClient();
            $response = $api->post('/users', $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /users');
                exit;
            }

            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al crear el usuario';
            $alerts['error'][] = self::formatErrorMessage($rawError);

            // Repoblar formulario en caso de error
            $user = self::populateFormData();
        }

        $router->render('users/create', [
            'title'  => 'Nuevo Usuario',
            'alerts' => $alerts,
            'user'   => $user
        ]);
    }

    /**
     * Actualizar un usuario existente
     */
    public static function update(Router $router): void
    {
        isAdmin();

        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /users');
            exit;
        }

        $alerts = [];
        $api = new ApiClient();
        $user = $api->get("/users/{$id}");

        if (!$user || isset($user['detail']) || isset($user['error'])) {
            header('Location: /users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();
            $response = $api->put("/users/{$id}", $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /users');
                exit;
            }

            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al actualizar el usuario';
            $alerts['error'][] = self::formatErrorMessage($rawError);

            // Preservar datos intentados tras un error
            $user = self::populateFormData($user);
        }

        $router->render('users/update', [
            'title'  => 'Actualizar Usuario',
            'alerts' => $alerts,
            'user'   => $user
        ]);
    }

    /**
     * Eliminar un usuario vía FETCH
     */
    public static function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        isAdmin();

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            self::jsonResponse(false, 'ID de usuario no válido', 400);
        }

        $api = new ApiClient();
        $response = $api->delete("/users/{$id}");

        if ($response && !isset($response['detail']) && !isset($response['error'])) {
            self::jsonResponse(true, 'Usuario Eliminado Exitosamente');
        }

        $rawError = $response['detail'] ?? $response['error'] ?? 'Error al eliminar el usuario';
        self::jsonResponse(false, self::formatErrorMessage($rawError), 400);
    }

    protected static function getPayload(array $extraData = []): array
    {
        return [
            'username'  => trim((string) ($_POST['username'] ?? '')),
            'full_name' => trim((string) ($_POST['nombre'] ?? '')),
            'email'     => trim((string) ($_POST['email'] ?? '')),
            'password'  => $_POST['password'] ?? '',
            'admin'     => 0 // Convención: usuario estándar
        ];
    }

    protected static function populateFormData(array $default = []): array
    {
        return array_merge($default, [
            'username'  => $_POST['username'] ?? $default['username'] ?? '',
            'full_name' => $_POST['nombre'] ?? $default['full_name'] ?? $default['nombre'] ?? '',
            'email'     => $_POST['email'] ?? $default['email'] ?? '',
            'password'  => $_POST['password'] ?? '',
        ]);
    }
}

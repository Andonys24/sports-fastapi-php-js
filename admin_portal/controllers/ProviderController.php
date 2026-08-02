<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class ProviderController
{
    public static function index(Router $router)
    {
        isAdmin();

        $api = new ApiClient();
        $providersResponse = $api->get('/suppliers');
        $providers = is_array($providersResponse) && array_is_list($providersResponse) ? $providersResponse : [];

        $router->render('providers/index', [
            'title' => 'Proveedores',
            'providers' => $providers
        ]);
    }

    private static function getPayload(): array
    {
        return [
            'name' => $_POST['nombre'] ?? '',
            'address' => $_POST['direccion'] ?? '',
            'phone_number' => $_POST['telefono'] ?? '',
            'contract_period' => (int)($_POST['periodo_contrato'] ?? 0),
            'contract_type' => $_POST['tipo_contrato'] ?? ''
        ];
    }

    public static function create(Router $router)
    {
        isAdmin();

        $alerts = [];
        $provider = [];

        $api = new ApiClient();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();

            $response = $api->post('/suppliers', $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /suppliers');
                exit;
            }

            $alerts['error'][] = $response['detail'] ?? $response['error'] ?? 'Error al crear el proveedor';
        }

        $router->render('providers/create', [
            'title' => 'Nuevo Proveedor',
            'alerts' => $alerts,
            'provider' => $provider
        ]);
    }

    public static function update(Router $router)
    {
        isAdmin();

        $alerts = [];
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /suppliers');
            exit;
        }

        $api = new ApiClient();
        $provider = $api->get("/suppliers/{$id}");

        if (!$provider) {
            header('Location: /suppliers');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();

            $response = $api->put("/suppliers/{$id}", $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /suppliers');
                exit;
            }

            $alerts['error'][] = $response['detail'] ?? $response['error'] ?? 'Error al actualizar el proveedor';
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
            isAdmin();

            $id = $_POST['id'] ?? null;
            $api = new ApiClient();
            $response = $api->delete("/suppliers/{$id}");

            header('Content-Type: application/json');
            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                echo json_encode([
                    'resultado' => true,
                    'mensaje' => 'Proveedor Eliminado Exitosamente'
                ]);
            } else {
                echo json_encode([
                    'resultado' => false,
                    'mensaje' => $response['detail'] ?? $response['error'] ?? 'Error al eliminar el proveedor'
                ]);
            }
            exit;
        }
    }
}

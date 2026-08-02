<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class PurchaseController
{
    public static function index(Router $router)
    {
        isAdmin();

        // 1. Validar y formatear la fecha recibida por GET (por defecto hoy)
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-', $fecha);

        // Si la fecha enviada no es válida (ej: 2026-02-31), redirigimos a la fecha actual
        if (count($fechas) !== 3 || !checkdate((int)$fechas[1], (int)$fechas[2], (int)$fechas[0])) {
            $fecha = date('Y-m-d');
        }

        // 2. Consumir la API pasando la fecha filtrada
        $api = new ApiClient();
        $response = $api->get("/purchases?fecha={$fecha}");

        // Aseguramos que sea una lista/arreglo válido
        $purchases = (is_array($response) && array_is_list($response)) ? $response : [];

        // 3. Renderizar la vista
        $router->render('purchases/index', [
            'title'     => 'Administración de Compras',
            'fecha'     => $fecha,
            'purchases' => $purchases
        ]);
    }

    private static function getPayload(): array
    {
        return [
            'product_id'     => (int) ($_POST['product_id'] ?? 0),
            'provider_id'    => (int) ($_POST['provider_id'] ?? 0),
            'quantity'       => (int) ($_POST['quantity'] ?? 0),
            'purchase_price' => (float) ($_POST['purchase_price'] ?? 0),
            'date'           => $_POST['date'] ?? date('Y-m-d')
        ];
    }

    public static function create(Router $router)
    {
        isAdmin();

        $alerts = [];
        $purchase = [];
        $api = new ApiClient();
        $products = $api->get("/products") ?? [];
        $providersResponse = $api->get('/suppliers');
        $providers = is_array($providersResponse) && array_is_list($providersResponse) ? $providersResponse : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();

            $response = $api->post('/purchases', $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /purchases');
                exit;
            }

            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al registrar la compra';

            if (is_array($rawError)) {
                $errorMessage = $rawError[0]['msg'] ?? json_encode($rawError);
            } else {
                $errorMessage = $rawError;
            }

            $alerts['error'][] = is_string($errorMessage) ? $errorMessage : 'Error desconocido en la API';
            $purchase = $payload;
        }

        $router->render('purchases/create', [
            'title'     => 'Nueva Compra',
            'alerts'    => $alerts,
            'products'  => $products,
            'providers' => $providers,
            'purchase'  => $purchase,
        ]);
    }

    public static function update(Router $router)
    {
        isAdmin();

        $alerts = [];
        $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            header("Location: /purchases");
            exit;
        }

        $api = new ApiClient();
        $purchase = $api->get("/purchases/{$id}");
        $products = $api->get("/products") ?? [];
        $providersResponse = $api->get('/suppliers');
        $providers = is_array($providersResponse) && array_is_list($providersResponse) ? $providersResponse : [];

        if (!$purchase || isset($purchase['detail'])) {
            header('Location: /purchases');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();

            $response = $api->put("/purchases/{$id}", $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /purchases');
                exit;
            }

            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al actualizar la compra';

            if (is_array($rawError)) {
                $errorMessage = $rawError[0]['msg'] ?? json_encode($rawError);
            } else {
                $errorMessage = $rawError;
            }

            $alerts['error'][] = is_string($errorMessage) ? $errorMessage : 'Error al actualizar la compra';
        }

        $router->render('purchases/update', [
            'title'     => 'Actualizar Compra',
            'alerts'    => $alerts,
            'purchase'  => $purchase,
            'products'  => $products,
            'providers' => $providers,
        ]);
    }

    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            isAdmin();

            $api = new ApiClient();
            $id = $_POST['id'] ?? null;
            $response = $api->delete("/purchases/{$id}");

            header('Content-Type: application/json');
            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                echo json_encode([
                    'resultado' => true,
                    'mensaje'   => 'Registro de Compra Eliminado Exitosamente'
                ]);
            } else {
                echo json_encode([
                    'resultado' => false,
                    'mensaje'   => $response['detail'] ?? $response['error'] ?? 'Error al eliminar la compra'
                ]);
            }
            exit;
        }
    }
}

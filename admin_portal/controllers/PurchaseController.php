<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class PurchaseController extends BaseController
{
    /**
     * Muestra el historial de compras filtrado por fecha (hoy por defecto)
     */
    public static function index(Router $router): void
    {
        isAdmin();

        $api = new ApiClient();
        $response = $api->get('/purchases');

        $purchases = (is_array($response) && array_is_list($response)) ? $response : [];

        // Si no viene parámetro fecha en la URL, se usa la fecha actual por defecto
        $fecha = trim((string) ($_GET['fecha'] ?? date('Y-m-d')));

        $purchases = filterByDate($purchases, "date_purchase", $fecha);

        $router->render('purchases/index', [
            'title'     => 'Historial de Compras',
            'purchases' => $purchases,
            'fecha'     => $fecha
        ]);
    }

    /**
     * Registrar una nueva compra
     */
    public static function create(Router $router): void
    {
        isAdmin();

        $alerts = [];
        $api = new ApiClient();

        $products = $api->get('/products') ?? [];
        $suppliersResponse = $api->get('/suppliers');
        $suppliers = (is_array($suppliersResponse) && array_is_list($suppliersResponse)) ? $suppliersResponse : [];

        // Estructura por defecto para evitar warnings de claves no definidas
        $purchase = [
            'product_id'     => '',
            'supplier_id'    => '',
            'quantity'       => '',
            'purchase_price' => '',
            'date_purchase'  => date('Y-m-d')
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();

            $response = $api->post('/purchases', $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /purchases');
                exit;
            }

            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al registrar la compra';
            $alerts['error'][] = self::formatErrorMessage($rawError);

            $purchase = self::populateFormData();
        }

        $router->render('purchases/create', [
            'title'     => 'Nueva Compra',
            'alerts'    => $alerts,
            'products'  => $products,
            'suppliers' => $suppliers,
            'purchase'  => $purchase,
        ]);
    }

    protected static function getPayload(array $extraData = []): array
    {
        $userId = $_SESSION['id'] ?? $_SESSION['user_id'] ?? 1;

        return [
            'product_id'     => (int) ($_POST['product_id'] ?? 0),
            'supplier_id'    => (int) ($_POST['supplier_id'] ?? 0),
            'user_id'        => (int) $userId,
            'quantity'       => (int) ($_POST['quantity'] ?? 0),
            'purchase_price' => (float) ($_POST['purchase_price'] ?? 0.0),
            'date_purchase'  => trim((string) ($_POST['date_purchase'] ?? date('Y-m-d'))),
        ];
    }

    protected static function populateFormData(array $default = []): array
    {
        return array_merge([
            'product_id'     => '',
            'supplier_id'    => '',
            'quantity'       => '',
            'purchase_price' => '',
            'date_purchase'  => date('Y-m-d'),
        ], $default, [
            'product_id'     => $_POST['product_id'] ?? $default['product_id'] ?? '',
            'supplier_id'    => $_POST['supplier_id'] ?? $default['supplier_id'] ?? '',
            'quantity'       => $_POST['quantity'] ?? $default['quantity'] ?? '',
            'purchase_price' => $_POST['purchase_price'] ?? $default['purchase_price'] ?? '',
            'date_purchase'  => $_POST['date_purchase'] ?? $default['date_purchase'] ?? date('Y-m-d'),
        ]);
    }
}

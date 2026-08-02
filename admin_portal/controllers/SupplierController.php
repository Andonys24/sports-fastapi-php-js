<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class SupplierController extends BaseController
{
    /**
     * Listado de proveedores
     */
    public static function index(Router $router): void
    {
        isAdmin();

        $api = new ApiClient();
        $suppliersResponse = $api->get('/suppliers');
        $suppliers = (is_array($suppliersResponse) && array_is_list($suppliersResponse)) ? $suppliersResponse : [];

        $router->render('suppliers/index', [
            'title'     => 'Proveedores',
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Crear un nuevo proveedor
     */
    public static function create(Router $router): void
    {
        isAdmin();

        $alerts = [];
        $supplier = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = self::getPayload();
            $api = new ApiClient();
            $response = $api->post('/suppliers', $payload);

            if ($response && !isset($response['detail']) && !isset($response['error'])) {
                header('Location: /suppliers');
                exit;
            }

            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al crear el proveedor';
            $alerts['error'][] = self::formatErrorMessage($rawError);

            // Repoblar formulario en caso de error
            $supplier = self::populateFormData();
        }

        $router->render('suppliers/create', [
            'title'    => 'Nuevo Proveedor',
            'alerts'   => $alerts,
            'supplier' => $supplier
        ]);
    }

    /**
     * Actualizar un proveedor existente
     */
    public static function update(Router $router): void
    {
        isAdmin();

        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /suppliers');
            exit;
        }

        $alerts = [];
        $api = new ApiClient();
        $supplier = $api->get("/suppliers/{$id}");

        if (!$supplier || isset($supplier['detail']) || isset($supplier['error'])) {
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

            $rawError = $response['detail'] ?? $response['error'] ?? 'Error al actualizar el proveedor';
            $alerts['error'][] = self::formatErrorMessage($rawError);

            // Preservar cambios en el formulario tras un error
            $supplier = self::populateFormData($supplier);
        }

        $router->render('suppliers/update', [
            'title'    => 'Actualizar Proveedor',
            'alerts'   => $alerts,
            'supplier' => $supplier
        ]);
    }

    /**
     * Eliminar un proveedor vía AJAX
     */
    public static function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        isAdmin();

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            self::jsonResponse(false, 'ID de proveedor no válido', 400);
        }

        $api = new ApiClient();
        $response = $api->delete("/suppliers/{$id}");

        if ($response && !isset($response['detail']) && !isset($response['error'])) {
            self::jsonResponse(true, 'Proveedor Eliminado Exitosamente');
        }

        $rawError = $response['detail'] ?? $response['error'] ?? 'Error al eliminar el proveedor';
        self::jsonResponse(false, self::formatErrorMessage($rawError), 400);
    }


    protected static function getPayload(array $extraData = []): array
    {
        return [
            'name'            => trim((string) ($_POST['nombre'] ?? '')),
            'address'         => trim((string) ($_POST['direccion'] ?? '')),
            'phone_number'    => trim((string) ($_POST['telefono'] ?? '')),
            'contract_period' => (int) ($_POST['periodo_contrato'] ?? 0),
            'contract_type'   => trim((string) ($_POST['tipo_contrato'] ?? '')),
        ];
    }

    protected static function populateFormData(array $default = []): array
    {
        return array_merge($default, [
            'name'            => $_POST['nombre'] ?? $default['name'] ?? '',
            'address'         => $_POST['direccion'] ?? $default['address'] ?? '',
            'phone_number'    => $_POST['telefono'] ?? $default['phone_number'] ?? '',
            'contract_period' => $_POST['periodo_contrato'] ?? $default['contract_period'] ?? 0,
            'contract_type'   => $_POST['tipo_contrato'] ?? $default['contract_type'] ?? '',
        ]);
    }
}

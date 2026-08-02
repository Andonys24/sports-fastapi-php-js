<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class OrderController
{
    /**
     * Muestra el panel de encargos filtrados por fecha (Solo Lectura)
     */
    public static function index(Router $router): void
    {
        isAdmin();

        $api = new ApiClient();
        $response = $api->get("/orders/");

        $orders = (is_array($response) && array_is_list($response)) ? $response : [];

        // Si no se especifica fecha, se usa la fecha de hoy por defecto
        $fecha = trim((string) ($_GET['fecha'] ?? date('Y-m-d')));

        $orders = filterByDate($orders, 'date_order', $fecha);

        $router->render('orders/index', [
            'title'    => 'Panel de Encargos',
            'encargos' => $orders,
            'fecha'    => $fecha
        ]);
    }
}

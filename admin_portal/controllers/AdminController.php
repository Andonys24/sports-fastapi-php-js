<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class AdminController
{

    public static function index(Router $router)
    {
        session_start();
        isAdmin();

        $date = $_GET["date"] ?? date("Y-m-d");
        $date_parts = explode("-", $date);

        if (!checkdate((int)($date_parts[1] ?? 0), (int)($date_parts[2] ?? 0), (int)($date_parts[0] ?? 0))) {
            header('Location: /admin');
            exit;
        }

        $api = new ApiClient();

        // Consultar pedidos a la API de FastAPI
        $orders = $api->get("/orders?date={$date}") ?? [];

        $router->render("admin/index", [
            "title" => "Panel de Administracion",
            "name" => $_SESSION["name"] ?? "Administrador",
            "date" => $date,
            "orders" => $orders
        ]);
    }
}

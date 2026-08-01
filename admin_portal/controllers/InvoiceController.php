<?php

namespace Controllers;

use MVC\Router;

class InvoiceController
{
    public static function index(Router $router)
    {
        session_start();
        isAdmin();

        $router->render('invoices/index', [
            'title' => 'Facturas / Ventas'
        ]);
    }

    public static function create(Router $router)
    {
        session_start();
        isAdmin();

        $alerts = [];
        $invoice = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alerts['error'][] = 'Módulo preparado. Falta integrar el endpoint de facturas con FastAPI.';
        }

        $router->render('invoices/create', [
            'title' => 'Crear Factura',
            'alerts' => $alerts,
            'invoice' => $invoice
        ]);
    }
}
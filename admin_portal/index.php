<?php
require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/autoload.php";
require_once __DIR__ . "/helpers/functions.php";

use MVC\Router;
use Controllers\AuthController;
use Controllers\AdminController;
use Controllers\ProductController;
use Controllers\CategoryController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();

// Rutas de Autenticacion
$router->get("/login", [AuthController::class, "login"]);
$router->post("/login", [AuthController::class, "login"]);
$router->post("/logout", [AuthController::class, "logout"]);

// Panel Principal
$router->get('/admin', [AdminController::class, 'index']);

// CRUD para productos
$router->get('/products', [ProductController::class, 'index']);
$router->get('/products/create', [ProductController::class, 'create']);
$router->post('/products/create', [ProductController::class, 'create']);
$router->get('/products/update', [ProductController::class, 'update']);
$router->post('/products/update', [ProductController::class, 'update']);
$router->post('/products/delete', [ProductController::class, 'delete']);

// CRUD de Categorías
$router->get('/categories', [CategoryController::class, 'index']);
$router->get('/categories/create', [CategoryController::class, 'create']);
$router->post('/categories/create', [CategoryController::class, 'create']);
$router->get('/categories/update', [CategoryController::class, 'update']);
$router->post('/categories/update', [CategoryController::class, 'update']);
$router->post('/categories/delete', [CategoryController::class, 'delete']);

// Ejecucion del Router
$router->checkRoutes();

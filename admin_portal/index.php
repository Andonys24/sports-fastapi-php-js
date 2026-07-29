<?php
require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/autoload.php";
require_once __DIR__ . "/helpers/functions.php";
require_once __DIR__ . "/controllers/UserController.php";
require_once __DIR__ . "/controllers/ProviderController.php";
require_once __DIR__ . "/controllers/PurchaseController.php";
require_once __DIR__ . "/controllers/InventoryController.php";

use MVC\Router;
use Controllers\AuthController;
use Controllers\AdminController;
use Controllers\ProductController;
use Controllers\CategoryController;
use Controllers\UserController;
use Controllers\ProviderController;
use Controllers\PurchaseController;
use Controllers\InventoryController;

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

// CRUD de Usuarios
$router->get('/users', [UserController::class, 'index']);
$router->get('/users/create', [UserController::class, 'create']);
$router->post('/users/create', [UserController::class, 'create']);
$router->get('/users/update', [UserController::class, 'update']);
$router->post('/users/update', [UserController::class, 'update']);
$router->post('/users/delete', [UserController::class, 'delete']);

// CRUD de Proveedores
$router->get('/providers', [ProviderController::class, 'index']);
$router->get('/providers/create', [ProviderController::class, 'create']);
$router->post('/providers/create', [ProviderController::class, 'create']);
$router->get('/providers/update', [ProviderController::class, 'update']);
$router->post('/providers/update', [ProviderController::class, 'update']);
$router->post('/providers/delete', [ProviderController::class, 'delete']);

// Compras para inventario
$router->get('/purchases', [PurchaseController::class, 'index']);
$router->get('/purchases/create', [PurchaseController::class, 'create']);
$router->post('/purchases/create', [PurchaseController::class, 'create']);
$router->get('/purchases/update', [PurchaseController::class, 'update']);
$router->post('/purchases/update', [PurchaseController::class, 'update']);
$router->post('/purchases/delete', [PurchaseController::class, 'delete']);

// Inventario
$router->get('/inventory', [InventoryController::class, 'index']);
$router->get('/inventory/update', [InventoryController::class, 'update']);
$router->post('/inventory/update', [InventoryController::class, 'update']);
$router->post('/inventory/delete', [InventoryController::class, 'delete']);

// Ejecucion del Router
$router->checkRoutes();

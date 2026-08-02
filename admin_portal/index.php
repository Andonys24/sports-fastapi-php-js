<?php
require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/autoload.php";
require_once __DIR__ . "/helpers/functions.php";
require_once __DIR__ . "/controllers/UserController.php";
require_once __DIR__ . "/controllers/SupplierController.php";
require_once __DIR__ . "/controllers/PurchaseController.php";
require_once __DIR__ . "/controllers/InventoryController.php";

use MVC\Router;
use Controllers\AuthController;
use Controllers\AdminController;
use Controllers\ProductController;
use Controllers\CategoryController;
use Controllers\UserController;
use Controllers\SupplierController;
use Controllers\PurchaseController;
use Controllers\InventoryController;
use Controllers\OrderController;

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
$router->get('/suppliers', [SupplierController::class, 'index']);
$router->get('/suppliers/create', [SupplierController::class, 'create']);
$router->post('/suppliers/create', [SupplierController::class, 'create']);
$router->get('/suppliers/update', [SupplierController::class, 'update']);
$router->post('/suppliers/update', [SupplierController::class, 'update']);
$router->post('/suppliers/delete', [SupplierController::class, 'delete']);

// Compras para inventario
$router->get('/purchases', [PurchaseController::class, 'index']);
$router->get('/purchases/create', [PurchaseController::class, 'create']);
$router->post('/purchases/create', [PurchaseController::class, 'create']);

// Inventario
$router->get('/inventory', [InventoryController::class, 'index']);

// Rutas de Encargos
$router->get('/orders', [OrderController::class, 'index']);

// Ejecucion del Router
$router->checkRoutes();

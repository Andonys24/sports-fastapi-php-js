<?php
require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/autoload.php";
require_once __DIR__ . "/helpers/functions.php";

use MVC\Router;
use Controllers\AuthController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();

// Rutas de Autenticacion
$router->get("/login", [AuthController::class, "login"]);
$router->post("/login", [AuthController::class, "login"]);
$router->post("/logout", [AuthController::class, "logout"]);

// Rutas de Sistema de Admin

// Ejecucion del Router
$router->checkRoutes();

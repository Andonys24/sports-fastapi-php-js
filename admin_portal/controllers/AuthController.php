<?php

namespace Controllers;

use MVC\Router;
use Services\ApiClient;

class AuthController
{
    public static function login(Router $router): void
    {
        $alerts = [];
        $user = [
            "email" => "",
            "password" => ""
        ];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $user["email"] = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL) ?? "";
            $user["password"] = $_POST["password"] ?? "";

            // Validacion de Formulario
            if (empty($user["email"])) {
                $alerts["error"][] = "El email es obligatorio";
            }
            if (empty($user['password'])) {
                $alerts['error'][] = 'El password es obligatorio';
            }

            // Si la validación del formulario es correcta, autentínese contra FastAPI.
            if (empty($alerts)) {
                $apiClient = new ApiClient();

                $response = $apiClient->post("/login", [
                    "email" => $user["email"],
                    "password" => $user["password"]
                ]);

                // Compruebar si la autenticación se realizó correctamente y si el usuario tiene el rol de 'administrador'.
                if (isset($response['id']) && isset($response['rol']) && $response['rol'] == ROLE_ADMIN) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    $_SESSION["id"] = $response["id"];
                    $_SESSION["name"] = $response["name"];
                    $_SESSION["email"] = $response["email"];
                    $_SESSION["role"] = $response["role"];
                    $_SESSION["login"] = true;
                    header('Location: /dashboard');
                    exit;
                }
            } else {
                $alerts['error'][] = $response['detail'] ?? 'Credenciales incorrectas o acceso no autorizado';
            }
        }

        $router->render("auth/login", [
            "title" => "Iniciar Sesion",
            "alerts" => $alerts,
            "user" => $user
        ]);
    }

    // Destruye la sesión y cierra la sesión del administrador.
    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: /login");
        exit;
    }
}

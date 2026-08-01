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
            'username' => '',
            'password' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user['username'] = trim($_POST['username'] ?? '');
            $user['password'] = $_POST['password'] ?? '';

            if (empty($user['username'])) {
                $alerts['error'][] = 'El usuario es obligatorio';
            }

            if (empty($user['password'])) {
                $alerts['error'][] = 'El password es obligatorio';
            }

            if (empty($alerts)) {
                $apiClient = new ApiClient();
                $response = $apiClient->postForm('/auth/login', [
                    'username' => $user['username'],
                    'password' => $user['password']
                ]);

                if (isset($response['access_token'])) {
                    $profile = $apiClient->get('/auth/me', [
                        'Authorization: Bearer ' . $response['access_token']
                    ]);

                    if (isset($profile['id']) && isset($profile['admin']) && (int)$profile['admin'] === ROLE_ADMIN) {
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }

                        $_SESSION['id'] = $profile['id'];
                        $_SESSION['name'] = $profile['full_name'] ?? $profile['username'] ?? 'Administrador';
                        $_SESSION['username'] = $profile['username'] ?? '';
                        $_SESSION['email'] = $profile['email'] ?? '';
                        $_SESSION['role'] = $profile['admin'];
                        $_SESSION['token'] = $response['access_token'];
                        $_SESSION['login'] = true;

                        header('Location: /admin');
                        exit;
                    }

                    $alerts['error'][] = 'El usuario no tiene permisos de administrador';
                } else {
                    $alerts['error'][] = $response['detail'] ?? 'Credenciales incorrectas o acceso no autorizado';
                }
            }
        }

        $router->render('auth/login', [
            'title' => 'Iniciar Sesion',
            'alerts' => $alerts,
            'user' => $user
        ]);
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: /login');
        exit;
    }
}

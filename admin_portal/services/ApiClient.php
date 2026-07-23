<?php

namespace Services;

class ApiClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = defined("API_BASE_URL") ? API_BASE_URL : "http://localhost:8000";
    }

    // Ejecuta solicitudes GET a FastAPI.
    public function get(string $endpoint): array
    {
        return $this->request('GET', $endpoint);
    }

    // Ejecuta solicitudes GET a FastAPI.
    public function post(string $endpoint, array $data = []): array
    {
        return $this->request("POST", $endpoint, $data);
    }

    // Controlador de ejecución cURL base

    private function request(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl . $endpoint;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        if (!empty($data) && ($method === "POST" || $method === "PUT")) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            return ["detail" => "Error de conexion con el serviodr API"];
        }

        $decode = json_decode($response, true);
        return is_array($decode) ? $decode : ["detail" => "Respuesta no valida de la API"];
    }
}

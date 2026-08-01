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
    public function get(string $endpoint, array $headers = []): array
    {
        return $this->request('GET', $endpoint, [], $headers);
    }

    // Ejecuta solicitudes GET a FastAPI.
    public function post(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request("POST", $endpoint, $data, $headers);
    }

    public function put(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request("PUT", $endpoint, $data, $headers);
    }

    public function delete(string $endpoint, array $headers = []): array
    {
        return $this->request("DELETE", $endpoint, [], $headers);
    }

    public function postForm(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request("POST", $endpoint, $data, $headers, true);
    }

    // Controlador de ejecución cURL base
    private function request(string $method, string $endpoint, array $data = [], array $headers = [], bool $formEncoded = false): array
    {
        $url = rtrim($this->baseUrl, '/') . $endpoint;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $requestHeaders = ['Accept: application/json'];

        if ($formEncoded) {
            $requestHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }
        } elseif (!empty($data) && ($method === "POST" || $method === "PUT")) {
            $requestHeaders[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } else {
            $requestHeaders[] = 'Content-Type: application/json';
        }

        foreach ($headers as $header) {
            $requestHeaders[] = $header;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            return ["detail" => "Error de conexion con el serviodr API"];
        }

        $decode = json_decode($response, true);
        return is_array($decode) ? $decode : ["detail" => "Respuesta no valida de la API"];
    }
}

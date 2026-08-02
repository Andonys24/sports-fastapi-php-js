<?php

namespace Controllers;

abstract class BaseController
{
    /**
     * Normaliza los mensajes de error devueltos por FastAPI (Pydantic / Arrays / Strings)
     */
    protected static function formatErrorMessage(mixed $rawError): string
    {
        return match (true) {
            is_array($rawError) => $rawError[0]['msg'] ?? json_encode($rawError),
            default             => (string) $rawError,
        };
    }

    /**
     * Envía una respuesta JSON uniforme para peticiones Fetch
     */
    protected static function jsonResponse(bool $resultado, string $mensaje, int $code = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode([
            'resultado' => $resultado,
            'mensaje'   => $mensaje
        ]);
        exit;
    }

    /**
     * Contrato: Cada controlador debe definir cómo mapear su Payload hacia FastAPI
     */
    abstract protected static function getPayload(array $extraData = []): array;

    /**
     * Contrato: Cada controlador debe definir cómo repoblar sus campos de formulario
     */
    abstract protected static function populateFormData(array $default = []): array;
}

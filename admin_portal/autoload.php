<?php

// Registrar autoloader
spl_autoload_register(function ($class) {
    // Extraer nombre final de la clase
    $parts = explode("\\", $class);
    $className = end($parts);
    // Remplazar backslashes de namespace por barras de directorio
    $classFormatted = str_replace("\\", "/", $class);

    // Mapear directo de clases principales y controladores
    $routes = [
        __DIR__ . "/{$classFormatted}.php",
        __DIR__ . "/{$className}.php",
        __DIR__ . "/controllers/{$classFormatted}.php",
        __DIR__ . "/services/{$classFormatted}.php",
        __DIR__ . "/helpers/{$classFormatted}.php",
    ];

    foreach ($routes as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

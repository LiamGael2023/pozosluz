<?php
/**
 * Router para PHP Built-in Server
 * Uso: php -S localhost:8000 router.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Si es un archivo estático existente, servirlo directamente
$staticFile = __DIR__ . $uri;
if ($uri !== '/' && file_exists($staticFile) && is_file($staticFile)) {
    // Dejar que PHP sirva el archivo estático
    return false;
}

// Todo lo demás va a index.php
require __DIR__ . '/index.php';

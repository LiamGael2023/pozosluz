<?php
/**
 * Configuración de la Aplicación
 */

return [
    'name' => 'Calculadora de Pozos de Luz',
    'version' => '1.0.0',
    'url' => getenv('APP_URL') ?: 'http://localhost/pozosluz',
    'debug' => getenv('APP_DEBUG') ?: true,
    'timezone' => 'America/Lima'
];

<?php
/**
 * Configuración de la base de datos.
 * Ajusta estos valores a tu instalación de MAMP.
 *
 * En un proyecto real esto vendría de un .env (ver Módulo 04), no en código.
 */

declare(strict_types=1);

return [
    'host'    => '127.0.0.1',
    'port'    => '3306',          // MAMP Windows: 3306 (Mac suele ser 8889)
    'dbname'  => 'curso_tareas',
    'usuario' => 'root',
    'pass'    => 'root',
    'charset' => 'utf8mb4',
];

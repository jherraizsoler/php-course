<?php
/**
 * Configuración de la base de datos.
 * Ajusta estos valores a tu instalación de MAMP.
 *
 * En un proyecto real esto vendría de un .env (ver Módulo 04), no en código.
 */

declare(strict_types=1);

// Lee de variables de entorno (Docker) con fallback a MAMP, igual que el resto del proyecto.
return [
    'host'    => getenv('DB_HOST') ?: '127.0.0.1',
    'port'    => getenv('DB_PORT') ?: '8889',   // MAMP Mac: 8889 · XAMPP/Win: 3306 · Docker: 3306
    'dbname'  => 'curso_tareas',
    'usuario' => getenv('DB_USER') ?: 'root',
    'pass'    => getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'root',
    'charset' => 'utf8mb4',
];

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
    'port'    => '8889',          // MAMP estilo Mac: 8889 · XAMPP/MAMP Windows clásico: 3306
    'dbname'  => 'curso_tareas',
    'usuario' => 'root',          // credenciales por defecto de MAMP
    'pass'    => 'root',
    'charset' => 'utf8mb4',
];

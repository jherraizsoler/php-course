<?php
/**
 * Configuración del Visor 3D.
 * Igual que el resto del curso: lee de variables de entorno (Docker) con
 * fallback a MAMP. En un proyecto real esto vendría de un .env (Módulo 04).
 */

declare(strict_types=1);

// Fichas profesionales por sector (casos de uso + métricas). Fuente única de verdad.
$sectores_full = require __DIR__ . '/sectores.php';

return [
    // --- Base de datos ---
    'host'    => getenv('DB_HOST') ?: '127.0.0.1',
    'port'    => getenv('DB_PORT') ?: '8889',   // MAMP Mac: 8889 · XAMPP/Win: 3306 · Docker: 3306
    'dbname'  => 'curso_3d',
    'usuario' => getenv('DB_USER') ?: 'root',
    'pass'    => getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'root',
    'charset' => 'utf8mb4',

    // --- Subidas ---
    'uploads_dir' => __DIR__ . '/uploads',
    // Límite de la app. OJO: PHP manda primero (upload_max_filesize / post_max_size en
    // docker/php-course.ini). Este valor debe ser <= el de PHP.
    'max_bytes'   => 1024 * 1024 * 1024,   // 1 GB por archivo
    // extensión => [tipo de visor, MIME para servirlo]
    'formatos' => [
        'stl'  => ['malla',       'model/stl'],
        'glb'  => ['malla',       'model/gltf-binary'],
        'gltf' => ['malla',       'model/gltf+json'],   // solo autocontenido (buffers/texturas embebidos)
        'obj'  => ['malla',       'text/plain'],
        'fbx'  => ['malla',       'application/octet-stream'],
        'vtp'  => ['volumetrico', 'application/xml'],
        'vti'  => ['volumetrico', 'application/xml'],
        'vtk'  => ['volumetrico', 'application/octet-stream'],
    ],

    'sectores_full' => $sectores_full,
    // Mapa simple value => label (con icono), derivado para selects y chips.
    'sectores' => array_map(fn($s) => $s['label'], $sectores_full),
];

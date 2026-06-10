<?php
/**
 * Punto de entrada. Demuestra el autoload PSR-4.
 *
 * PASOS:
 *   1) cd 04-php-avanzado/ejercicios/proyecto-composer
 *   2) composer dump-autoload       (genera vendor/autoload.php)
 *   3) php index.php
 *
 * Fíjate: NO hacemos require de Usuario.php ni de Saludador.php.
 * Composer las carga solas por su namespace.
 */

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Models\Usuario;
use App\Services\Saludador;

$usuarios = [
    new Usuario('Jorge', 'jorge@example.com'),
    new Usuario('Ana',   'ana@example.com'),
];

(new Saludador())->saludarA($usuarios);

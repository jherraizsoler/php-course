<?php
/**
 * Variables, tipos y operadores.
 * Ejecuta:  php 01-php-fundamentos/ejemplos/02-variables-tipos.php
 */

declare(strict_types=1);

// --- Tipos básicos ---
$nombre  = "Jorge";
$edad    = 23;
$altura  = 1.73;
$activo  = true;
$colores = ['rojo', 'azul', 'verde'];

echo "Tipos:\n";
var_dump($nombre, $edad, $altura, $activo, $colores);

// --- Operadores y == vs === ---
echo "\nComparaciones (¡cuidado con ==!):\n";
var_dump(0 == "0");    // true  → conversión de tipos
var_dump(0 === "0");   // false → tipo distinto (int vs string)
var_dump(5 <=> 3);     // 1     → spaceship

// --- Operadores modernos de PHP 8 ---
echo "\nOperadores modernos:\n";
$entrada = null;
$usuario = $entrada ?? 'invitado';        // null coalescing
echo "Usuario: {$usuario}\n";

$potencia = 2 ** 10;                       // exponenciación
echo "2^10 = {$potencia}\n";

echo "Mayor de edad: " . ($edad >= 18 ? 'sí' : 'no') . "\n";

// match (PHP 8)
$codigo = 2;
$rol = match ($codigo) {
    1       => 'admin',
    2, 3    => 'editor',
    default => 'invitado',
};
echo "Rol: {$rol}\n";

<?php
/**
 * Funciones: tipado, valores por defecto, variadic, closures y arrow functions.
 * Ejecuta:  php 02-funciones-arrays-strings/ejemplos/01-funciones.php
 */

declare(strict_types=1);

function saludar(string $nombre, string $saludo = "Hola"): string
{
    return "{$saludo}, {$nombre}!";
}

echo saludar("Jorge") . "\n";
echo saludar("Ana", "Buenas") . "\n";
echo saludar(saludo: "Hey", nombre: "Leo") . "\n";  // argumentos con nombre (PHP 8)

// Variadic: número variable de argumentos
function sumar(int ...$numeros): int
{
    return array_sum($numeros);
}
echo "Suma: " . sumar(1, 2, 3, 4) . "\n";

// Closure y arrow function
$doble = function (int $n): int {
    return $n * 2;
};

$factor = 3;
$porFactor = fn(int $n): int => $n * $factor; // captura $factor automáticamente

echo "Doble de 5: " . $doble(5) . "\n";
echo "5 x factor(3): " . $porFactor(5) . "\n";

<?php

declare(strict_types=1);

/**
 * Clase sencilla con una sola responsabilidad: operaciones aritméticas.
 * Su test está en CalculadoraTest.php.
 */
class Calculadora
{
    public function sumar(int $a, int $b): int
    {
        return $a + $b;
    }

    public function dividir(float $a, float $b): float
    {
        if ($b === 0.0) {
            throw new InvalidArgumentException('No se puede dividir por cero');
        }
        return $a / $b;
    }
}

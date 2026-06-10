<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Calculadora.php';

/**
 * Test de la clase Calculadora.
 *
 * PARA EJECUTARLO:
 *   1) cd 06-buenas-practicas/ejemplos/test
 *   2) composer require --dev phpunit/phpunit
 *   3) ./vendor/bin/phpunit CalculadoraTest.php
 */
final class CalculadoraTest extends TestCase
{
    public function test_suma_dos_numeros(): void
    {
        $calc = new Calculadora();
        $this->assertSame(5, $calc->sumar(2, 3));
    }

    public function test_division_normal(): void
    {
        $calc = new Calculadora();
        $this->assertSame(2.5, $calc->dividir(5, 2));
    }

    public function test_division_por_cero_lanza_excepcion(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new Calculadora())->dividir(10, 0);
    }
}

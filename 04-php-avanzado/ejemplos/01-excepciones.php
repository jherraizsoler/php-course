<?php
/**
 * Manejo de errores con excepciones.
 * Ejecuta:  php 04-php-avanzado/ejemplos/01-excepciones.php
 */

declare(strict_types=1);

// Excepción propia
class SaldoInsuficienteException extends Exception {}

function dividir(float $a, float $b): float
{
    if ($b === 0.0) {
        throw new InvalidArgumentException("No se puede dividir por cero");
    }
    return $a / $b;
}

function retirar(float $saldo, float $cantidad): float
{
    if ($cantidad > $saldo) {
        throw new SaldoInsuficienteException("Saldo insuficiente ({$saldo} < {$cantidad})");
    }
    return $saldo - $cantidad;
}

// try / catch / finally
try {
    echo "10 / 2 = " . dividir(10, 2) . "\n";
    echo "Nuevo saldo: " . retirar(100, 30) . "\n";
    echo "10 / 0 = " . dividir(10, 0) . "\n"; // explota aquí
} catch (InvalidArgumentException $e) {
    echo "⚠️ Argumento inválido: " . $e->getMessage() . "\n";
} catch (SaldoInsuficienteException $e) {
    echo "⚠️ " . $e->getMessage() . "\n";
} finally {
    echo "Operación finalizada (esto siempre se ejecuta)\n";
}

// Capturar por separado distintos tipos
try {
    retirar(50, 1000);
} catch (SaldoInsuficienteException $e) {
    echo "Capturada específica: " . $e->getMessage() . "\n";
}

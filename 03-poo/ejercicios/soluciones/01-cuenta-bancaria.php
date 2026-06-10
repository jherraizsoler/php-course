<?php
declare(strict_types=1);

class CuentaBancaria
{
    public function __construct(private float $saldo = 0.0) {}

    public function ingresar(float $cantidad): void
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("El ingreso debe ser positivo");
        }
        $this->saldo += $cantidad;
    }

    public function retirar(float $cantidad): void
    {
        if ($cantidad > $this->saldo) {
            throw new RuntimeException("Saldo insuficiente");
        }
        $this->saldo -= $cantidad;
    }

    public function getSaldo(): float
    {
        return $this->saldo;
    }
}

$cuenta = new CuentaBancaria(100);
$cuenta->ingresar(50);
$cuenta->retirar(30);
echo "Saldo: " . $cuenta->getSaldo() . " €\n"; // 120

try {
    $cuenta->retirar(1000);
} catch (RuntimeException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

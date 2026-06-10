<?php
declare(strict_types=1);

abstract class Empleado
{
    public function __construct(protected string $nombre) {}

    abstract public function calcularSueldo(): float;

    public function describir(): string
    {
        return sprintf("%s cobra %.2f €", $this->nombre, $this->calcularSueldo());
    }
}

class Asalariado extends Empleado
{
    public function __construct(string $nombre, private float $sueldoMensual)
    {
        parent::__construct($nombre);
    }

    public function calcularSueldo(): float
    {
        return $this->sueldoMensual;
    }
}

class PorHoras extends Empleado
{
    public function __construct(string $nombre, private int $horas, private float $tarifa)
    {
        parent::__construct($nombre);
    }

    public function calcularSueldo(): float
    {
        return $this->horas * $this->tarifa;
    }
}

$empleados = [
    new Asalariado("Ana", 2000),
    new PorHoras("Luis", 120, 18.5),
];

foreach ($empleados as $e) {
    echo $e->describir() . "\n";
}

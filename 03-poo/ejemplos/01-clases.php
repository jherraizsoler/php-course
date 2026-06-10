<?php
/**
 * Clases, objetos, constructor y promoción de propiedades (PHP 8).
 * Ejecuta:  php 03-poo/ejemplos/01-clases.php
 */

declare(strict_types=1);

class Coche
{
    private int $velocidad = 0;

    // Promoción de propiedades: declara $marca y $color y las asigna automáticamente
    public function __construct(
        public string $marca,
        public string $color,
    ) {}

    public function acelerar(int $kmh): void
    {
        $this->velocidad += $kmh;
        echo "{$this->marca} acelera a {$this->velocidad} km/h\n";
    }

    public function frenar(int $kmh): void
    {
        $this->velocidad = max(0, $this->velocidad - $kmh);
        echo "{$this->marca} frena a {$this->velocidad} km/h\n";
    }

    public function getVelocidad(): int
    {
        return $this->velocidad;
    }
}

$coche = new Coche("Seat", "rojo");
echo "Nuevo {$coche->marca} {$coche->color}\n";
$coche->acelerar(60);
$coche->acelerar(40);
$coche->frenar(30);
echo "Velocidad final: {$coche->getVelocidad()} km/h\n";

<?php
/**
 * Collections de Laravel/Illuminate, usadas en Perfex.
 * (composer require illuminate/collections)
 * Ejecuta:  php ejemplos/04-collections.php
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Collection;

// API encadenable: mucho más legible que array_map/filter/reduce sueltos
$total = collect([1, 2, 3, 4, 5, 6])
    ->filter(fn($n) => $n % 2 === 0)   // pares: 2, 4, 6
    ->map(fn($n) => $n * 10)           // 20, 40, 60
    ->sum();                            // 120

echo "Suma de pares x10: {$total}\n\n";

// Agrupar y resumir datos (típico en informes de un CRM)
$facturas = collect([
    ['cliente' => 'Ana',  'estado' => 'pagada',    'importe' => 100],
    ['cliente' => 'Ana',  'estado' => 'pendiente', 'importe' => 50],
    ['cliente' => 'Luis', 'estado' => 'pagada',    'importe' => 200],
    ['cliente' => 'Luis', 'estado' => 'pagada',    'importe' => 75],
]);

echo "Total facturado por cliente:\n";
$facturas
    ->groupBy('cliente')
    ->map(fn(Collection $g) => $g->sum('importe'))
    ->each(fn($total, $cliente) => print("  {$cliente}: {$total} €\n"));

echo "\nTotal cobrado (solo pagadas): "
    . $facturas->where('estado', 'pagada')->sum('importe') . " €\n";

echo "Clientes únicos: " . $facturas->pluck('cliente')->unique()->implode(', ') . "\n";

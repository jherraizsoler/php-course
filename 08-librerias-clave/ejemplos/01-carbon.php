<?php
/**
 * Carbon: manejo de fechas. (composer require nesbot/carbon)
 * Ejecuta:  cd 08-librerias-clave && composer install && php ejemplos/01-carbon.php
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Carbon\Carbon;

$ahora = Carbon::now();
echo "Ahora:            " . $ahora->format('d/m/Y H:i') . "\n";
echo "Dentro de 7 días: " . $ahora->copy()->addDays(7)->format('d/m/Y') . "\n";
echo "Hace 3 meses:     " . $ahora->copy()->subMonths(3)->format('d/m/Y') . "\n";

$vencimiento = Carbon::parse('2026-12-31');
echo "\nVencimiento de factura: " . $vencimiento->format('d/m/Y') . "\n";
echo "¿Ya venció?: " . ($vencimiento->isPast() ? 'sí' : 'no') . "\n";
echo "Días hasta vencer: " . (int) $ahora->diffInDays($vencimiento) . "\n";

// En español
echo "\nEn español: " . $ahora->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') . "\n";
echo "Humano:     " . Carbon::parse('2026-06-01')->locale('es')->diffForHumans() . "\n";

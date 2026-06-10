<?php
/**
 * Control de flujo: condicionales y bucles.
 * Ejecuta:  php 01-php-fundamentos/ejemplos/03-control-flujo.php
 */

declare(strict_types=1);

// --- Condicional ---
$edad = 16;
if ($edad >= 18) {
    echo "Mayor de edad\n";
} elseif ($edad >= 13) {
    echo "Adolescente\n";
} else {
    echo "Niño\n";
}

// --- Bucle for ---
echo "\nContando hasta 5: ";
for ($i = 1; $i <= 5; $i++) {
    echo $i . " ";
}
echo "\n";

// --- foreach sobre array asociativo ---
echo "\nPaíses:\n";
$paises = ['es' => 'España', 'fr' => 'Francia', 'it' => 'Italia'];
foreach ($paises as $codigo => $pais) {
    echo "  {$codigo} → {$pais}\n";
}

// --- while con break/continue ---
echo "\nNúmeros impares hasta 10: ";
$n = 0;
while (true) {
    $n++;
    if ($n > 10) break;        // salir del bucle
    if ($n % 2 === 0) continue; // saltar los pares
    echo $n . " ";
}
echo "\n";

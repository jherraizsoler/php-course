<?php
/**
 * Arrays: listas, asociativos y funciones funcionales (map/filter/reduce).
 * Ejecuta:  php 02-funciones-arrays-strings/ejemplos/02-arrays.php
 */

declare(strict_types=1);

$nums = [1, 2, 3, 4, 5];

echo "Original: " . implode(', ', $nums) . "\n";

// map: transformar cada elemento
$dobles = array_map(fn($n) => $n * 2, $nums);
echo "Dobles:   " . implode(', ', $dobles) . "\n";

// filter: quedarse con los que cumplen
$pares = array_filter($nums, fn($n) => $n % 2 === 0);
echo "Pares:    " . implode(', ', $pares) . "\n";

// reduce: colapsar a un valor
$total = array_reduce($nums, fn($acc, $n) => $acc + $n, 0);
echo "Suma:     {$total}\n";

// --- Array asociativo: simular una "fila" de base de datos ---
echo "\nUsuario:\n";
$usuario = [
    'nombre' => 'Jorge',
    'edad'   => 30,
    'roles'  => ['admin', 'editor'],
];
foreach ($usuario as $clave => $valor) {
    $valor = is_array($valor) ? implode(', ', $valor) : $valor;
    echo "  {$clave}: {$valor}\n";
}

// --- Ordenar por criterio personalizado ---
$usuarios = [
    ['nombre' => 'Ana',   'edad' => 25],
    ['nombre' => 'Luis',  'edad' => 40],
    ['nombre' => 'Marta', 'edad' => 32],
];
usort($usuarios, fn($a, $b) => $a['edad'] <=> $b['edad']); // por edad ascendente

echo "\nOrdenados por edad:\n";
foreach ($usuarios as $u) {
    echo "  {$u['nombre']} ({$u['edad']})\n";
}

<?php
/**
 * Mismo problema, dos versiones: código "sucio" vs código limpio.
 * Ejecuta:  php 06-buenas-practicas/ejemplos/01-refactor.php
 */

declare(strict_types=1);

// ============================================================
// ❌ ANTES: nombres crípticos, anidación, hace varias cosas
// ============================================================
function proc($d)
{
    $r = 0;
    if ($d) {
        foreach ($d as $i) {
            if ($i['a']) {
                if ($i['p'] > 0) {
                    $r = $r + $i['p'] * $i['c'];
                }
            }
        }
    }
    return $r;
}

// ============================================================
// ✅ DESPUÉS: nombres claros, early return, una responsabilidad,
//             tipado y estilo funcional
// ============================================================

/** @param array<int,array{activo:bool,precio:float,cantidad:int}> $lineas */
function calcularTotal(array $lineas): float
{
    $lineasFacturables = array_filter(
        $lineas,
        fn(array $linea) => $linea['activo'] && $linea['precio'] > 0
    );

    return array_reduce(
        $lineasFacturables,
        fn(float $total, array $linea) => $total + $linea['precio'] * $linea['cantidad'],
        0.0
    );
}

$carrito = [
    ['activo' => true,  'precio' => 10.0, 'cantidad' => 2],  // 20
    ['activo' => false, 'precio' => 99.0, 'cantidad' => 1],  // ignorado (inactivo)
    ['activo' => true,  'precio' => 5.5,  'cantidad' => 4],  // 22
];

// Mapeamos al formato que espera la versión "sucia" para comparar
$carritoSucio = array_map(
    fn($l) => ['a' => $l['activo'], 'p' => $l['precio'], 'c' => $l['cantidad']],
    $carrito
);

echo "Versión sucia:  " . proc($carritoSucio) . "\n";
echo "Versión limpia: " . calcularTotal($carrito) . "\n";
echo "\nMismo resultado, pero la segunda se lee y se mantiene sola.\n";

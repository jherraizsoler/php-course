<?php
declare(strict_types=1);

function contarPalabras(string $frase): array
{
    $frase    = mb_strtolower(trim($frase));
    $palabras = preg_split('/\s+/', $frase);   // partir por espacios
    $conteo   = [];
    foreach ($palabras as $palabra) {
        $palabra = trim($palabra, ".,;:!?");
        if ($palabra === '') {
            continue;
        }
        $conteo[$palabra] = ($conteo[$palabra] ?? 0) + 1;
    }
    arsort($conteo); // ordenar por frecuencia descendente
    return $conteo;
}

$frase = "el perro y el gato y el ratón";
foreach (contarPalabras($frase) as $palabra => $veces) {
    echo "{$palabra}: {$veces}\n";
}

<?php
declare(strict_types=1);

$datos = [4, 8, 15, 16, 23, 42];

$suma   = array_sum($datos);
$media  = $suma / count($datos);
$max    = max($datos);
$min    = min($datos);

printf("Datos:  %s\n", implode(', ', $datos));
printf("Suma:   %d\n", $suma);
printf("Media:  %.2f\n", $media);
printf("Máximo: %d\n", $max);
printf("Mínimo: %d\n", $min);

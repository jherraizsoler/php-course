<?php
/**
 * Tu primer programa PHP.
 * Ejecuta:  php 01-php-fundamentos/ejemplos/01-hola-mundo.php
 */

declare(strict_types=1);

echo "¡Hola, mundo!" . PHP_EOL;
echo "Estoy aprendiendo PHP " . PHP_VERSION . PHP_EOL;

// Una variable y su interpolación
$nombre = "Jorge";
echo "Me llamo {$nombre} y este es mi curso.\n";

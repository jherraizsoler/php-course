<?php
/**
 * Ejercicio 3: convertir un texto en "slug" (como cocur/slugify en un CRM profesional).
 * "Hola Mundo PHP!" → "hola-mundo-php"
 */

declare(strict_types=1);

function slug(string $texto): string
{
    // 1. A minúsculas (multibyte, respeta UTF-8)
    $texto = mb_strtolower($texto);
    // 2. Pasar acentos a su versión ASCII (á→a, ñ→n…) con un mapa fiable
    //    (iconv//TRANSLIT se comporta distinto en Windows, así que no dependemos de él)
    $acentos = [
        'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n',
        'à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u','ç'=>'c',
    ];
    $texto = strtr($texto, $acentos);
    // 3. Cualquier cosa que no sea letra/número → guion
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    // 4. Quitar guiones sobrantes de los extremos
    return trim($texto, '-');
}

$pruebas = ['Hola Mundo PHP!', '  Camión & Café  ', 'CodeIgniter 3.1.11'];
foreach ($pruebas as $p) {
    echo "'{$p}' → '" . slug($p) . "'\n";
}

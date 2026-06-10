<?php
/**
 * Ejecutor de ejemplos: corre un script .php del curso por la CLI de PHP
 * y devuelve su salida (stdout + stderr) como texto.
 *
 * Seguridad (entorno LOCAL de aprendizaje):
 *  - Solo ejecuta archivos .php dentro del propio curso.
 *  - Solo dentro de carpetas de ejemplos/soluciones/proyectos.
 *  - Nunca se ejecuta a sí mismo ni index/modulo.
 */

declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

$root = realpath(__DIR__);
$rel  = (string) ($_GET['file'] ?? '');

// Resolver y validar la ruta
$full = realpath($root . DIRECTORY_SEPARATOR . $rel);

if ($full === false || strncmp($full, $root . DIRECTORY_SEPARATOR, strlen($root) + 1) !== 0) {
    http_response_code(400);
    exit('Ruta no válida.');
}
if (strtolower((string) pathinfo($full, PATHINFO_EXTENSION)) !== 'php') {
    http_response_code(400);
    exit('Solo se pueden ejecutar archivos .php.');
}

$norm = str_replace('\\', '/', $full);
$permitido = preg_match('#/(ejemplos|soluciones)/#', $norm)
    || str_contains($norm, '/proyectos/crud-php-puro/');

if (!$permitido) {
    http_response_code(403);
    exit('Ese archivo no es ejecutable desde el panel.');
}

// Localizar el php.exe de MAMP (misma versión que corre Apache)
$php = 'C:\\MAMP\\bin\\php\\php' . PHP_VERSION . '\\php.exe';
if (!is_file($php)) {
    $candidatos = glob('C:\\MAMP\\bin\\php\\php*\\php.exe') ?: [];
    $php = $candidatos ? end($candidatos) : 'php';
}

// Ejecutar capturando stdout + stderr, con límite de tiempo
$descriptores = [
    1 => ['pipe', 'w'],   // stdout
    2 => ['pipe', 'w'],   // stderr
];

$proceso = proc_open(
    [$php, '-d', 'display_errors=1', '-d', 'max_execution_time=20', $full],
    $descriptores,
    $pipes,
    dirname($full)        // cwd: por si el script usa rutas relativas
);

if (!is_resource($proceso)) {
    http_response_code(500);
    exit('No se pudo lanzar PHP (' . $php . ').');
}

$salida = stream_get_contents($pipes[1]);
$errores = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);
$codigo = proc_close($proceso);

$texto = $salida;
if (trim($errores) !== '') {
    $texto .= "\n----- stderr -----\n" . $errores;
}
if (trim($texto) === '') {
    $texto = "(el script no produjo salida; código de salida {$codigo})";
}

if ($codigo !== 0) {
    http_response_code(500);
}

echo "$ php " . basename($full) . "\n\n" . $texto;

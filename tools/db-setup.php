<?php
/**
 * Crea e inicializa las bases de datos del curso (idempotente).
 *
 * Uso:   php tools/db-setup.php
 * Config opcional por variables de entorno:
 *   DB_HOST (def. 127.0.0.1)  DB_PORT (def. 8889)  DB_USER (def. root)  DB_PASS (def. root)
 *
 * MAMP (estilo Mac) suele usar el puerto 8889; XAMPP/MAMP Windows clásico usan 3306.
 */

declare(strict_types=1);
mysqli_report(MYSQLI_REPORT_OFF);

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = (int) (getenv('DB_PORT') ?: 8889);
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'root';

echo "→ Conectando a MySQL {$host}:{$port} (usuario {$user})...\n";
$c = @mysqli_connect($host, $user, $pass, '', $port);
if (! $c) {
    fwrite(STDERR, "❌ No se pudo conectar a MySQL (errno " . mysqli_connect_errno() . ").\n");
    fwrite(STDERR, "   ¿Está arrancado MAMP/XAMPP? Ajusta DB_HOST/DB_PORT/DB_USER/DB_PASS.\n");
    fwrite(STDERR, "   Ej. (PowerShell):  \$env:DB_PORT='3306'; php tools/db-setup.php\n");
    exit(1);
}

$base    = dirname(__DIR__);
$schemas = [
    $base . '/05-php-web/ejemplos/db/schema.sql',     // BBDD 'curso'        (tabla usuarios)
    $base . '/proyectos/crud-php-puro/schema.sql',    // BBDD 'curso_tareas' (tabla tareas)
];

$errores = 0;
foreach ($schemas as $sql) {
    if (! is_file($sql)) { echo "· (omito, no existe) " . basename($sql) . "\n"; continue; }
    echo "· Importando " . basename(dirname($sql)) . "/" . basename($sql) . "\n";
    if (mysqli_multi_query($c, (string) file_get_contents($sql))) {
        do { if ($r = mysqli_store_result($c)) mysqli_free_result($r); }
        while (mysqli_more_results($c) && mysqli_next_result($c));
    }
    if ($e = mysqli_error($c)) { fwrite(STDERR, "  ⚠️  {$e}\n"); $errores++; }
}

mysqli_close($c);
echo $errores === 0 ? "✅ Base(s) de datos lista(s).\n" : "⚠️  Terminado con avisos.\n";
exit($errores === 0 ? 0 : 0);

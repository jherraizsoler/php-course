<?php
/**
 * Diagnóstico del entorno PHP.
 * Ejecuta:  php 00-entorno/ejemplos/diagnostico.php
 *
 * Comprueba que tienes todo lo necesario para seguir el curso y trabajar
 * en un proyecto real tipo CodeIgniter 3.
 */

declare(strict_types=1);

echo "==================================================\n";
echo "  DIAGNÓSTICO DEL ENTORNO — PHP Course\n";
echo "==================================================\n\n";

// 1. Versión de PHP
echo "PHP versión: " . PHP_VERSION . "\n";
if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
    echo "  ✅ Versión correcta (>= 8.0, como un proyecto profesional real).\n";
} else {
    echo "  ⚠️  Recomendado PHP 8.2 u 8.3. Usa el de MAMP: C:\\MAMP\\bin\\php\\php8.3.1\\php.exe\n";
}
echo "\n";

// 2. Extensiones necesarias para un proyecto real
$necesarias = ['pdo_mysql', 'mbstring', 'openssl', 'curl', 'json', 'gd', 'intl'];
echo "Extensiones:\n";
foreach ($necesarias as $ext) {
    $ok = extension_loaded($ext);
    printf("  %s %s\n", $ok ? '✅' : '❌', $ext);
}
echo "\n";

// 3. ¿Composer disponible?
echo "Composer: ";
$composer = @shell_exec('composer --version 2>&1');
echo $composer ? "✅ " . trim($composer) . "\n" : "❌ no encontrado en el PATH\n";
echo "\n";

// 4. Límites de configuración (útiles para subir archivos, PDFs, etc.)
echo "Configuración relevante (php.ini):\n";
echo "  memory_limit        = " . ini_get('memory_limit') . "\n";
echo "  upload_max_filesize = " . ini_get('upload_max_filesize') . "\n";
echo "  post_max_size       = " . ini_get('post_max_size') . "\n";
echo "  max_execution_time  = " . ini_get('max_execution_time') . "s\n";
echo "  date.timezone       = " . (ini_get('date.timezone') ?: '(no definida)') . "\n";

echo "\n==================================================\n";
echo "  Si ves ❌ en pdo_mysql, mbstring, curl u openssl,\n";
echo "  actívalas en php.ini (MAMP las trae, solo descomenta).\n";
echo "==================================================\n";

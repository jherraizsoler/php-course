<?php
$env  = __DIR__ . '/../proyectos/login-seguro/.env';
$hash = password_hash('demo1234', PASSWORD_DEFAULT);

$content = file_get_contents($env);
// Reemplaza cualquier línea que contenga DEMO_PASS_HASH= con el hash correcto
$content = preg_replace('/^.*DEMO_PASS_HASH=.*$/m', "DEMO_PASS_HASH='" . $hash . "'", $content);
file_put_contents($env, $content);

echo "Hash   : $hash\n";
echo "Verify : " . (password_verify('demo1234', $hash) ? 'OK ✅' : 'FAIL ❌') . "\n";
echo "Fichero: $env\n";

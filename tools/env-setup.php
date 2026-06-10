<?php
/**
 * Crea el archivo .env de la demo login-seguro (si no existe), generando
 * automáticamente el hash de la contraseña demo y un secreto TOTP nuevo.
 *
 * Uso:  php tools/env-setup.php
 */

declare(strict_types=1);

$dir     = dirname(__DIR__) . '/proyectos/login-seguro';
$env     = $dir . '/.env';
$example = $dir . '/.env.example';

if (is_file($env)) {
    echo "· login-seguro/.env ya existe (no lo toco).\n";
    exit(0);
}
if (! is_file($example)) {
    echo "· No hay .env.example en login-seguro; nada que hacer.\n";
    exit(0);
}

$hash   = password_hash('demo1234', PASSWORD_DEFAULT);
$secret = 'EKFD4N52UIYNLG2J';   // fallback

$autoload = $dir . '/vendor/autoload.php';
if (is_file($autoload)) {
    require $autoload;
    if (class_exists(\PragmaRX\Google2FA\Google2FA::class)) {
        $secret = (new \PragmaRX\Google2FA\Google2FA())->generateSecretKey();
    }
}

$contenido = (string) file_get_contents($example);
$contenido = preg_replace('/^DEMO_PASS_HASH=.*$/m', "DEMO_PASS_HASH='{$hash}'", $contenido);
$contenido = preg_replace('/^TOTP_SECRET=.*$/m', "TOTP_SECRET={$secret}", $contenido);

file_put_contents($env, $contenido);
echo "✅ login-seguro/.env creado (usuario: jorge · contraseña: demo1234).\n";

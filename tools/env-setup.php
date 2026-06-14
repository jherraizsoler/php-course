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
// Usar preg_replace_callback para evitar que '$' del hash se trate como backreference.
$contenido = preg_replace_callback('/^DEMO_PASS_HASH=.*$/m', fn() => "DEMO_PASS_HASH='{$hash}'", $contenido);
$contenido = preg_replace_callback('/^TOTP_SECRET=.*$/m', fn() => "TOTP_SECRET={$secret}", $contenido);

file_put_contents($env, $contenido);
echo "✅ login-seguro/.env creado (usuario: jorge · contraseña: demo1234).\n";

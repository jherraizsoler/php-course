<?php
/**
 * Sesiones: contador de visitas.
 * Sírvelo con MAMP:  http://localhost/php-course/05-php-web/ejemplos/02-sesiones.php
 * Recarga la página varias veces y verás cómo sube el contador.
 */

declare(strict_types=1);

session_start();   // SIEMPRE antes de imprimir nada

// Inicializa o incrementa
$_SESSION['visitas'] = ($_SESSION['visitas'] ?? 0) + 1;

// Logout: ?reset=1 destruye la sesión
if (isset($_GET['reset'])) {
    session_destroy();
    header('Location: 02-sesiones.php');  // redirige limpio
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><title>Sesiones</title></head>
<body style="font-family: sans-serif; max-width: 480px; margin: 40px auto;">
    <h1>Contador de visitas</h1>
    <p>Has visitado esta página <strong><?= (int) $_SESSION['visitas'] ?></strong> veces
       en esta sesión.</p>
    <p><a href="?reset=1">Reiniciar sesión</a></p>
    <hr>
    <small>El contador se guarda en <code>$_SESSION</code>, en el servidor. Si cierras el
    navegador o reinicias la sesión, vuelve a empezar.</small>
</body>
</html>

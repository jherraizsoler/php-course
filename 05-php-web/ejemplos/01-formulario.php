<?php
/**
 * Formulario con validación de entrada y escape de salida (anti-XSS).
 * Sírvelo con MAMP: ábrelo en el navegador (ruta 05-php-web/ejemplos/01-formulario.php desde la raíz del proyecto)
 */

declare(strict_types=1);

$errores = [];
$nombre  = '';
$email   = '';
$enviado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Recoger y limpiar
    $nombre = trim($_POST['nombre'] ?? '');
    $email  = trim($_POST['email'] ?? '');

    // 2) Validar
    if ($nombre === '') {
        $errores[] = 'El nombre es obligatorio.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El email no es válido.';
    }

    $enviado = empty($errores);
}

/** Helper para escapar salida (anti-XSS). En CI3 esto es html_escape(). */
function e(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><title>Formulario</title></head>
<body style="font-family: sans-serif; max-width: 480px; margin: 40px auto;">
    <h1>Registro</h1>

    <?php if ($enviado): ?>
        <p style="color: green;">
            ✅ ¡Hola <?= e($nombre) ?>! Te registraremos con <?= e($email) ?>.
        </p>
    <?php else: ?>
        <?php foreach ($errores as $error): ?>
            <p style="color: red;">⚠️ <?= e($error) ?></p>
        <?php endforeach; ?>

        <form method="post">
            <p>
                <label>Nombre:<br>
                    <input type="text" name="nombre" value="<?= e($nombre) ?>">
                </label>
            </p>
            <p>
                <label>Email:<br>
                    <input type="text" name="email" value="<?= e($email) ?>">
                </label>
            </p>
            <button type="submit">Enviar</button>
        </form>
    <?php endif; ?>
</body>
</html>

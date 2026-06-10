<?php
/**
 * Conexión a MySQL con PDO y consultas preparadas (anti-inyección SQL).
 *
 * REQUISITOS:
 *   1) Arranca MySQL en MAMP.
 *   2) Importa db/schema.sql (crea la BBDD "curso" y la tabla "usuarios").
 *   3) Ajusta usuario/contraseña/puerto abajo si tu MAMP no usa root/root.
 *   4) Sírvelo: ábrelo en el navegador vía MAMP (ruta 05-php-web/ejemplos/03-pdo.php)
 *
 * NOTA: requiere la extensión pdo_mysql activa. En MAMP suele estarlo bajo Apache.
 *       (Por CLI puede estar desactivada en el php.ini de la línea de comandos.)
 */

declare(strict_types=1);

// --- Configuración de conexión (ajústala a tu MAMP) ---
const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';        // MAMP Windows: 3306 (a veces 8889 en Mac)
const DB_NAME = 'curso';
const DB_USER = 'root';
const DB_PASS = 'root';

header('Content-Type: text/html; charset=utf-8');

function e(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

try {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME);
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,   // usa preparadas nativas (más seguro)
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    exit('No se pudo conectar a MySQL. ¿Arrancaste MAMP e importaste schema.sql?<br>'
        . 'Detalle: ' . e($e->getMessage()));
}

// --- INSERTAR un usuario nuevo si se envía el formulario ---
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    if ($nombre !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // ✅ Consulta preparada: los datos NUNCA se concatenan en la SQL
        $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)');
        try {
            $stmt->execute(['nombre' => $nombre, 'email' => $email]);
            $mensaje = "Usuario #{$pdo->lastInsertId()} creado.";
        } catch (PDOException $e) {
            $mensaje = 'Error (¿email duplicado?): ' . e($e->getMessage());
        }
    } else {
        $mensaje = 'Datos inválidos.';
    }
}

// --- LEER todos los usuarios ---
$usuarios = $pdo->query('SELECT id, nombre, email, creado_en FROM usuarios ORDER BY id')->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><title>PDO + MySQL</title></head>
<body style="font-family: sans-serif; max-width: 600px; margin: 40px auto;">
    <h1>Usuarios (PDO + MySQL)</h1>
    <?php if ($mensaje): ?><p style="color: blue;"><?= e($mensaje) ?></p><?php endif; ?>

    <table border="1" cellpadding="6" style="border-collapse: collapse; width: 100%;">
        <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Creado</th></tr>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= (int) $u['id'] ?></td>
                <td><?= e($u['nombre']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><?= e((string) $u['creado_en']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Añadir usuario</h2>
    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Crear</button>
    </form>
</body>
</html>

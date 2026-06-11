<?php

declare(strict_types=1);

/**
 * Conexión PDO a la BBDD del visor (curso_3d). Patrón singleton:
 * una sola conexión por petición. Mismo patrón que crud-php-puro.
 */
final class Database
{
    private static ?PDO $instancia = null;

    public static function conexion(): PDO
    {
        if (self::$instancia === null) {
            $cfg = require __DIR__ . '/config.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $cfg['host'],
                $cfg['port'],
                $cfg['dbname'],
                $cfg['charset']
            );

            self::$instancia = new PDO($dsn, $cfg['usuario'], $cfg['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }

        return self::$instancia;
    }
}

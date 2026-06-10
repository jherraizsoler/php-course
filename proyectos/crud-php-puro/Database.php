<?php

declare(strict_types=1);

/**
 * Gestiona la conexión PDO. Patrón singleton: una sola conexión por petición.
 */
final class Database
{
    private static ?PDO $instancia = null;

    /** Devuelve la conexión PDO (la crea la primera vez). */
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

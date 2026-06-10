<?php
/**
 * Herencia, clases abstractas e interfaces.
 * Simula cómo Perfex abstrae sus canales de notificación / pasarelas.
 * Ejecuta:  php 03-poo/ejemplos/02-herencia-interfaces.php
 */

declare(strict_types=1);

// --- Interfaz: el contrato ---
interface Notificable
{
    public function enviar(string $mensaje): bool;
}

// --- Clase abstracta: base común ---
abstract class Canal implements Notificable
{
    abstract public function enviar(string $mensaje): bool;

    protected function log(string $linea): void
    {
        echo "[" . static::class . "] {$linea}\n";
    }
}

// --- Implementaciones concretas ---
class CanalEmail extends Canal
{
    public function enviar(string $mensaje): bool
    {
        $this->log("Email → {$mensaje}");
        return true;
    }
}

class CanalSms extends Canal
{
    public function enviar(string $mensaje): bool
    {
        $this->log("SMS → {$mensaje}");
        return true;
    }
}

// Programamos contra la INTERFAZ, no contra la clase concreta:
function notificar(Notificable $canal, string $mensaje): void
{
    $canal->enviar($mensaje);
}

foreach ([new CanalEmail(), new CanalSms()] as $canal) {
    notificar($canal, "Tu factura está lista");
}

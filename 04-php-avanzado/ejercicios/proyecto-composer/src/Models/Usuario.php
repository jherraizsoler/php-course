<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Clase App\Models\Usuario.
 * Por la convención PSR-4 vive en src/Models/Usuario.php
 * (namespace "App\Models" → carpeta "src/Models" según el composer.json).
 */
class Usuario
{
    public function __construct(
        public string $nombre,
        public string $email,
    ) {}

    public function saludar(): string
    {
        return "Hola, soy {$this->nombre} ({$this->email})";
    }
}

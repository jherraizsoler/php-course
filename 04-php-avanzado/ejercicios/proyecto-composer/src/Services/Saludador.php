<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Usuario;

class Saludador
{
    /** @param Usuario[] $usuarios */
    public function saludarA(array $usuarios): void
    {
        foreach ($usuarios as $usuario) {
            echo $usuario->saludar() . "\n";
        }
    }
}

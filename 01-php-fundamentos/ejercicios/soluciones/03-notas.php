<?php
declare(strict_types=1);

function clasificarNota(int $nota): string
{
    return match (true) {
        $nota < 0 || $nota > 10 => 'Nota inválida',
        $nota <= 4              => 'Suspenso',
        $nota <= 6              => 'Aprobado',
        $nota <= 8              => 'Notable',
        default                 => 'Sobresaliente',
    };
}

foreach ([3, 5, 7, 9, 10, 11] as $nota) {
    echo "Nota {$nota}: " . clasificarNota($nota) . "\n";
}

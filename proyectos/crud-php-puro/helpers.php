<?php

declare(strict_types=1);

/** Escapa una cadena para imprimirla en HTML de forma segura (anti-XSS). */
function e(?string $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/** Renderiza una vista dentro del layout y devuelve el HTML. */
function render(string $vista, array $datos = []): void
{
    extract($datos, EXTR_SKIP);
    $vistaPath = __DIR__ . "/views/{$vista}.php";

    ob_start();
    require $vistaPath;        // genera $contenido
    $contenido = ob_get_clean();

    require __DIR__ . '/views/layout.php';
}

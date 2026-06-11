<?php

declare(strict_types=1);

/** Escapa para HTML (anti-XSS). */
function e($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

/** Tamaño legible: 1536 → "1.5 KB". */
function bytes_legibles(int $b): string
{
    if ($b <= 0) return '—';
    $u = ['B', 'KB', 'MB', 'GB'];
    $i = (int) floor(log($b, 1024));
    $i = min($i, count($u) - 1);
    return round($b / (1024 ** $i), 1) . ' ' . $u[$i];
}

/** Token CSRF de la sesión (uno por sesión). */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/** Comprueba el token CSRF recibido en un POST. */
function csrf_ok($token): bool
{
    return is_string($token) && ! empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

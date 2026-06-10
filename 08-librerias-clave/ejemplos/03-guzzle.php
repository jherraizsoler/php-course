<?php
/**
 * Guzzle: cliente HTTP para consumir APIs. (composer require guzzlehttp/guzzle)
 * Ejecuta:  php ejemplos/03-guzzle.php   (necesita conexión a internet)
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

$client = new Client(['timeout' => 10]);

try {
    // API pública de GitHub: datos del repo de CodeIgniter 4
    $res = $client->get('https://api.github.com/repos/codeigniter4/CodeIgniter4', [
        'headers' => ['Accept' => 'application/vnd.github+json'],
    ]);

    $repo = json_decode((string) $res->getBody(), true);

    echo "Repositorio: {$repo['full_name']}\n";
    echo "Descripción: {$repo['description']}\n";
    echo "⭐ Estrellas: {$repo['stargazers_count']}\n";
    echo "🍴 Forks:     {$repo['forks_count']}\n";
    echo "Lenguaje:    {$repo['language']}\n";
} catch (GuzzleException $e) {
    echo "Error de red: " . $e->getMessage() . "\n";
}

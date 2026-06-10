<?php
/**
 * Faker: generar datos falsos realistas. (composer require --dev fakerphp/faker)
 * Útil para poblar la BBDD en desarrollo o en tests.
 * Ejecuta:  php ejemplos/02-faker.php
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$faker = Faker\Factory::create('es_ES');

echo "10 usuarios falsos:\n";
echo str_repeat('-', 60) . "\n";
for ($i = 1; $i <= 10; $i++) {
    printf(
        "%2d. %-25s %-30s %s\n",
        $i,
        $faker->name(),
        $faker->safeEmail(),
        $faker->phoneNumber()
    );
}

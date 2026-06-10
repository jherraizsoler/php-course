<?php
declare(strict_types=1);

function celsiusAFahrenheit(float $c): float
{
    return $c * 9 / 5 + 32;
}

echo "Celsius → Fahrenheit\n";
echo "--------------------\n";
for ($c = 0; $c <= 100; $c += 10) {
    printf("%6.1f°C = %6.1f°F\n", $c, celsiusAFahrenheit((float) $c));
}

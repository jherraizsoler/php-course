# Ejercicios — Fundamentos PHP 🏋️

Intenta resolverlos **sin mirar** las soluciones. Crea tu propio archivo `.php` y ejecútalo con
`php tu-archivo.php`. Cuando termines, compara con `soluciones/`.

### 1. FizzBuzz
Recorre del 1 al 100. Por cada número:
- Si es múltiplo de 3 → imprime `Fizz`.
- Si es múltiplo de 5 → imprime `Buzz`.
- Si es múltiplo de ambos → imprime `FizzBuzz`.
- Si no → imprime el número.

> Clásico de entrevistas. Practica `%`, `if/elseif` y bucles.

### 2. Conversor de temperatura
Crea una función que reciba grados Celsius y devuelva Fahrenheit (`F = C * 9/5 + 32`).
Imprime una tabla de 0°C a 100°C de 10 en 10.

### 3. Clasificador de notas
Dada una nota del 0 al 10, imprime:
- 0–4 → `Suspenso`
- 5–6 → `Aprobado`
- 7–8 → `Notable`
- 9–10 → `Sobresaliente`

Usa `match(true)` para hacerlo elegante. Pruébalo con varias notas.

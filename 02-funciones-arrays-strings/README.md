# 02 · Funciones, arrays y strings 🟢

El 80% de tu trabajo diario en PHP es **mover datos por arrays** y **manipular texto**. Domínalo.

```bash
php 02-funciones-arrays-strings/ejemplos/01-funciones.php
```

---

## 1. Funciones

```php
// Tipado de parámetros y retorno (buena práctica desde PHP 7+)
function saludar(string $nombre, string $saludo = "Hola"): string
{
    return "{$saludo}, {$nombre}!";
}

echo saludar("Jorge");              // Hola, Jorge!
echo saludar("Ana", "Buenas");      // Buenas, Ana!

// Argumentos con nombre (PHP 8):
echo saludar(saludo: "Hey", nombre: "Leo");

// Número variable de argumentos (variadic):
function sumar(int ...$numeros): int
{
    return array_sum($numeros);
}
echo sumar(1, 2, 3, 4); // 10
```

### Funciones flecha y closures

```php
// Closure (función anónima)
$doble = function (int $n): int {
    return $n * 2;
};

// Arrow function (PHP 7.4+) — captura variables del entorno automáticamente
$factor = 3;
$triple = fn(int $n): int => $n * $factor;

echo $doble(5);  // 10
echo $triple(5); // 15
```

> 💡 Las closures son la base del estilo funcional que usan `array_map`, `array_filter` y la
> librería `illuminate/collections` (que usa un CRM profesional). Mira el [Módulo 08](../08-librerias-clave/).

---

## 2. Arrays

PHP tiene un único tipo `array` que sirve como **lista** y como **mapa/diccionario**.

```php
// Lista indexada
$frutas = ['manzana', 'pera', 'uva'];
echo $frutas[0];          // manzana
$frutas[] = 'kiwi';       // añadir al final

// Array asociativo (clave => valor)
$usuario = [
    'nombre' => 'Jorge',
    'edad'   => 30,
    'roles'  => ['admin', 'editor'],
];
echo $usuario['nombre'];
echo $usuario['roles'][0]; // admin

// Recorrer
foreach ($usuario as $clave => $valor) {
    echo "{$clave}: " . (is_array($valor) ? implode(', ', $valor) : $valor) . "\n";
}
```

### Funciones de array imprescindibles

```php
$nums = [1, 2, 3, 4, 5];

count($nums);                              // 5
in_array(3, $nums);                        // true
array_keys($usuario);                      // ['nombre','edad','roles']
array_values($usuario);

// Transformar (map):
$dobles = array_map(fn($n) => $n * 2, $nums);   // [2,4,6,8,10]

// Filtrar:
$pares = array_filter($nums, fn($n) => $n % 2 === 0); // [2,4]

// Reducir a un solo valor:
$total = array_reduce($nums, fn($acc, $n) => $acc + $n, 0); // 15

// Ordenar:
sort($nums);                 // por valor (reindexar)
usort($nums, fn($a,$b) => $b <=> $a); // orden personalizado (descendente)

// Unir / partir:
implode(', ', $nums);        // "1, 2, 3, 4, 5"  (array → string)
explode(',', "a,b,c");       // ['a','b','c']     (string → array)

// Combinar arrays:
$merge   = array_merge($a, $b);
$spread  = [...$a, ...$b];    // spread operator (PHP 7.4+)
```

> 🧠 `array_map` + `array_filter` + `array_reduce` son el corazón del estilo funcional. Si dominas
> estos tres, manipulas cualquier colección de datos sin bucles farragosos.

---

## 3. Strings (texto)

```php
$texto = "  Hola Mundo PHP  ";

strlen($texto);                    // longitud (bytes)
mb_strlen($texto);                 // longitud (multibyte, para acentos/UTF-8)
trim($texto);                      // quita espacios → "Hola Mundo PHP"
strtoupper($texto);                // MAYÚSCULAS
strtolower($texto);                // minúsculas
ucfirst("hola");                   // "Hola"
ucwords("hola mundo");             // "Hola Mundo"
str_replace("PHP", "8.3", $texto); // reemplazar
substr($texto, 0, 4);              // extraer
str_contains($texto, "Mundo");     // true (PHP 8)
str_starts_with($texto, "  Hola"); // true (PHP 8)
explode(" ", trim($texto));        // partir por espacios

// Formateo profesional con sprintf:
$precio = 1234.5;
echo sprintf("Total: %.2f €", $precio);   // Total: 1234.50 €
echo number_format($precio, 2, ',', '.'); // 1.234,50  (formato español)
```

> ⚠️ Para texto con acentos/UTF-8 usa siempre las funciones `mb_*` (`mb_strlen`, `mb_strtoupper`,
> `mb_substr`). Es el tipo de detalle que rompe proyectos reales con datos en español.

---

## 🏋️ Ejercicios

1. **Estadísticas de array** — dado `[4, 8, 15, 16, 23, 42]`, calcula suma, media, máximo y mínimo
   usando funciones de array (sin bucles manuales).
2. **Contador de palabras** — dada una frase, cuenta cuántas veces aparece cada palabra
   (devuelve un array asociativo `palabra => veces`).
3. **Slug** — convierte `"Hola Mundo PHP!"` en `"hola-mundo-php"` (minúsculas, sin símbolos,
   espacios por guiones). Esto es justo lo que hace `cocur/slugify` en un CRM profesional.

Soluciones en [`ejercicios/soluciones/`](ejercicios/soluciones/).

---

## 📚 Resumen

- Tipa siempre tus funciones: `function x(int $a): string`.
- `array_map / filter / reduce` > bucles manuales.
- `implode` / `explode` para convertir entre array y string.
- Usa `mb_*` y `str_contains/starts_with/ends_with` (PHP 8).

---

⬅️ Anterior: [**01 · Fundamentos de PHP**](../01-php-fundamentos/README.md) · 🏠 [**Índice**](../README.md) · ➡️ Siguiente: [**03 · POO**](../03-poo/README.md)

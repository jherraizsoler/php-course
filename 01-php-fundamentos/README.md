# 01 · Fundamentos de PHP 🟢

Aquí aprendes la sintaxis básica del lenguaje. Todo esto se ejecuta por **línea de comandos**,
sin MAMP:

```bash
php 01-php-fundamentos/ejemplos/01-hola-mundo.php
```

---

## 1. Las etiquetas PHP

Todo código PHP va dentro de `<?php ... ?>`. En archivos que son **solo PHP** (como los modelos o
controladores de CodeIgniter) se omite la etiqueta de cierre `?>` para evitar espacios accidentales.

```php
<?php
echo "Hola mundo";
// La etiqueta de cierre ?> se omite a propósito en archivos solo-PHP
```

`echo` imprime texto. `\n` es un salto de línea.

---

## 2. Variables y tipos

Las variables empiezan por `$`. PHP es de **tipado dinámico**: no declaras el tipo, lo infiere.

```php
$nombre  = "Jorge";        // string
$edad    = 30;             // int
$altura  = 1.78;           // float
$activo  = true;           // bool
$nada    = null;           // null
$colores = ['rojo', 'azul']; // array
```

Comprobar el tipo:

```php
var_dump($edad);        // int(30)
echo gettype($altura);  // double
echo PHP_EOL;           // salto de línea independiente del SO
```

> 🧠 **Tipado estricto:** poniendo `declare(strict_types=1);` en la primera línea, PHP deja de
> convertir tipos automáticamente en las funciones. Es una **buena práctica** que usaremos siempre.

---

## 3. Strings (cadenas)

```php
$nombre = "Jorge";

echo 'Comillas simples: $nombre';   // literal: $nombre
echo "Comillas dobles: $nombre";    // interpola: Jorge
echo "Llaves: {$nombre} Soler";     // forma recomendada para interpolar

// Concatenación con el punto:
echo "Hola " . $nombre . "!";

// Heredoc (texto largo con interpolación):
$html = <<<HTML
<p>Hola, {$nombre}</p>
HTML;
```

Funciones de string que usarás mucho: `strlen()`, `strtoupper()`, `strtolower()`, `trim()`,
`str_replace()`, `substr()`, `sprintf()`. (Más en el [Módulo 02](../02-funciones-arrays-strings/).)

---

## 4. Operadores

```php
// Aritméticos
$a = 10 + 3;   // 13
$b = 10 % 3;   // 1  (resto / módulo)
$c = 2 ** 8;   // 256 (potencia)

// Comparación — ¡OJO con == vs ===!
var_dump(0 == "0");    // true  (compara valor, convierte tipos)
var_dump(0 === "0");   // false (compara valor Y tipo) ← usa SIEMPRE ===
var_dump(5 <=> 3);     // 1     (spaceship: -1, 0 o 1)

// Lógicos
$x = true && false;    // false
$y = true || false;    // true

// Operadores modernos muy útiles:
$nombre = $entrada ?? 'invitado';        // null coalescing: si null, usa 'invitado'
$valor  = $config['x'] ??= 'por defecto'; // asigna si no existe
echo $edad >= 18 ? "mayor" : "menor";     // ternario
```

> ⚠️ **Regla de oro:** usa `===` y `!==` casi siempre. El `==` provoca bugs sutiles por la
> conversión automática de tipos (*type juggling*).

---

## 5. Control de flujo

### Condicionales

```php
if ($edad >= 18) {
    echo "Mayor de edad";
} elseif ($edad >= 13) {
    echo "Adolescente";
} else {
    echo "Niño";
}

// match (PHP 8+) — como switch pero con === y devuelve valor:
$rol = match ($codigo) {
    1       => 'admin',
    2, 3    => 'editor',
    default => 'invitado',
};
```

### Bucles

```php
for ($i = 0; $i < 5; $i++) {
    echo $i;
}

$i = 0;
while ($i < 5) { echo $i; $i++; }

foreach (['a', 'b', 'c'] as $letra) {
    echo $letra;
}

foreach (['es' => 'España', 'fr' => 'Francia'] as $codigo => $pais) {
    echo "$codigo => $pais\n";
}

// Control: break (salir), continue (siguiente iteración)
```

---

## 6. Mostrar y depurar

```php
echo $variable;          // imprime texto
print_r($array);         // muestra arrays legibles
var_dump($variable);     // muestra tipo + valor (lo más usado para depurar)
```

> 💡 En proyectos reales como Perfex se usa `symfony/var-dumper` con la función `dump()` y `dd()`
> (*dump and die*), mucho más bonitas. Lo veremos en el [Módulo 08](../08-librerias-clave/).

---

## 🏋️ Ejercicios

Ve a [`ejercicios/`](ejercicios/) y resuélvelos. Las soluciones están en `ejercicios/soluciones/`.

1. **FizzBuzz** — del 1 al 100: múltiplos de 3 → "Fizz", de 5 → "Buzz", de ambos → "FizzBuzz".
2. **Conversor de temperatura** — celsius a fahrenheit y viceversa.
3. **Clasificador de notas** — nota (0-10) → "Suspenso/Aprobado/Notable/Sobresaliente".

---

## 📚 Resumen

- `<?php`, `echo`, `$variables`, tipado dinámico.
- Usa `===` no `==`. Usa `declare(strict_types=1)`.
- `??`, `?:` y `match` son tus amigos en PHP 8.
- Control de flujo: `if/elseif/else`, `match`, `for`, `while`, `foreach`.

➡️ Siguiente: **[02 · Funciones, arrays y strings](../02-funciones-arrays-strings/)**

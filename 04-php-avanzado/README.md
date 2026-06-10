# 04 · PHP avanzado y Composer 🟡

Excepciones, autoload y **Composer**: el pegamento que hace funcionar cualquier proyecto moderno,
incluido Perfex (que tiene 30+ librerías instaladas vía Composer).

```bash
php 04-php-avanzado/ejemplos/01-excepciones.php
```

---

## 1. Manejo de errores con excepciones

En código profesional **no devuelves `false` cuando algo falla**: lanzas una excepción.

```php
function dividir(float $a, float $b): float
{
    if ($b === 0.0) {
        throw new InvalidArgumentException("No se puede dividir por cero");
    }
    return $a / $b;
}

try {
    echo dividir(10, 2);   // 5
    echo dividir(10, 0);   // lanza excepción
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    echo "Esto se ejecuta siempre";
}
```

### Excepciones propias

```php
class SaldoInsuficienteException extends Exception {}

throw new SaldoInsuficienteException("No tienes saldo");
```

Jerarquía útil de PHP: `Throwable` → `Error` (errores del motor) y `Exception` (errores de tu app),
con subclases como `InvalidArgumentException`, `RuntimeException`, `LogicException`.

> 🧠 En CodeIgniter/Perfex verás `show_error()` y `log_message()`. Por debajo, los frameworks
> modernos convierten errores en excepciones para manejarlas de forma centralizada.

---

## 2. Composer: el gestor de dependencias

Composer descarga librerías y genera un **autoload** (carga automática de clases). Así no haces
`require` de cada archivo a mano.

### Iniciar un proyecto

```bash
composer init                     # crea composer.json interactivamente
composer require nesbot/carbon    # instala Carbon (fechas) y lo añade a composer.json
composer require --dev phpunit/phpunit   # dependencia solo de desarrollo
```

Se crean:
- `composer.json` → lista de dependencias (lo editas/commiteas).
- `composer.lock` → versiones exactas instaladas (lo commiteas).
- `vendor/` → las librerías descargadas (NO se commitea, va en `.gitignore`).
- `vendor/autoload.php` → el cargador mágico.

### Usar una librería

```php
require __DIR__ . '/vendor/autoload.php';   // una sola línea carga TODO

use Carbon\Carbon;

echo Carbon::now()->addDays(7)->format('d/m/Y');
```

> 📌 En enfoca-nexo hay **dos** `composer.json`: uno en la raíz y otro en `application/`. Por eso el
> README del proyecto te dice ejecutar `composer update` en ambos sitios. Cada uno tiene su `vendor/`.

---

## 3. Autoload PSR-4 (tu propio código)

Puedes hacer que Composer cargue **tus** clases por namespace. Es el estándar **PSR-4**: el
namespace mapea a una carpeta.

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
```

Con eso, la clase `App\Models\Usuario` se busca en `src/Models/Usuario.php`. Tras editar el
`composer.json` ejecutas:

```bash
composer dump-autoload
```

Y ya puedes:

```php
require 'vendor/autoload.php';
use App\Models\Usuario;
$u = new Usuario();
```

> 💡 Este es exactamente el modelo de **CodeIgniter 4** (namespaces + PSR-4). CI3 NO lo usa para sus
> clases core, pero SÍ puedes usar Composer para librerías de terceros dentro de CI3 (como hace
> Perfex). Verás esto en el Módulo 07.

---

## 4. Variables de entorno con `.env` (phpdotenv)

Las claves secretas (contraseñas de BBDD, API keys de Stripe…) **nunca** van en el código. Van en
un archivo `.env` que NO se commitea. Perfex usa `vlucas/phpdotenv` para esto.

```bash
composer require vlucas/phpdotenv
```

```ini
# .env  (en .gitignore)
DB_HOST=localhost
DB_USER=root
DB_PASS=secreto
STRIPE_KEY=sk_test_xxx
```

```php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo $_ENV['DB_HOST'];   // localhost
```

> ⚠️ **Regla de seguridad nº1:** credenciales fuera del repositorio. Siempre `.env` en `.gitignore`
> y un `.env.example` (sin valores reales) para documentar qué variables hacen falta.

---

## 🏋️ Ejercicios

1. **Validador con excepciones** — función `validarEdad(int $edad)` que lance excepciones distintas
   si la edad es negativa o mayor de 130. Captúralas por separado.
2. **Mini-proyecto Composer** — en [`ejercicios/proyecto-composer/`](ejercicios/proyecto-composer/)
   tienes un `composer.json` con autoload PSR-4. Ejecuta `composer dump-autoload` y luego
   `php index.php`. Estudia cómo se cargan las clases de `src/` sin un solo `require`.

---

## 📚 Resumen

- Lanza **excepciones** (`throw`) en vez de devolver `false`; captúralas con `try/catch/finally`.
- **Composer** = dependencias + autoload. `composer require`, `composer install`, `dump-autoload`.
- **PSR-4** mapea namespace → carpeta. Es la base de CI4 y del PHP moderno.
- Secretos en **`.env`** (phpdotenv), nunca en el código.

➡️ Siguiente: **[05 · PHP y la web](../05-php-web/)**

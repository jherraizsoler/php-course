# 03 · Programación Orientada a Objetos (POO) 🟡

**Este módulo es la frontera.** CodeIgniter, Composer y todas las librerías profesionales se basan
en POO. Sin esto no entiendes un controlador de Perfex. Tómate tu tiempo aquí.

```bash
php 03-poo/ejemplos/01-clases.php
```

---

## 1. Clases y objetos

Una **clase** es una plantilla; un **objeto** es una instancia concreta de esa plantilla.

```php
class Coche
{
    // Propiedades (estado) — tipadas y con visibilidad
    public string $marca;
    public string $color;
    private int $velocidad = 0;   // privada: solo accesible dentro de la clase

    // Constructor: se ejecuta al crear el objeto
    public function __construct(string $marca, string $color)
    {
        $this->marca = $marca;
        $this->color = $color;
    }

    // Métodos (comportamiento)
    public function acelerar(int $kmh): void
    {
        $this->velocidad += $kmh;
    }

    public function getVelocidad(): int
    {
        return $this->velocidad;
    }
}

$miCoche = new Coche("Seat", "rojo");
$miCoche->acelerar(50);
echo $miCoche->getVelocidad(); // 50
```

- `$this` → el objeto actual.
- `->` → acceder a propiedades/métodos de un objeto.
- **Visibilidad:** `public` (todos), `protected` (la clase y sus hijas), `private` (solo la clase).

### Constructor con promoción de propiedades (PHP 8)

Atajo que escribe el constructor y declara las propiedades a la vez:

```php
class Coche
{
    public function __construct(
        public string $marca,
        public string $color,
        private int $velocidad = 0,
    ) {}
}
```

---

## 2. Herencia

Una clase puede **heredar** de otra con `extends` y reutilizar/ampliar su comportamiento.

```php
class Vehiculo
{
    public function __construct(protected string $marca) {}

    public function describir(): string
    {
        return "Vehículo de marca {$this->marca}";
    }
}

class Moto extends Vehiculo
{
    public function describir(): string
    {
        return parent::describir() . " (moto)"; // parent:: llama al padre
    }
}

echo (new Moto("Honda"))->describir(); // Vehículo de marca Honda (moto)
```

> 🧠 En CodeIgniter 3, **todos tus controladores** extienden `CI_Controller` y **tus modelos**
> extienden `CI_Model`. En Perfex extienden versiones propias (`App_Controller`, `App_Model`).
> Entender `extends` y `parent::` es entender CodeIgniter.

---

## 3. Clases abstractas e interfaces

```php
// Interfaz: un "contrato". Dice QUÉ métodos hay, no CÓMO.
interface Notificable
{
    public function enviar(string $mensaje): bool;
}

// Clase abstracta: no se instancia; sirve de base con lógica parcial.
abstract class Canal implements Notificable
{
    abstract public function enviar(string $mensaje): bool; // obliga a implementarlo

    public function log(string $mensaje): void
    {
        echo "[LOG] {$mensaje}\n";
    }
}

class CanalEmail extends Canal
{
    public function enviar(string $mensaje): bool
    {
        $this->log("Email enviado: {$mensaje}");
        return true;
    }
}
```

> 💡 Las interfaces son la base de la **inyección de dependencias** y el principio *D* de SOLID
> (Módulo 06). Programar contra interfaces te permite cambiar PHPMailer por otro sistema de correo
> sin tocar el resto del código.

---

## 4. Propiedades y métodos estáticos

Pertenecen a la **clase**, no a una instancia. Se accede con `::`.

```php
class Contador
{
    public static int $total = 0;

    public static function incrementar(): void
    {
        self::$total++;
    }
}

Contador::incrementar();
Contador::incrementar();
echo Contador::$total; // 2
```

> En CodeIgniter verás mucho `self::` y helpers globales. En Laravel/Perfex verás *facades* estáticas.

---

## 5. Traits (reutilización horizontal)

Un **trait** es un bloque de métodos que puedes "pegar" en varias clases sin herencia.

```php
trait Timestampable
{
    public function ahora(): string
    {
        return date('Y-m-d H:i:s');
    }
}

class Factura
{
    use Timestampable;
}

echo (new Factura())->ahora();
```

PHP no tiene herencia múltiple, pero los traits resuelven el "quiero compartir este método en
clases que no están relacionadas".

---

## 6. Namespaces

Los **namespaces** evitan choques de nombres y organizan el código. Son la base del autoload de
Composer (Módulo 04).

```php
// archivo: src/Models/Usuario.php
namespace App\Models;

class Usuario { /* ... */ }
```

```php
// otro archivo que lo usa:
use App\Models\Usuario;

$u = new Usuario();
```

> ⚠️ CodeIgniter **3** apenas usa namespaces (carga clases por convención de carpetas). CodeIgniter
> **4** sí los usa de lleno (`App\Controllers\...`). Es una de las grandes diferencias entre ambos
> (Módulo 09).

---

## 🏋️ Ejercicios

1. **Cuenta bancaria** — clase `CuentaBancaria` con saldo privado, métodos `ingresar()`,
   `retirar()` (que no permita saldo negativo) y `getSaldo()`.
2. **Jerarquía de empleados** — clase abstracta `Empleado` con método abstracto `calcularSueldo()`;
   subclases `Asalariado` (sueldo fijo) y `PorHoras` (horas × tarifa).
3. **Interfaz de pago** — interfaz `PasarelaPago` con `cobrar(float $importe): bool`;
   implementaciones `Stripe` y `PayPal` (simuladas). Esto es exactamente cómo Perfex abstrae sus
   pasarelas con Omnipay.

Soluciones en [`ejercicios/soluciones/`](ejercicios/soluciones/).

---

## 📚 Resumen

- Clase = plantilla, objeto = instancia. `$this`, `->`, `::`.
- `extends` + `parent::` = herencia (la base de CI3).
- `interface` = contrato; `abstract` = base parcial.
- `trait` = reutilización horizontal; `namespace` = organización (clave en CI4 y Composer).

➡️ Siguiente: **[04 · PHP avanzado y Composer](../04-php-avanzado/)**

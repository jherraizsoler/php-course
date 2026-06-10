# 06 · Buenas prácticas 🟠

Saber escribir código que funciona es el 50%. El otro 50% es escribirlo **limpio, mantenible y
testeable**. Esto es lo que separa a un junior de un profesional.

```bash
php 06-buenas-practicas/ejemplos/01-refactor.php
```

---

## 1. Estándares PSR

**PSR** (PHP Standards Recommendations) son convenciones que sigue todo el ecosistema (Composer,
CodeIgniter 4, Laravel, Symfony…). Las que importan:

| PSR | Qué define |
|---|---|
| **PSR-1 / PSR-12** | Estilo de código: indentación, llaves, nombres |
| **PSR-4** | Autoload por namespace (ya lo viste en el Módulo 04) |
| **PSR-3** | Interfaz de logging (`LoggerInterface`) |
| **PSR-7 / PSR-15** | HTTP messages y middleware |

**Convenciones de nombres** (PSR-1/12):
- Clases: `PascalCase` → `class FacturaService`
- Métodos y variables: `camelCase` → `function calcularTotal()`
- Constantes: `MAYUSCULAS_CON_GUION_BAJO`
- Indentación: **4 espacios**, no tabs.

> 🛠️ Herramientas que lo automatizan: **PHP-CS-Fixer** y **PHP_CodeSniffer** (formatean tu código
> al estándar). En proyectos serios corren en el CI.

---

## 2. Principios SOLID

Cinco principios para diseñar clases que no se conviertan en un infierno de mantener:

- **S** — *Single Responsibility*: cada clase hace **una** cosa. Un `FacturaPdf` genera PDFs, no
  también envía emails ni calcula impuestos.
- **O** — *Open/Closed*: abierto a extensión, cerrado a modificación. Añades una nueva pasarela de
  pago creando una clase, sin tocar el checkout (lo viste en el Módulo 03).
- **L** — *Liskov*: una subclase debe poder sustituir a su padre sin romper nada.
- **I** — *Interface Segregation*: interfaces pequeñas y específicas mejor que una gigante.
- **D** — *Dependency Inversion*: depende de **interfaces**, no de clases concretas. Inyecta las
  dependencias en el constructor.

### Inyección de dependencias (lo más práctico de SOLID)

```php
// ❌ MAL: la clase crea sus dependencias (acoplada, no testeable)
class Pedido
{
    public function confirmar(): void
    {
        $mailer = new PHPMailer();   // acoplado a PHPMailer para siempre
        $mailer->send(/* ... */);
    }
}

// ✅ BIEN: las dependencias se inyectan (desacoplado, testeable)
class Pedido
{
    public function __construct(private Notificable $notificador) {}

    public function confirmar(): void
    {
        $this->notificador->enviar("Pedido confirmado");  // no sabe ni le importa cuál es
    }
}
```

Ahora puedes pasar un email real en producción y un *mock* falso en los tests.

---

## 3. Código limpio (reglas prácticas)

- **Nombres que se leen solos:** `calcularTotalConIva()` mejor que `calc()`.
- **Funciones cortas:** si una función no cabe en la pantalla, divídela.
- **Evita la anidación profunda:** usa *early return* (`if (!$valido) return;`) en vez de pirámides
  de `if`.
- **No te repitas (DRY):** si copias y pegas, extrae una función.
- **Comenta el *por qué*, no el *qué*:** el código dice qué hace; el comentario, por qué.
- **Tipa todo:** parámetros, retornos y propiedades. `declare(strict_types=1)` siempre.

El ejemplo [`ejemplos/01-refactor.php`](ejemplos/01-refactor.php) muestra el mismo código "antes y
después" de aplicar estas reglas.

---

## 4. Testing con PHPUnit

Los tests automáticos te dan la confianza de cambiar código sin romper nada.

```bash
composer require --dev phpunit/phpunit
```

```php
use PHPUnit\Framework\TestCase;

final class CalculadoraTest extends TestCase
{
    public function test_suma_dos_numeros(): void
    {
        $calc = new Calculadora();
        $this->assertSame(5, $calc->sumar(2, 3));
    }
}
```

```bash
./vendor/bin/phpunit tests
```

> 💡 Perfex usa `fakerphp/faker` (datos falsos para tests) y `symfony/var-dumper`. El testing en
> CI3 es más manual que en CI4, pero el principio es el mismo: aislar y verificar.

En [`ejemplos/test/`](ejemplos/test/) tienes una `Calculadora` con su test listo para correr.

---

## 5. Git y flujo de trabajo

Tal como se trabaja en enfoca-nexo (lo dice su README):

- Ramas por funcionalidad: `git checkout -b nueva-feature`.
- Commits pequeños y descriptivos.
- Pull Request revisada por **otra persona** antes de mergear a `dev`/`main`.
- `main` = producción, no se toca directamente.

---

## 📚 Resumen

- Sigue **PSR-12** (estilo) y **PSR-4** (autoload).
- **SOLID**, sobre todo: una clase = una responsabilidad, e **inyecta dependencias**.
- Código limpio: nombres claros, funciones cortas, early return, DRY, tipado estricto.
- **Tests** con PHPUnit para no tener miedo a cambiar el código.

➡️ Siguiente: **[07 · CodeIgniter 3](../07-codeigniter3/)** — ¡por fin el framework!

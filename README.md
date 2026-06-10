# 🐘 PHP Course — De 0 a 100 con CodeIgniter y buenas prácticas

Repositorio personal de aprendizaje de **PHP** y **CodeIgniter 3** (con introducción a CI4),
pensado para estudiar, trastear y prepararte para trabajar en proyectos reales tipo
**Perfex CRM / enfoca-nexo** (CodeIgniter 3.1.11 + HMVC + hooks).

> Curso orientado a tu stack real. El proyecto profesional `enfoca-nexo` usa:
> CodeIgniter **3.1.11**, PHP **^8.0**, MySQL, MAMP/Docker, y librerías como
> PHPMailer, TCPDF, Carbon, Guzzle, Stripe/PayPal, Faker, etc.
> Este curso te lleva desde cero hasta entender y mantener un proyecto así.

---

## 🎯 Cómo usar este curso

1. Cada carpeta `NN-tema/` es un **módulo** con su propio `README.md` (la lección).
2. Dentro encontrarás:
   - `README.md` → teoría explicada con ejemplos.
   - `ejemplos/` → código PHP **ejecutable** (lo corres en MAMP o por CLI).
   - `ejercicios/` → retos para practicar, con `soluciones/`.
3. Sigue el orden numérico. No saltes a CodeIgniter sin dominar POO y Composer.
4. **Trastea**: rompe el código, cámbialo, vuelve a ejecutarlo. Así se aprende.

### Ejecutar un ejemplo

```bash
# Por línea de comandos (rápido para fundamentos):
php 01-php-fundamentos/ejemplos/01-hola-mundo.php

# En el navegador con MAMP (necesario para web/sesiones/formularios):
# 1. Copia el repo dentro de C:\MAMP\htdocs\  (o crea un enlace)
# 2. Arranca MAMP
# 3. Abre http://localhost/php-course/05-php-web/ejemplos/...
```

> 💡 Tienes varias versiones de PHP en MAMP (`C:\MAMP\bin\php\`), hasta **php8.3.1**.
> Para este curso usa **PHP 8.2 u 8.3**, que es lo que corre tu proyecto real.

---

## 🗺️ Roadmap (de 0 a 100)

| # | Módulo | Qué aprendes | Nivel |
|---|--------|--------------|-------|
| [00](00-entorno/) | **Entorno de trabajo** | MAMP, PHP CLI, Composer, Git, VS Code | 🟢 Base |
| [01](01-php-fundamentos/) | **Fundamentos PHP** | Sintaxis, variables, tipos, operadores, control de flujo | 🟢 Base |
| [02](02-funciones-arrays-strings/) | **Funciones, arrays y strings** | Funciones, arrays asociativos, manipulación de texto | 🟢 Base |
| [03](03-poo/) | **Programación Orientada a Objetos** | Clases, herencia, interfaces, traits, namespaces | 🟡 Medio |
| [04](04-php-avanzado/) | **PHP avanzado** | Excepciones, closures, Composer, autoload PSR-4 | 🟡 Medio |
| [05](05-php-web/) | **PHP y la web** | Formularios, GET/POST, sesiones, cookies, PDO + MySQL, seguridad | 🟡 Medio |
| [06](06-buenas-practicas/) | **Buenas prácticas** | PSR, SOLID, principios limpios, PHPUnit, `.env` | 🟠 Pro |
| [07](07-codeigniter3/) | **CodeIgniter 3** | MVC, routing, models, Query Builder, validación, HMVC, hooks | 🟠 Pro |
| [08](08-librerias-clave/) | **Librerías clave** | PHPMailer, TCPDF, Carbon, Guzzle, Collections, Faker… | 🟠 Pro |
| [09](09-codeigniter4-intro/) | **Intro a CodeIgniter 4** | Comparativa CI3 vs CI4, namespaces, migración | 🔵 Extra |
| [proyectos](proyectos/) | **Proyectos prácticos** | CRUD en PHP puro + CRUD en CodeIgniter 3 | 🏗️ Práctica |

---

## 🧰 Stack y tecnologías que cubre el curso

**Lenguaje base:** PHP 8.2 / 8.3
**Framework principal:** CodeIgniter 3.1.11 (MVC + HMVC con Modular Extensions)
**Base de datos:** MySQL (vía PDO en puro y Query Builder en CI)
**Gestor de dependencias:** Composer (autoload PSR-4)
**Entorno local:** MAMP (Apache + MySQL + PHP)
**Control de versiones:** Git

**Librerías profesionales** (las que verás en proyectos reales como Perfex):
PHPMailer · TCPDF · nesbot/carbon · guzzlehttp/guzzle · illuminate/collections ·
vlucas/phpdotenv · fakerphp/faker · stripe/stripe-php · pragmarx/google2fa.

---

## ✅ Checklist de progreso

- [ ] 00 · Entorno montado (MAMP arranca, `php -v` funciona, Composer instalado)
- [ ] 01 · Fundamentos de PHP
- [ ] 02 · Funciones, arrays y strings
- [ ] 03 · POO
- [ ] 04 · PHP avanzado + Composer
- [ ] 05 · PHP y web (PDO, sesiones, seguridad)
- [ ] 06 · Buenas prácticas
- [ ] 07 · CodeIgniter 3
- [ ] 08 · Librerías clave
- [ ] 09 · Intro CodeIgniter 4
- [ ] 🏗️ Proyecto CRUD PHP puro
- [ ] 🏗️ Proyecto CRUD CodeIgniter 3

---

> Hecho como repositorio de estudio personal. Edita, rompe y experimenta libremente. 🚀

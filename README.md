# 🐘 PHP Course — De 0 a 100 con CodeIgniter y buenas prácticas

Repositorio personal de aprendizaje de **PHP** y **CodeIgniter 3** (con introducción a CI4),
pensado para estudiar, trastear y prepararte para trabajar en **proyectos reales** tipo CRM o
panel de gestión (CodeIgniter 3.1.11 + HMVC + hooks).

> Curso orientado a un stack profesional real:
> CodeIgniter **3.1.11**, PHP **8.x**, MySQL, MAMP/Docker, y librerías como
> PHPMailer, TCPDF, Carbon, Guzzle, Stripe/PayPal, Faker, etc.
> Este curso te lleva desde cero hasta entender y mantener un proyecto así.

---

## 🚀 Inicio rápido — elige tu opción

### 🐳 OPCIÓN 1: Docker (recomendado — 0 configuración, 1 comando)

Si tienes **Docker Desktop** instalado, este es el flujo más rápido:

```bash
git clone https://github.com/jherraizsoler/php-course.git
cd php-course
docker compose up --build
# Abre http://localhost:8080/
```

✅ **Todo automático:** BBDD creadas, Composer resuelto, secretos generados. **Sin pasos manuales.**

### 💻 OPCIÓN 2: Local (MAMP/XAMPP Windows, macOS, Linux)

Si prefieres trabajar sin Docker, copia el repo a tu carpeta `htdocs` y ejecuta:

```powershell
# Windows (PowerShell):
.\setup.ps1

# Si tu MySQL usa puerto 3306 (XAMPP estándar):
$env:DB_PORT='3306'; .\setup.ps1
```

```bash
# Linux / macOS:
bash setup.sh           # o:  DB_PORT=3306 bash setup.sh
```

El script instala dependencias Composer, crea BBDD e importa datos. **Requisitos previos:**
- Apache arrancado
- MySQL arrancado (MAMP, XAMPP o similar)

---

## 📖 Detalles de cada opción

### Docker — Flujo automático completo

Mientras Docker arranca, hace TODO automáticamente:

1. **Construye la imagen PHP** (Dockerfile) con:
   - PHP 8.3 + Apache + todas las extensiones necesarias (pdo_mysql, gd, zip, etc.)
   - Composer instalado
   - Dependencias Composer ya resueltas (`vendor/` preinstalado)

2. **Inicia MySQL 8** y ejecuta las "migraciones" (crea BBDD + tablas automáticamente):
   - `curso` (tabla usuarios)
   - `curso_tareas` (tabla tareas)
   - `curso_3d` (tabla modelos para visor 3D)

3. **Genera secretos** (`.env` del login-seguro con hash + 2FA automático)

4. **Expone servicios** en tu máquina:
   - **Web:** http://localhost:8080/ (PHP 8.3 + Apache)
   - **MySQL:** localhost:3307 (acceso externo opcional, dentro es `db:3306`)

**Comandos útiles:**

```bash
docker compose down              # Parar sin borrar (datos persisten)
docker compose down -v           # Parar Y borrar todo (BBDD incluida)
docker compose logs -f web       # Ver logs en tiempo real
docker compose exec web bash     # Abrir shell en el contenedor
docker compose down -v && docker compose up --build   # Resetear todo
```

> 💡 **Ventaja:** Esta misma imagen sirve para desplegar en **Railway / Render / Fly.io** sin cambios.

---

### Local (MAMP/XAMPP) — Qué hace setup.ps1 / setup.sh

El script de setup instala automáticamente:

1. **Dependencias Composer** — `composer install` en cada sub-proyecto que lo necesite:
   - `proyectos/crud-codeigniter3/app/`
   - `proyectos/login-seguro/`

2. **Base de datos** — crea e importa automáticamente:
   - `curso` (tabla usuarios para ejemplos 05-php-web)
   - `curso_tareas` (tabla tareas para CRUD puro)
   - `curso_3d` (tabla modelos para visor 3D)

3. **Secretos** — genera el `.env` de la demo de seguridad:
   - Hash bcrypt para usuario `demo` 
   - Secreto TOTP (2FA) automático

> **Requisitos previos:** Apache + MySQL arrancados en MAMP/XAMPP.
> **Variables opcionales:** `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS` (por defecto `127.0.0.1:8889`, `root`/`root`).

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
# 3. Abre en el navegador la ruta 05-php-web/ejemplos/... (URL segun tu MAMP)
```

> 💡 Tienes varias versiones de PHP en MAMP (`C:\MAMP\bin\php\`), hasta **php8.3.1**.
> Para este curso usa **PHP 8.2 u 8.3**, que es lo que corre tu proyecto real.

---

## 🗺️ Roadmap (de 0 a 100)

> Haz clic en el nombre de cualquier módulo para abrir su lección (`README.md`).

| # | Módulo | Qué aprendes | Nivel |
|---|--------|--------------|-------|
| 00 | [**Entorno de trabajo**](00-entorno/README.md) | MAMP, PHP CLI, Composer, Git, VS Code | 🟢 Base |
| 01 | [**Fundamentos PHP**](01-php-fundamentos/README.md) | Sintaxis, variables, tipos, operadores, control de flujo | 🟢 Base |
| 02 | [**Funciones, arrays y strings**](02-funciones-arrays-strings/README.md) | Funciones, arrays asociativos, manipulación de texto | 🟢 Base |
| 03 | [**Programación Orientada a Objetos**](03-poo/README.md) | Clases, herencia, interfaces, traits, namespaces | 🟡 Medio |
| 04 | [**PHP avanzado**](04-php-avanzado/README.md) | Excepciones, closures, Composer, autoload PSR-4 | 🟡 Medio |
| 05 | [**PHP y la web**](05-php-web/README.md) | Formularios, GET/POST, sesiones, cookies, PDO + MySQL, seguridad | 🟡 Medio |
| 06 | [**Buenas prácticas**](06-buenas-practicas/README.md) | PSR, SOLID, principios limpios, PHPUnit, `.env` | 🟠 Pro |
| 07 | [**CodeIgniter 3**](07-codeigniter3/README.md) | MVC, routing, models, Query Builder, validación, HMVC, hooks | 🟠 Pro |
| 08 | [**Librerías clave**](08-librerias-clave/README.md) | PHPMailer, TCPDF, Carbon, Guzzle, Collections, Faker… | 🟠 Pro |
| 09 | [**Intro a CodeIgniter 4**](09-codeigniter4-intro/README.md) | Comparativa CI3 vs CI4, namespaces, migración | 🔵 Extra |
| 🏗️ | [**Proyectos prácticos**](proyectos/README.md) | CRUD en PHP puro + CRUD en CodeIgniter 3 | 🏗️ Práctica |

---

## 🧰 Stack y tecnologías que cubre el curso

**Lenguaje base:** PHP 8.2 / 8.3
**Framework principal:** CodeIgniter 3.1.11 (MVC + HMVC con Modular Extensions)
**Base de datos:** MySQL (vía PDO en puro y Query Builder en CI)
**Gestor de dependencias:** Composer (autoload PSR-4)
**Entorno local:** MAMP (Apache + MySQL + PHP)
**Control de versiones:** Git

**Librerías profesionales** (las que verás en proyectos reales como un CRM profesional):
PHPMailer · TCPDF · nesbot/carbon · guzzlehttp/guzzle · illuminate/collections ·
vlucas/phpdotenv · fakerphp/faker · stripe/stripe-php · pragmarx/google2fa.

---

## ✅ Checklist de progreso

- [ ] [00 · Entorno](00-entorno/README.md) montado (MAMP arranca, `php -v` funciona, Composer instalado)
- [ ] [01 · Fundamentos de PHP](01-php-fundamentos/README.md)
- [ ] [02 · Funciones, arrays y strings](02-funciones-arrays-strings/README.md)
- [ ] [03 · POO](03-poo/README.md)
- [ ] [04 · PHP avanzado + Composer](04-php-avanzado/README.md)
- [ ] [05 · PHP y web (PDO, sesiones, seguridad)](05-php-web/README.md)
- [ ] [06 · Buenas prácticas](06-buenas-practicas/README.md)
- [ ] [07 · CodeIgniter 3](07-codeigniter3/README.md)
- [ ] [08 · Librerías clave](08-librerias-clave/README.md)
- [ ] [09 · Intro CodeIgniter 4](09-codeigniter4-intro/README.md)
- [ ] [🏗️ Proyectos prácticos](proyectos/README.md) (CRUD PHP puro + CRUD CodeIgniter 3)

---

## 📜 Licencia y uso

**Proyecto educativo · Todos los derechos reservados © Jorge Herraiz Soler ([@jherraizsoler](https://github.com/jherraizsoler)).**

Este repositorio se publica con fines **exclusivamente educativos y de demostración personal**.

- ✅ Puedes **consultarlo y estudiarlo** para aprender.
- ❌ **No** está permitido **usarlo con fines comerciales o remunerados**, ni **copiar, editar,
  redistribuir o publicar** este repositorio (ni obras derivadas) **sin el permiso previo y por
  escrito del autor**.

Para solicitar permisos, contacta con el autor en GitHub: [@jherraizsoler](https://github.com/jherraizsoler).
Términos completos en el archivo [LICENSE](LICENSE).

---

> Repositorio de estudio personal de **Jorge Herraiz Soler**. 🚀

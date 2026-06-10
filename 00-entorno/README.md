# 00 · Entorno de trabajo 🛠️

Antes de programar necesitas un entorno que ejecute PHP. Tú ya tienes **MAMP** instalado en
`C:\MAMP`, que incluye **Apache** (servidor web), **MySQL** (base de datos) y **PHP** (el lenguaje).

---

## 1. ¿Qué es MAMP y por qué lo usamos?

PHP es un lenguaje **de servidor**: el navegador no entiende PHP, lo entiende el servidor, que
ejecuta el código y devuelve HTML. MAMP te monta ese servidor en tu PC:

```
Navegador  ──HTTP──►  Apache  ──►  PHP  ──►  MySQL
   ▲                                            │
   └──────────────  HTML resultante  ◄──────────┘
```

- **M**AMP = **M**y **A**pache, **M**ySQL, **P**HP.
- El directorio raíz web es `C:\MAMP\htdocs\`. Todo lo que pongas ahí es accesible desde
  `http://localhost/`.
- El curso puedes servirlo desde `C:\MAMP\htdocs\php-course`.

### Poner el curso en MAMP

Para los módulos web (05 en adelante) necesitas servirlos por Apache. Dos opciones:

```powershell
# Opción A — copiar el curso a htdocs (simple):
Copy-Item -Recurse "c:\reposPersonales\php-course" "C:\MAMP\htdocs\php-course"

# Opción B — enlace simbólico (recomendado: editas en tu repo, se ve en MAMP):
New-Item -ItemType SymbolicLink -Path "C:\MAMP\htdocs\php-course" -Target "c:\reposPersonales\php-course"
```

Luego arranca MAMP y abre la raíz del proyecto en el navegador. La URL depende de tu puerto de Apache y de dónde tengas el repo dentro de `htdocs` (p. ej. `http://localhost:8888/repoPersonales/php-course/`).

> ⚠️ Para los módulos 01–04 (fundamentos) **no necesitas MAMP**: se ejecutan por línea de
> comandos con `php archivo.php`. Es más rápido para practicar.

---

## 2. PHP por línea de comandos (CLI)

MAMP trae varias versiones de PHP en `C:\MAMP\bin\php\`. Tú tienes desde `php5.5` hasta
**`php8.3.1`**. Para este curso usaremos **PHP 8.2 / 8.3**.

```powershell
# Ver la versión de PHP que tienes en el PATH global:
php -v

# Usar una versión concreta de MAMP directamente:
& "C:\MAMP\bin\php\php8.3.1\php.exe" -v

# Ejecutar un script:
php 01-php-fundamentos/ejemplos/01-hola-mundo.php
```

### Truco: alias para la versión de MAMP

Si `php -v` te da una versión distinta a la del proyecto, puedes crear un alias en tu perfil de
PowerShell para usar siempre la misma:

```powershell
# Añade esto a $PROFILE
function php83 { & "C:\MAMP\bin\php\php8.3.1\php.exe" @args }
```

---

## 3. Composer (gestor de dependencias)

**Composer** es a PHP lo que `npm` a Node: descarga librerías y genera el *autoload*. Tu proyecto
real lo usa intensamente (mira `application/composer.json` de un proyecto profesional: TCPDF, PHPMailer,
Carbon…). Lo veremos a fondo en el [Módulo 04](../04-php-avanzado/).

```powershell
# Comprobar si lo tienes:
composer --version

# Si no, descárgalo de https://getcomposer.org/download/
# En un proyecto nuevo:
composer init          # crea composer.json
composer require monolog/monolog   # instala una librería
composer install       # instala lo que diga composer.json
composer dump-autoload # regenera el autoload
```

---

## 4. Git (control de versiones)

Este repo ya es un repositorio Git. Comandos que usarás a diario (igual que en un proyecto profesional):

```bash
git status                 # ver cambios
git add .                  # preparar cambios
git commit -m "mensaje"    # guardar una versión
git checkout -b mi-rama    # crear y cambiar de rama
git log --oneline          # historial
```

---

## 5. VS Code (editor recomendado)

Extensiones útiles para PHP:

- **PHP Intelephense** — autocompletado y análisis.
- **PHP Debug** (Xdebug) — depuración paso a paso.
- **CodeIgniter snippets** — atajos para CI.
- **DotENV** — resaltado de archivos `.env`.

---

## ✅ Comprueba tu entorno

Ejecuta el script de diagnóstico incluido:

```powershell
php 00-entorno/ejemplos/diagnostico.php
```

Debe decirte tu versión de PHP, si Composer está disponible y qué extensiones tienes activas
(necesitarás `pdo_mysql`, `mbstring`, `openssl`, `curl`… que son las que usa un proyecto real).

---

## 📚 Resumen

| Herramienta | Para qué | Comando clave |
|---|---|---|
| MAMP | Servidor local (Apache+MySQL+PHP) | arrancar la app |
| PHP CLI | Ejecutar scripts | `php archivo.php` |
| Composer | Librerías + autoload | `composer require` |
| Git | Versiones de código | `git commit` |

---

🏠 [**Índice del curso**](../README.md) · ➡️ Siguiente: [**01 · Fundamentos de PHP**](../01-php-fundamentos/README.md)

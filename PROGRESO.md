# 📌 PROGRESO — estado del proyecto (para continuar)

> Documento de continuación. Resume **qué está hecho**, **datos clave del entorno** y **qué falta**.
> Autor: **Jorge Herraiz Soler** ([@jherraizsoler](https://github.com/jherraizsoler)).

---

## 🧩 Entorno (datos clave)

| Dato | Valor |
|---|---|
| Repo local | `c:\MAMP\htdocs\repoPersonales\php-course` |
| URL local (MAMP) | `http://localhost:8888/repoPersonales/php-course/` |
| Apache (MAMP) | puerto **8888** |
| MySQL (MAMP) | `127.0.0.1:8889`, usuario `root` / `root` |
| PHP runtime | `C:\MAMP\bin\php\php8.3.1\php.exe` (8.3.1) |
| Composer | `php8.3.1 /c/composer/composer.phar …` (el `composer` suelto usa 8.2.14) |
| GitHub | `jherraizsoler/php-course` |
| Extensión añadida | `fileinfo` activada en el `php.ini` de 8.3.1 (la pedía PhpSpreadsheet) |
| Login demo | usuario `jorge` · contraseña `demo1234` · TOTP secret `EKFD4N52UIYNLG2J` (en `.env` local) |

> ⚠️ La 8.3.1 no tenía `php.ini` (se copió de 8.2.14). Si MAMP lo regenera, recordar reactivar extensiones.

---

## ✅ Hecho y verificado

### Portal del curso (raíz)
- `index.php` — portada: **intro splash** (logo PHP + avatar Jorge + "Jorge Herraiz Soler" / "Desarrollador FullStack IA" + botones LinkedIn/Portfolio), una vez por sesión.
- **Badge de autor** en la cabecera (logo + nombre + rol → enlaza al portfolio).
- **Tema** claro/oscuro con transición suave (sin *flashbang*) y sin FOUC (script en `<head>`).
- Tarjetas de módulo **clicables enteras**, buscador, progreso, **CTA "Ir a Proyectos"**, footer social.
- `modulo.php` renderiza el `README.md` de cada módulo y **reescribe enlaces** relativos (`../x/README.md` → `modulo.php?m=x`, etc.).
- Colores accesibles (tokens `--link`, `--code-ink`, `--accent-ink`); cian eléctrico corregido en modo claro.
- READMEs de módulos `00`–`09` + `proyectos` con barra de navegación (anterior/índice/siguiente).

### Proyectos (`proyectos/`)
- **`crud-php-puro/`** — CRUD PDO/MVC a mano (BBDD `curso_tareas`). `config.php` lee de entorno (fallback MAMP).
- **`crud-codeigniter3/app/`** — **CodeIgniter 3.1.13** funcional (BBDD `curso`, tabla `usuarios`):
  - CRUD con Query Builder + validación + flashdata + nav al curso.
  - **Exportación** `Export.php`: **CSV** (nativo), **Excel** (PhpSpreadsheet), **PDF** (TCPDF, clase `Informe_PDF` con cabecera/pie corporativos, **paleta tranquila**, logo con esquinas redondeadas).
  - **Dashboard** `Dashboard.php`: **Chart.js** (cliente) + gráfica **GD** server-side (`/dashboard/png`).
  - `database.php` y `base_url` calculados desde entorno / `HTTP_HOST` (portable a Docker).
  - `index.php` (landing) = guía + botón "▶ Abrir la app en vivo".
- **`login-seguro/`** — demo de seguridad: **CSRF + 2FA (google2fa, QR con `chillerlan/php-qrcode` SVG server-side) + `.env` (phpdotenv) + rate limiting + `session_regenerate_id`**. QR centrado. Verificado login→2FA→panel.

### Puesta en marcha / despliegue
- **`setup.ps1`** / **`setup.sh`** — un comando: `composer install` + BBDD + `.env`.
- **`tools/db-setup.php`** (crea `curso` + `curso_tareas`) y **`tools/env-setup.php`** (genera `.env` de login-seguro).
- **Docker** (✅ probado funcionando): `Dockerfile` (PHP 8.3+Apache, ext: `pdo_mysql mysqli gd zip intl mbstring`), `docker-compose.yml` (web `:8080` + MySQL que crea las BBDD solas), `.dockerignore`, `docker/php-course.ini` (temp/sesiones → `/tmp`, evita el `mkdir(): Invalid path` de TCPDF).
  - Comando: `docker compose up --build` → todo en `http://localhost:8080/`.
  - Notas Docker: CI3 necesita **`mysqli`** (no solo pdo_mysql); TCPDF/sesiones necesitan `sys_temp_dir`/`session.save_path`.

### Licencia
- `LICENSE` (uso educativo, todos los derechos reservados, ES/EN) + sección en `README.md`. No uso comercial/edición sin permiso del autor.

---

## ⏳ PENDIENTE — Parte 4: Visor 3D / volumétrico

**Objetivo:** módulo para ver figuras 3D y volumétricas, con subida y catálogo en PHP.

- **Three.js** → `.stl`, `.glb`, `.obj` (mallas de superficie). Loader `STLLoader`/`GLTFLoader` o `<model-viewer>`.
- **vtk.js** → `.vtk`, `.vtp` y volumétrico (`.vti`/DICOM) — visualización científica/médica.
- **PHP** = backend: subir/almacenar ficheros, catálogo en BBDD, permisos, servir el modelo, miniaturas.
- Sectores: médico (TAC/RM), ingeniería (FEA/CFD), geología, impresión 3D.
- Integrarlo con el **tema del curso** y, si se quiere, dentro del entorno **Docker** ya montado.
- (Opcional) Para análisis pesado: microservicio Python (VTK/SimpleITK) que PHP orquesta.

### Ideas futuras (mencionadas)
- Migraciones versionadas reales de CI3 (`application/migrations/`).
- Desplegar en Railway/Render/Fly.io con dominio público (GitHub **Pages NO** ejecuta PHP).
- Lavado visual de las vistas restantes.

---

## ▶️ Cómo retomar
1. Arrancar MAMP (o `docker compose up --build`).
2. Local: `http://localhost:8888/repoPersonales/php-course/` · Docker: `http://localhost:8080/`.
3. Continuar por la **Parte 4 (visor 3D)**.

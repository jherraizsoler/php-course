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
  - 🐛 Arreglado `tools/env-setup.php`: usaba `preg_replace` y el `$` del hash bcrypt se interpretaba como retro-referencia → hash corrupto en clones nuevos (login fallaba). Ahora usa `preg_replace_callback`.
- **`visor-3d/`** — **Parte 4 ✅**. Visor 3D/volumétrico en navegador + backend PHP (subida, catálogo MySQL `curso_3d`, servido del archivo). **Three.js** (mallas `.stl/.glb/.obj`, importmap jsDelivr, OrbitControls + auto-encuadre) y **vtk.js** (volumétrico `.vtp/.vti/.vtk`, UMD unpkg). Front controller `?action=` (lista/subir/ver/archivo/eliminar/**usos**). Seguridad: CSRF, whitelist de extensiones, límite 32 MB, nombre de destino aleatorio, servido solo de archivos del catálogo (anti path traversal). Seed: `uploads/cubo-ejemplo.stl`. **Verificado en Apache/Docker:** catálogo, subida (302+persistencia), servido con headers, eliminación (borra registro+archivo), rechazo de formato. La malla (Three.js) probada server-side; el render vtk.js necesita navegador para confirmar.
  - **Usos por sector** (`sectores.php` + `views/usos.php`): fichas profesionales de odontología, cirugía, radiología, ortopedia, ingeniería (FEA/CFD), geología, impresión 3D y arquitectura — con casos de uso y métricas/análisis por sector. Columna `sector` migrada de ENUM a `VARCHAR(32)` (ALTER idempotente en `schema.sql`).
  - **Métricas en vivo** en el visor: malla → nº triángulos, dimensiones, superficie y volumen calculados en el navegador (fórmula verificada contra el cubo: 12 tris, 2×2×2, área 24, vol 8); volumétrico → dimensiones/rango escalar/conteo desde vtk.js. Cada visor muestra además las métricas típicas de su sector.
  - **Verificado en navegador real (Chrome headless + puppeteer-core):** los 4 formatos (STL/VTP/VTI/VTK) renderizan, ocultan el overlay, rellenan métricas y NO dan errores de consola. Screenshots confirman geometría (cubo, tetraedros, volumen radial). Ejemplos volumétricos sembrados en `schema.sql` + `uploads/` (`ejemplo-tetraedro.vtp`, `ejemplo-volumen.vti`, `ejemplo-legacy.vtk`).
  - 🐛 Dos bugs encontrados y corregidos en esa verificación: (1) colisión de nombre `fmt` en el módulo Three.js (rompía todo el visor de mallas) → renombrado a `nf`; (2) vtk.js "latest" (36.x) tiene el build UMD global roto (`window.vtk` undefined) → **pineado a `vtk.js@32.9.0`**. Favicon SVG inline añadido (evita 404).
  - **Formatos FBX y glTF añadidos** (Three.js `FBXLoader` + `GLTFLoader` para `.glb`/`.gltf`): verificado en navegador real con Samba Dancing.fbx (55.320 triángulos, personaje renderiza) y un `.gltf` autocontenido embebido. Columna `formato` migrada ENUM→`VARCHAR(8)`. Ejemplo `ejemplo-triangulo.gltf` sembrado. No visualizables en web: `.blend/.max/.c4d/.ma/.mb` (propietarios → exportar).
  - **Subidas grandes:** PHP por defecto limita a 2 MB → subido a **1 GB** en `docker/php-course.ini` (`upload_max_filesize`/`post_max_size`/`memory_limit 512M`/`max_input_time 600`). El ini ahora se **monta como volumen** en `docker-compose.yml` (cambios sin reconstruir, solo `docker compose up -d web`). `config.php max_bytes` = 1 GB. Verificado: subida de 6 MB que antes fallaba ahora OK (HTTP 302).
  - **Fondo del visor configurable** (selector de color en la barra): por defecto sigue el tema claro/oscuro y reacciona al cambiarlo (MutationObserver), elección manual recordada en localStorage. Setter común para Three.js (`renderer.setClearColor`) y vtk.js (`renderer.setBackground`). Verificado en navegador (capturas: fondo oscuro/azul/claro en ambos motores). Antes el volumétrico tenía fondo casi negro hardcodeado.
  - **Ejemplos dentales reales** (sector odontología, fuente [thingiverse.com/thing:3587989](https://www.thingiverse.com/thing:3587989)): `Maxillary_Denture_Base.stl` (12,8 MB, 268.430 tris, arcada maxilar), `Anterior_Teeth.stl`, `Posterior_Teeth_1.stl`, `Posterior_Teeth_2.stl`. Verificados en navegador (métricas anatómicas en mm). Sembrados y versionados.
  - **Ejemplos de cirugía** (sector cirugia, fuente [vascularmodel.com](https://www.vascularmodel.com/dataset.html#0)): `carotid.vti` (carótida; era `.vtk` binario STRUCTURED_POINTS que vtk.js no lee → **convertido a .vti** con PHP, 76×49×45, volume rendering), `0157_0000.vtp`, `AS1_SU0308_prestent.stl` (aorta, convertida en 3D Slicer) — versionados y verificados. Los `.vtp` grandes **NO se versionan** (GitHub rechaza >100 MB): pulmonar 499 MB (no renderiza en navegador por memoria) y aorta 175 MB (sí renderiza) quedan solo en BBDD local.
  - **Atribución por modelo:** nueva columna `fuente VARCHAR(255)` (ALTER idempotente vía information_schema), campo opcional URL en el formulario de subida (validado con `FILTER_VALIDATE_URL`), enlace mostrado en el visor.
  - **Volume rendering mejorado** (vtk.js .vti): la función de transferencia inicial dejaba todo el volumen como "niebla". Ahora opacidad transparente en valores bajos/medios + **opacidad por gradiente** (resalta bordes) + iluminación (shade). La carótida pasa de cubo azul opaco a verse los vasos (como angiografía). Verificado con capturas.
  - **Arquitectura: Basílica del Pilar esquemática** (`basilica-pilar.stl`, modelo propio generado por código en `genpilar.mjs` temporal: cuerpo rectangular + 4 torres con agujas + cimborrio central + cupulines; 10.672 tris, 2,6 MB). Sembrada y versionada (sector arquitectura). No es reconstrucción exacta. Verificada en navegador (silueta reconocible).

### Puesta en marcha / despliegue
- **`setup.ps1`** / **`setup.sh`** — un comando: `composer install` + BBDD + `.env`.
- **`tools/db-setup.php`** (crea `curso` + `curso_tareas`) y **`tools/env-setup.php`** (genera `.env` de login-seguro).
- **Docker** (✅ probado funcionando): `Dockerfile` (PHP 8.3+Apache, ext: `pdo_mysql mysqli gd zip intl mbstring`), `docker-compose.yml` (web `:8080` + MySQL que crea las BBDD solas), `.dockerignore`, `docker/php-course.ini` (temp/sesiones → `/tmp`, evita el `mkdir(): Invalid path` de TCPDF).
  - Comando: `docker compose up --build` → todo en `http://localhost:8080/`.
  - Notas Docker: CI3 necesita **`mysqli`** (no solo pdo_mysql); TCPDF/sesiones necesitan `sys_temp_dir`/`session.save_path`.

### Licencia
- `LICENSE` (uso educativo, todos los derechos reservados, ES/EN) + sección en `README.md`. No uso comercial/edición sin permiso del autor.

---

## ⏳ PENDIENTE

> ✅ **Parte 4 (Visor 3D / volumétrico) — HECHA.** Ver `proyectos/visor-3d/` en la sección anterior.

### Ideas futuras (mencionadas)
- Migraciones versionadas reales de CI3 (`application/migrations/`).
- Desplegar en Railway/Render/Fly.io con dominio público (GitHub **Pages NO** ejecuta PHP).
- Lavado visual de las vistas restantes.

---

## ▶️ Cómo retomar
1. Arrancar MAMP (o `docker compose up --build`).
2. Local: `http://localhost:8888/repoPersonales/php-course/` · Docker: `http://localhost:8080/`.
3. Visor 3D: `…/proyectos/visor-3d/`. Si la BBDD `curso_3d` no existe, `php tools/db-setup.php`.
4. Siguiente: ideas futuras (miniaturas/auth en visor-3d, migraciones CI3, despliegue público).

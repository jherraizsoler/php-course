# 🏗️ Proyectos prácticos

Aquí juntas todo lo aprendido. Dos versiones del **mismo** CRUD (a mano vs. con framework) para que
veas la diferencia, más una **demo de seguridad** (login con 2FA) con las defensas de un proyecto real.

| Proyecto | Qué practica | Fase del curso (módulos) | Librerías / tecnologías |
|---|---|---|---|
| [`crud-php-puro/`](crud-php-puro/) | PDO, MVC manual, seguridad, sesiones | `03` · `04` · `05` · `06` | PHP nativo (sin framework) |
| [`crud-codeigniter3/`](crud-codeigniter3/) | CI3, Query Builder, validación, *flashdata*, **exportación** y **dashboard** | `03` · `04` · `05` · `06` · `07` | CodeIgniter 3 · **TCPDF** · **PhpSpreadsheet** · **Chart.js** · **GD** |
| [`login-seguro/`](login-seguro/) | **CSRF · 2FA (TOTP) · .env · rate limiting** | `04` · `05` · `06` | **phpdotenv** · **google2fa** · QRCode.js |
| [`visor-3d/`](visor-3d/) | **Subida + catálogo (MySQL) + visor 3D/volumétrico** en el navegador | `03` · `04` · `05` · `06` | **Three.js** (mallas) · **vtk.js** (volumétrico) · PDO |

> 🧭 **¿En qué punto del curso estás?** La columna *Fase del curso* te dice qué módulos necesitas
> dominar para cada proyecto. La columna *Librerías* indica las dependencias que usa.

**Orden recomendado:** primero el de PHP puro (entiendes lo que pasa por debajo), luego el de CI3
(ves cuánto te ahorra el framework — y reconoces el patrón de un proyecto profesional).

Cada carpeta tiene su propio `README.md` con instrucciones paso a paso.

### Atajos directos
- 🐘 **[CRUD en PHP puro](crud-php-puro/)** — el CRUD a mano.
- 🔥 **[CRUD en CodeIgniter 3](crud-codeigniter3/)** — framework + exportación CSV/Excel/PDF + dashboard.
- 🔐 **[Login seguro](login-seguro/)** — CSRF + 2FA (TOTP) + .env + rate limiting.
- 🧊 **[Visor 3D](visor-3d/)** — subida + catálogo en MySQL + visor 3D/volumétrico (Three.js + vtk.js).
- 🏠 **[Volver al inicio del curso](../index.php)** · 🗺️ **[Índice de módulos](../README.md)**

---

⬅️ Anterior: [**09 · Intro a CodeIgniter 4**](../09-codeigniter4-intro/README.md) · 🏠 [**Índice del curso**](../README.md)

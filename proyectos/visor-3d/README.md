# 🧊 Visor 3D / volumétrico

Sube y visualiza modelos 3D **en el navegador**, con un backend en **PHP** que gestiona la subida,
el catálogo (MySQL) y el servido de cada archivo. Es la **Parte 4** del curso: junta PHP (servidor)
con dos motores de render de cliente según el tipo de dato.

| Motor | Para qué | Formatos |
|---|---|---|
| **[Three.js](https://threejs.org/)** | Mallas de superficie | `.stl` · `.glb` · `.gltf`* · `.obj` · `.fbx` |
| **[vtk.js](https://kitware.github.io/vtk-js/)** | Datos científicos / volumétrico | `.vtp` · `.vti` · `.vtk` |

> \* El `.gltf` (texto) debe ser **autocontenido** (buffers/texturas embebidos). Los formatos de
> proyecto propietarios (`.blend`, `.max`, `.c4d`, `.ma/.mb`) **no** se pueden ver en navegador:
> hay que exportarlos antes a glTF/OBJ/STL/FBX.

### Límites de subida
Por defecto PHP solo admite **2 MB**. El visor sube ese límite a **1 GB** en
[`docker/php-course.ini`](../../docker/php-course.ini) (`upload_max_filesize` / `post_max_size`),
montado como volumen en `docker-compose.yml` (cambiarlo no requiere reconstruir, solo
`docker compose up -d web`). En MAMP, ajusta esos mismos valores en tu `php.ini`.

## 🎯 Usos por sector

El mismo visor sirve a muchos campos; lo que cambia es el **tipo de dato** y las **métricas** que cada
sector necesita. La página **`?action=usos`** detalla, por sector, casos de uso reales y el análisis de
datos que aporta valor:

| Sector | Datos | Ejemplos de métricas / análisis |
|---|---|---|
| 🦷 **Odontología** | Malla (escáner intraoral) + CBCT | Mediciones mm/°, densidad ósea (HU), hueso disponible, oclusión |
| 🩺 **Cirugía** | TAC/RM + malla | Volumen de lesión (cm³), márgenes, distancia a estructuras críticas |
| ☢️ **Radiología** | Volumétrico (TAC/RM/PET) | Densidad (HU), volúmenes por umbral, SUV, histogramas |
| 🦴 **Ortopedia** | Malla + volumétrico | Ejes y ángulos articulares, simetría, calidad ósea |
| ⚙️ **Ingeniería (FEA/CFD)** | Resultados + malla | Tensión de von Mises, factor de seguridad, velocidad/presión |
| ⛰️ **Geología** | Sísmica volumétrica | Porosidad, volumen de roca, leyes de mineral, isosuperficies |
| 🖨️ **Impresión 3D** | Malla | Volumen→gramos, superficie, dimensiones, errores de malla |
| 🏛️ **Arquitectura** | Malla / fotogrametría | Superficies y volúmenes (m²/m³), cotas, desviación de proyecto |

> Las **métricas geométricas** (triángulos, dimensiones, superficie, volumen) se calculan **en vivo en el
> navegador** al abrir cada malla. Las métricas por sector de la tabla son de referencia.

---

## ¿Qué hace cada parte?

- **PHP = backend.** `index.php` es un *front controller* (`?action=…`) con estas rutas:
  - `lista` (def.) — catálogo en galería.
  - `subir` — formulario + procesado de la subida.
  - `usos` — **usos por sector**: casos de uso y métricas por campo profesional.
  - `ver&id=` — página del visor (carga Three.js o vtk.js según el formato) con **métricas en vivo**.
  - `archivo&id=` — **sirve el binario** del modelo (lo consumen los *loaders* JS).
  - `eliminar&id=` — borra el registro y el archivo.
- **JavaScript = render.** El 3D ocurre en el navegador; PHP solo entrega los bytes.
- **Fondo del visor** configurable (selector de color en la barra): por defecto **sigue el tema**
  claro/oscuro y reacciona al cambiarlo; la elección manual se recuerda (localStorage). Funciona
  igual en Three.js y vtk.js.

### Seguridad aplicada (lo que enseña el curso)
- **CSRF** con token por sesión en subir y eliminar.
- **Whitelist de extensiones** + **límite de tamaño** (32 MB) en la subida.
- **Nombre de destino generado por el servidor** (`random_bytes`), nunca el del usuario.
- **Servido controlado**: solo se sirven archivos que están en el catálogo (id → BBDD),
  así no se puede pedir una ruta arbitraria del servidor (*path traversal*).
- Consultas **preparadas** siempre (PDO).

---

## Puesta en marcha

### Con Docker (recomendado)
Ya está integrado en el `docker-compose.yml` del curso (crea la BBDD `curso_3d` sola):

```bash
docker compose up --build
```

Luego abre **http://localhost:8080/proyectos/visor-3d/**.

### Con MAMP
1. Arranca MAMP (Apache + MySQL).
2. Crea la BBDD: `php tools/db-setup.php` (desde la raíz del repo).
3. Abre **http://localhost:8888/repoPersonales/php-course/proyectos/visor-3d/**.

> El catálogo arranca con un **cubo de ejemplo** (`uploads/cubo-ejemplo.stl`) para que veas
> el visor de mallas sin subir nada. Las subidas de usuario se guardan en `uploads/` (ignoradas por git).

---

## Estructura

```
visor-3d/
├── index.php            # front controller (rutas + lógica de subida)
├── config.php           # BBDD + límites + formatos (lee entorno, fallback MAMP)
├── sectores.php         # fichas profesionales por sector (casos de uso + métricas)
├── Database.php         # conexión PDO (singleton)
├── ModeloRepository.php # acceso a la tabla `modelos`
├── helpers.php          # e(), bytes_legibles(), CSRF
├── layout.php           # chrome del curso (tema claro/oscuro)
├── schema.sql           # BBDD curso_3d + tabla modelos + seed
├── views/
│   ├── catalogo.php     # galería
│   ├── usos.php         # usos por sector (casos de uso + métricas)
│   ├── subir.php        # formulario de subida
│   └── visor.php        # Three.js (mallas) / vtk.js (volumétrico) + métricas en vivo
└── uploads/             # archivos subidos (cubo-ejemplo.stl versionado; el resto no)
```

## Notas y posibles mejoras
- Three.js y vtk.js se cargan desde **CDN** (jsDelivr / unpkg). Sin conexión, el visor avisa.
- Para análisis volumétrico pesado (DICOM, mallas FEA grandes) lo natural sería un microservicio
  Python (VTK/SimpleITK) que PHP orquesta — fuera del alcance de esta demo.
- Posible siguiente paso: miniaturas, autenticación por usuario, y conversión de formatos en el servidor.

---

⬅️ [**Volver a Proyectos**](../README.md) · 🏠 [**Índice del curso**](../../README.md)

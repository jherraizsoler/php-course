# CRUD en CodeIgniter 3 — Gestor de usuarios 🏗️

El mismo tipo de aplicación que el CRUD en PHP puro, pero hecho con **CodeIgniter 3** — el framework
de un proyecto profesional. Verás cuánto código te ahorra el framework y reconocerás el patrón de un CRM profesional.

> ✅ **La app ya está montada y funcionando** en la carpeta [`app/`](app/) de este mismo proyecto.
> - **Para verla:** abre `http://localhost:8888/repoPersonales/php-course/proyectos/crud-codeigniter3/app/`
> - **Requisito:** MySQL de MAMP arrancado y la BBDD `curso` importada
>   (desde `05-php-web/ejemplos/db/schema.sql`). La conexión ya está configurada: `127.0.0.1:8889`, `root`/`root`.
> - **Exportar:** el listado se descarga en **CSV, Excel (.xlsx) y PDF** (botones en la app).
>   Usan librerías de Composer (TCPDF + PhpSpreadsheet). Si **clonas** el repo, instálalas con
>   `composer install` dentro de `app/` (el `vendor/` no se versiona). Requiere la extensión
>   `fileinfo` activa en tu `php.ini`.
> - **Dashboard:** `/dashboard` muestra métricas con **Chart.js** (render en cliente) y la misma
>   gráfica generada con **GD** en el servidor (`/dashboard/png`, imagen PNG sin JavaScript).
>
> Los pasos de abajo explican **cómo se construye desde cero** (por si quieres entender el montaje o
> rehacerlo tú mismo). La versión incluida ya tiene todos estos pasos aplicados.

## Paso 1 — Crear el proyecto CI3

```bash
cd C:\MAMP\htdocs
composer create-project codeigniter/framework crud-ci3 "^3.1"
```

Esto crea `C:\MAMP\htdocs\crud-ci3` con la estructura `application/` + `system/` + `index.php`.

## Paso 2 — Crear la base de datos

Importa el esquema (reutilizamos el del Módulo 05):

```bash
mysql -u root -p < ..\..\05-php-web\ejemplos\db\schema.sql
```

Crea la BBDD `curso` con la tabla `usuarios`.

## Paso 3 — Configurar CI3

**`application/config/database.php`** — ajusta la conexión:
```php
$db['default'] = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => 'root',     // tu pass de MAMP
    'database' => 'curso',
    'dbdriver' => 'mysqli',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    // ... resto por defecto
];
```

**`application/config/config.php`** — ajusta la URL base:
```php
$config['base_url'] = 'http://localhost/crud-ci3/';
$config['index_page'] = 'index.php';   // o '' si configuras .htaccess para quitarlo
```

**`application/config/autoload.php`** — carga lo que usaremos siempre:
```php
$autoload['libraries'] = ['database', 'session'];
$autoload['helper']    = ['url', 'form'];
```

**`application/config/routes.php`**:
```php
$route['default_controller'] = 'usuarios';
```

## Paso 4 — Copiar los archivos de la app

Los archivos del CRUD están listos en [`../../07-codeigniter3/ejemplo-app/`](../../07-codeigniter3/ejemplo-app/).
Cópialos a tu proyecto:

| Desde `07-codeigniter3/ejemplo-app/` | A tu proyecto `crud-ci3/application/` |
|---|---|
| `controllers/Usuarios.php` | `controllers/Usuarios.php` |
| `models/Usuario_model.php` | `models/Usuario_model.php` |
| `views/usuarios/index.php` | `views/usuarios/index.php` |
| `views/usuarios/form.php` | `views/usuarios/form.php` |

```powershell
# Desde la raíz del curso, en PowerShell:
$src = "07-codeigniter3\ejemplo-app"
$dst = "C:\MAMP\htdocs\crud-ci3\application"
Copy-Item "$src\controllers\Usuarios.php"    "$dst\controllers\"
Copy-Item "$src\models\Usuario_model.php"    "$dst\models\"
New-Item -ItemType Directory -Force "$dst\views\usuarios"
Copy-Item "$src\views\usuarios\*"            "$dst\views\usuarios\"
```

## Paso 5 — Probar

Arranca MAMP y abre:
```
http://localhost/crud-ci3/
```

Deberías poder listar, crear (con validación de email único), editar y eliminar usuarios.

## Qué comparar con el CRUD en PHP puro

| | PHP puro | CodeIgniter 3 |
|---|---|---|
| Routing | `switch` manual en `index.php` | Automático (`controlador/metodo`) |
| Conexión BBDD | Clase `Database` propia | `$this->db` ya cargado |
| SQL | PDO a mano | Query Builder (`$this->db->...`) |
| Validación | `if` a mano | `form_validation` con reglas |
| Mensajes | manual | `flashdata` |
| Escape salida | `e()` propia | `html_escape()` |

**Conclusión:** el framework te da hecho el 70% del fontanería. Por eso proyectos como un CRM profesional
pueden centrarse en la lógica de negocio. Pero gracias al CRUD en PHP puro, **sabes lo que CI hace
por debajo** — y eso es lo que te hace buen desarrollador.

## Reto

- Añade búsqueda usando el método `buscar()` que ya está en `Usuario_model`.
- Añade paginación con la librería `pagination` de CI3.
- Crea un segundo módulo (tareas) y conéctalo. Si te atreves, organízalo como **HMVC** (Módulo 07).

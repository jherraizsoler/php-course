# 09 · Intro a CodeIgniter 4 🔵

Tu trabajo es CI3, pero CI4 es el presente del framework. Este módulo te muestra **qué cambia** para
que, si algún día migras o tocas un proyecto nuevo, no te pierdas. Aquí se aplican de golpe los
namespaces y PSR-4 del Módulo 04.

> 📖 Docs CI4: https://codeigniter.com/user_guide/

---

## 1. ¿Por qué existe CI4?

CI3 es de la era PHP 5. CI4 (requiere PHP 8.1+) reescribió todo para usar lo moderno: **namespaces,
PSR-4, inyección de dependencias, Composer de serie, ORM (Entities), CLI (`spark`)**.

| Aspecto | CodeIgniter 3 (enfoca-nexo) | CodeIgniter 4 |
|---|---|---|
| PHP mínimo | 5.6+ | **8.1+** |
| Namespaces | Casi no | **Sí, en todo** (`App\Controllers\…`) |
| Autoload | Por convención de carpetas | **PSR-4 (Composer)** |
| Punto de entrada | `index.php` en la raíz | `public/index.php` (más seguro) |
| Carga de clases | `$this->load->model()` | `new Model()` / inyección |
| Acceso a request | `$this->input->post()` | `$this->request->getPost()` |
| BBDD | Query Builder | Query Builder **+ Entities/Model ORM** |
| Consola | No tiene | **`php spark`** (genera código, migra…) |
| Tests | Manual | **PHPUnit integrado** |

---

## 2. El mismo controlador, en los dos

### CodeIgniter 3 (lo que usas)
```php
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
    }

    public function index()
    {
        $data['usuarios'] = $this->Usuario_model->obtener_todos();
        $this->load->view('usuarios/index', $data);
    }
}
```

### CodeIgniter 4 (moderno)
```php
<?php

namespace App\Controllers;            // ← namespace

use App\Models\UsuarioModel;          // ← import explícito

class Usuarios extends BaseController
{
    public function index()
    {
        $modelo = new UsuarioModel();              // ← instancia directa
        $usuarios = $modelo->findAll();            // ← método ORM
        return view('usuarios/index', ['usuarios' => $usuarios]); // ← return
    }
}
```

**Diferencias clave:**
- CI4 usa `namespace` y `use` (Módulo 03/04). CI3 carga por convención.
- CI4 devuelve la vista con `return view(...)`; CI3 hace `$this->load->view(...)`.
- CI4 instancia modelos con `new`; CI3 con `$this->load->model()`.

---

## 3. El Model ORM de CI4

En CI4 el modelo ya trae CRUD hecho si lo configuras:

```php
<?php
namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table         = 'usuarios';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nombre', 'email'];
    protected $returnType    = 'object';
    protected $useTimestamps = true;   // gestiona created_at/updated_at solo
}
```

```php
$modelo = new UsuarioModel();
$modelo->findAll();              // SELECT *
$modelo->find(5);                // por id
$modelo->where('activo', 1)->findAll();
$modelo->insert(['nombre' => 'Ana', 'email' => 'ana@x.com']);
$modelo->update(5, ['nombre' => 'Ana María']);
$modelo->delete(5);
```

Comparado con CI3, te ahorras escribir los métodos `obtener_todos()`, `crear()`, etc. del Módulo 07.

---

## 4. La consola `spark`

CI4 trae una CLI potente (parecida a `artisan` de Laravel):

```bash
php spark serve                       # servidor de desarrollo (sin MAMP)
php spark make:controller Usuarios    # genera un controlador
php spark make:model UsuarioModel     # genera un modelo
php spark make:migration crear_usuarios
php spark migrate                     # ejecuta las migraciones
php spark routes                      # lista todas las rutas
```

---

## 5. Instalar CI4 para probar

```bash
composer create-project codeigniter4/appstarter mi-app-ci4
cd mi-app-ci4
php spark serve     # abre http://localhost:8080  (¡sin MAMP!)
```

---

## 6. ¿Debería migrar enfoca-nexo a CI4?

**No es trivial.** Perfex está construido sobre CI3 + HMVC + su propio sistema de módulos y hooks.
Migrar a CI4 sería prácticamente reescribir la aplicación. Por eso:

- Para **tu trabajo diario**: domina **CI3** (Módulo 07). Es lo que toca.
- Para **proyectos nuevos propios**: usa **CI4** (o Laravel/Symfony). Buenas prácticas modernas.
- Saber los dos te hace más valioso: entiendes el legacy y construyes lo nuevo.

---

## 📚 Resumen

- CI4 = CI3 modernizado: namespaces, PSR-4, ORM, `spark`, PHP 8.1+.
- Conceptualmente es el mismo MVC; cambia la sintaxis y la organización.
- enfoca-nexo seguirá en CI3 (Perfex). CI4 es para tus proyectos nuevos.
- Todo lo que aprendiste (POO, Composer, PSR-4, SOLID) se aplica **directamente** en CI4.

➡️ Practica todo junto en los **[proyectos](../proyectos/)**.

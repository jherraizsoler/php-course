# 07 · CodeIgniter 3 🟠

El framework de tu proyecto real. **enfoca-nexo usa CodeIgniter 3.1.11** (un Perfex CRM) con HMVC y
hooks. Este módulo te lleva de no saber nada de CI a entender la estructura de Perfex.

> 📖 Documentación oficial CI3: https://codeigniter.com/userguide3/

---

## 1. ¿Qué es CodeIgniter y qué es MVC?

CodeIgniter es un **framework MVC**: te da estructura y herramientas para no reinventar todo lo del
Módulo 05 (routing, BBDD, validación, sesiones, seguridad) a mano.

**MVC** separa tu app en tres capas:

```
        Petición HTTP
              │
              ▼
   ┌──────────────────┐     usa      ┌──────────────┐
   │   CONTROLADOR    │ ───────────► │    MODELO     │ ──► Base de datos
   │ (orquesta todo)  │ ◄─────────── │ (datos/lógica)│
   └────────┬─────────┘    datos     └──────────────┘
            │ pasa datos a
            ▼
   ┌──────────────────┐
   │      VISTA        │ ──► HTML al navegador
   │  (presentación)  │
   └──────────────────┘
```

- **Modelo** → habla con la base de datos y contiene la lógica de negocio.
- **Vista** → el HTML que ve el usuario. Sin lógica complicada.
- **Controlador** → recibe la petición, pide datos al modelo y elige la vista.

---

## 2. Estructura de un proyecto CI3

```
proyecto/
├── application/          ← TU código va aquí
│   ├── controllers/      ← controladores (Welcome.php, Usuarios.php…)
│   ├── models/           ← modelos (Usuario_model.php…)
│   ├── views/            ← plantillas HTML/PHP
│   ├── config/           ← config.php, database.php, routes.php, autoload.php
│   ├── helpers/          ← funciones sueltas (url_helper, form_helper…)
│   ├── libraries/        ← clases de servicio
│   └── hooks/            ← "ganchos" para ejecutar código en puntos clave
├── system/               ← el core de CodeIgniter (NO lo tocas)
└── index.php             ← punto de entrada único (front controller)
```

> 🔍 Esto es **exactamente** lo que viste en `enfoca-nexo`: carpetas `application/` y `system/`.
> Perfex añade encima sus `modules/`, `libraries/App_*.php` y `hooks/`.

---

## 3. Un controlador

`application/controllers/Usuarios.php`:

```php
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');   // cargar el modelo
        $this->load->helper('url');             // cargar un helper
    }

    // http://localhost/usuarios          → index()
    // http://localhost/usuarios/ver/5    → ver(5)
    public function index()
    {
        $data['usuarios'] = $this->Usuario_model->obtener_todos();
        $this->load->view('usuarios/index', $data);   // pasa $data a la vista
    }

    public function ver($id)
    {
        $data['usuario'] = $this->Usuario_model->obtener($id);
        $this->load->view('usuarios/ver', $data);
    }
}
```

> 🧠 Fíjate: extiende `CI_Controller` (¡herencia del Módulo 03!). `$this->load` es CI cargando
> recursos por ti. La URL mapea sola a métodos: `controlador/metodo/parametros`.

---

## 4. Un modelo y el Query Builder

`application/models/Usuario_model.php`:

```php
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario_model extends CI_Model
{
    public function obtener_todos()
    {
        // Query Builder: seguro (escapa valores) y legible
        return $this->db->order_by('id', 'DESC')->get('usuarios')->result();
    }

    public function obtener($id)
    {
        return $this->db->where('id', $id)->get('usuarios')->row();
    }

    public function crear($datos)
    {
        $this->db->insert('usuarios', $datos);
        return $this->db->insert_id();
    }

    public function actualizar($id, $datos)
    {
        return $this->db->where('id', $id)->update('usuarios', $datos);
    }

    public function eliminar($id)
    {
        return $this->db->where('id', $id)->delete('usuarios');
    }
}
```

### Query Builder — equivalencias con SQL

| Query Builder | SQL |
|---|---|
| `$this->db->get('t')` | `SELECT * FROM t` |
| `->where('id', 5)->get('t')` | `WHERE id = 5` |
| `->where(['activo' => 1, 'rol' => 'admin'])` | `WHERE activo=1 AND rol='admin'` |
| `->like('nombre', 'jor')` | `WHERE nombre LIKE '%jor%'` |
| `->join('roles r', 'r.id = u.rol_id')` | `JOIN` |
| `->order_by('id', 'DESC')` | `ORDER BY id DESC` |
| `->limit(10, 20)` | `LIMIT 10 OFFSET 20` |
| `->insert('t', $datos)` | `INSERT` |
| `->update('t', $datos)` | `UPDATE` |
| `->delete('t')` | `DELETE` |

> ✅ El Query Builder **escapa los valores automáticamente** → te protege de inyección SQL (el
> peligro del Módulo 05). Por eso en CI3 casi nunca escribes SQL a mano.

---

## 5. Una vista

`application/views/usuarios/index.php`:

```php
<h1>Usuarios</h1>
<ul>
<?php foreach ($usuarios as $u): ?>
    <li><?= html_escape($u->nombre) ?> — <?= html_escape($u->email) ?></li>
<?php endforeach; ?>
</ul>
```

`html_escape()` es el `htmlspecialchars()` del Módulo 05, pero más corto. **Escapa siempre.**

---

## 6. Routing, validación, sesiones (lo que CI te da hecho)

```php
// application/config/routes.php
$route['default_controller'] = 'usuarios';
$route['usuarios/perfil/(:num)'] = 'usuarios/ver/$1';  // ruta personalizada

// Validación de formularios (librería form_validation):
$this->load->library('form_validation');
$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
if ($this->form_validation->run()) {
    // datos válidos
}

// Sesiones (envuelve $_SESSION del Módulo 05):
$this->load->library('session');
$this->session->set_userdata('usuario_id', 42);
$id = $this->session->userdata('usuario_id');
$this->session->set_flashdata('ok', 'Guardado'); // mensaje de un solo uso
```

---

## 7. HMVC y hooks (cómo está hecho Perfex)

Perfex no es CI3 "pelado". Añade dos cosas que verás en enfoca-nexo:

### HMVC (Modular Extensions)
La carpeta `application/third_party/MX` permite organizar el código en **módulos** independientes
(cada uno con sus controllers/models/views). En enfoca-nexo lo ves en `modules/`
(`document_management`, `lists`, `policies`…). Un módulo se llama así:

```php
$datos = Modules::run('document_management/algun_metodo', $param);
```

### Hooks (estilo WordPress)
Perfex usa `bainternet/php-hooks` + `application/third_party/action_hooks.php` para "engancharse" a
eventos sin tocar el core. Es el sistema de **acciones y filtros**:

```php
// Registrar una acción (se ejecuta cuando ocurre el evento)
hooks()->add_action('after_invoice_added', 'mi_funcion');

// Filtrar/modificar un valor antes de usarlo
$valor = hooks()->apply_filters('nombre_filtro', $valor);
```

> 🧩 Así los módulos de Perfex añaden funcionalidad sin modificar el núcleo: se "cuelgan" de hooks.
> Es la pieza clave para entender cómo se extiende enfoca-nexo.

---

## 8. Cómo instalar CI3 para practicar

```bash
# Descarga CI3 (incluye la estructura completa application/ + system/)
composer create-project codeigniter/framework mi-app-ci3 "^3.1"

# Colócalo en MAMP:
#   C:\MAMP\htdocs\mi-app-ci3
# Configura la BBDD en application/config/database.php
# Abre http://localhost/mi-app-ci3
```

En la carpeta [`ejemplo-app/`](ejemplo-app/) tienes los archivos de un CRUD de usuarios listos para
copiar dentro de un proyecto CI3 (controlador + modelo + vistas comentados). El proyecto completo y
montado está en [`../proyectos/crud-codeigniter3/`](../proyectos/crud-codeigniter3/).

---

## 📚 Resumen

- CI3 = framework **MVC**. Controlador orquesta, Modelo accede a datos, Vista presenta.
- `application/` = tu código; `system/` = el core (no tocar).
- **Query Builder** (`$this->db->...`) = SQL seguro y legible.
- `$this->load->...` carga modelos, vistas, librerías y helpers.
- **Perfex/enfoca-nexo** = CI3 + **HMVC** (módulos) + **hooks** (acciones/filtros).

➡️ Siguiente: **[08 · Librerías clave](../08-librerias-clave/)**

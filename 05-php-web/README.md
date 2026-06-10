# 05 · PHP y la web 🟡

Aquí PHP cobra sentido: recibir datos de formularios, mantener sesiones, conectarte a MySQL y
hacerlo **de forma segura**. Esto es lo que un framework como CodeIgniter automatiza por ti — pero
primero hay que entenderlo a pelo.

> 🖥️ **Este módulo necesita MAMP** (Apache + MySQL). Sirve el curso desde `htdocs` (ver
> [Módulo 00](../00-entorno/)) y abre `http://localhost/php-course/05-php-web/ejemplos/...`

---

## 1. El ciclo petición → respuesta

```
Navegador  ──GET /pagina.php?id=5──►  Apache → PHP
   ▲                                            │ ejecuta el script
   └────────  HTML generado  ◄─────────────────┘
```

PHP recibe los datos de la petición en **superglobales**:

| Superglobal | Contiene |
|---|---|
| `$_GET` | Parámetros de la URL (`?id=5`) |
| `$_POST` | Datos enviados por formulario (método POST) |
| `$_REQUEST` | GET + POST + COOKIE (evítalo, ambiguo) |
| `$_SESSION` | Datos de sesión del usuario |
| `$_COOKIE` | Cookies del navegador |
| `$_SERVER` | Info del servidor y la petición |
| `$_FILES` | Archivos subidos |

---

## 2. Formularios (GET y POST)

```php
<form method="post" action="procesar.php">
    <input type="text" name="nombre">
    <button type="submit">Enviar</button>
</form>
```

```php
// procesar.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // NUNCA confíes en la entrada del usuario: valida y sanea
    $nombre = trim($_POST['nombre'] ?? '');
    if ($nombre === '') {
        die('El nombre es obligatorio');
    }
    // Al imprimir en HTML, ESCAPA siempre (anti-XSS):
    echo "Hola, " . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
}
```

> ⚠️ **Las 2 reglas que nunca se rompen:**
> 1. **Valida** la entrada al recibirla (¿es lo que espero?).
> 2. **Escapa** la salida al imprimirla (`htmlspecialchars`) para evitar **XSS**.

---

## 3. Sesiones y cookies

La sesión te permite "recordar" al usuario entre peticiones (login, carrito…).

```php
session_start();                  // al principio de TODO, antes de imprimir nada

$_SESSION['usuario_id'] = 42;     // guardar
$id = $_SESSION['usuario_id'] ?? null;   // leer

session_destroy();                // cerrar sesión (logout)

// Cookies (viven en el navegador):
setcookie('tema', 'oscuro', time() + 3600 * 24 * 30, '/');
echo $_COOKIE['tema'] ?? 'claro';
```

---

## 4. Conexión a MySQL con PDO

**PDO** es la forma moderna y segura de hablar con la base de datos. Lo más importante:
**consultas preparadas** para evitar **inyección SQL**.

```php
$pdo = new PDO(
    'mysql:host=localhost;dbname=curso;charset=utf8mb4',
    'root',
    'root',                       // en MAMP el usuario y pass suelen ser root/root
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // errores como excepciones
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // filas como arrays asociativos
    ]
);

// ✅ CORRECTO: consulta preparada (los datos van separados de la SQL)
$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
$stmt->execute([$email]);
$usuario = $stmt->fetch();

// Con parámetros con nombre:
$stmt = $pdo->prepare('INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)');
$stmt->execute(['nombre' => $nombre, 'email' => $email]);
$nuevoId = $pdo->lastInsertId();
```

```php
// ❌ NUNCA HAGAS ESTO (inyección SQL):
$pdo->query("SELECT * FROM usuarios WHERE email = '$email'"); // ¡vulnerable!
```

> 🧠 CodeIgniter 3 envuelve PDO/mysqli en su **Query Builder** (`$this->db->where(...)->get()`),
> que escapa los valores por ti. Pero entender PDO te enseña *por qué* el Query Builder es seguro.

---

## 5. Hashing de contraseñas

Las contraseñas **jamás** se guardan en texto plano. PHP trae funciones para esto:

```php
// Al registrar:
$hash = password_hash($passwordPlano, PASSWORD_DEFAULT);  // guarda $hash en la BBDD

// Al hacer login:
if (password_verify($passwordIntroducido, $hashGuardado)) {
    echo "Login correcto";
}
```

---

## 🏋️ Ejemplos ejecutables (en MAMP)

- [`ejemplos/01-formulario.php`](ejemplos/01-formulario.php) — formulario con validación y escape anti-XSS.
- [`ejemplos/02-sesiones.php`](ejemplos/02-sesiones.php) — contador de visitas con `$_SESSION`.
- [`ejemplos/03-pdo.php`](ejemplos/03-pdo.php) — conexión PDO + consulta preparada (crea su propia
  tabla SQLite si no tienes MySQL, para que puedas probarlo sin configurar nada).

## 🏋️ Ejercicios

1. **Login básico** — formulario de email + contraseña que valide contra un usuario "hardcodeado"
   con `password_hash`/`password_verify` y guarde la sesión.
2. **Libro de visitas** — formulario que guarde mensajes en un array de sesión y los liste,
   escapando siempre la salida.

---

## 📚 Resumen

- Datos de entrada en superglobales: `$_GET`, `$_POST`, `$_SESSION`…
- **Valida la entrada, escapa la salida** (`htmlspecialchars` anti-XSS).
- **PDO + consultas preparadas** anti-inyección SQL. Nunca concatenes SQL.
- Contraseñas con `password_hash` / `password_verify`.

➡️ Siguiente: **[06 · Buenas prácticas](../06-buenas-practicas/)**

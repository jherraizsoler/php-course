# CRUD en PHP puro — Gestor de tareas 🏗️

Una mini-aplicación **MVC hecha a mano** (sin framework) para gestionar tareas: crear, listar,
completar y borrar. Aplica todo lo de los módulos 03–06: POO, PDO seguro, validación, escape
anti-XSS y separación de responsabilidades.

## Qué demuestra

- **Front controller**: un único `index.php` que enruta según `?action=`.
- **Capa de datos** (`TareaRepository`) separada con **PDO + consultas preparadas**.
- **Vistas** separadas de la lógica.
- **Seguridad**: escape de salida y consultas parametrizadas.

## Cómo ejecutarlo

1. Arranca **MAMP** (Apache + MySQL).
2. Importa la base de datos:
   ```bash
   mysql -u root -p < schema.sql
   ```
   (o impórtalo desde phpMyAdmin). Crea la BBDD `curso_tareas` y la tabla `tareas`.
3. Si tu MySQL de MAMP no usa `root`/`root` o el puerto `3306`, edita [`config.php`](config.php).
4. Coloca el curso en `htdocs` (ver Módulo 00) y abre:
   ```
   http://localhost/php-course/proyectos/crud-php-puro/
   ```

## Estructura

```
crud-php-puro/
├── index.php          ← front controller + router (el "controlador")
├── config.php         ← configuración de BBDD
├── Database.php       ← conexión PDO (singleton)
├── TareaRepository.php← acceso a datos (el "modelo")
├── helpers.php        ← función e() para escapar salida
├── views/
│   ├── layout.php     ← plantilla común
│   ├── lista.php      ← listado de tareas
│   └── form.php       ← formulario crear/editar
└── schema.sql         ← estructura de la BBDD
```

## Reto para trastear

- Añade un campo "prioridad" (baja/media/alta) y permite filtrar por él.
- Añade fecha de vencimiento usando **Carbon** (Módulo 08).
- Refactoriza el router a una clase `Router` con SOLID (Módulo 06).

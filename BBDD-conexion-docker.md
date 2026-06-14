# Acceso a la base de datos Docker desde DBeaver / phpMyAdmin

El puerto MySQL del contenedor está mapeado al **3307** del host (para no chocar con MAMP/XAMPP que usa el 3306).

---

## DBeaver (lo más directo)

Nueva conexión → MySQL con estos datos:

| Campo | Valor |
|---|---|
| Host | `localhost` |
| Puerto | `3307` |
| Usuario | `root` |
| Contraseña | `root` |
| Base de datos | `curso` o `curso_tareas` |

---

## phpMyAdmin de XAMPP

phpMyAdmin de XAMPP por defecto solo apunta a su propio MySQL (127.0.0.1:3306). Para que apunte al contenedor Docker tienes dos opciones:

### Opción A — Añadir un servidor extra en phpMyAdmin (sin tocar XAMPP)

Edita `C:\xampp\phpMyAdmin\config.inc.php` y añade al final:

```php
$cfg['Servers'][2]['host']      = '127.0.0.1';
$cfg['Servers'][2]['port']      = '3307';
$cfg['Servers'][2]['auth_type'] = 'cookie';
$cfg['Servers'][2]['verbose']   = 'Docker MySQL';
```

Luego en la pantalla de login de phpMyAdmin aparecerá un selector de servidor.

### Opción B — Añadir phpMyAdmin como servicio Docker (más limpio, sin tocar XAMPP)

Añade esto al `docker-compose.yml`:

```yaml
  phpmyadmin:
    image: phpmyadmin:latest
    ports:
      - "8081:80"
    environment:
      PMA_HOST: db
      PMA_PORT: 3306
      MYSQL_ROOT_PASSWORD: root
    depends_on:
      - db
```

Luego ejecuta:

```bash
docker compose up --build
```

Y accede en **http://localhost:8081** con `root` / `root`.

> Esta es la opción más cómoda porque no depende de XAMPP.

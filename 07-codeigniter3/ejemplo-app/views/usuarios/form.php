<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?= html_escape($accion) ?> usuario</title>
    <style>
        body { font-family: sans-serif; max-width: 480px; margin: 40px auto; }
        .error { color: red; }
        input { display: block; margin: 8px 0; padding: 6px; width: 100%; }
    </style>
</head>
<body>
    <h1><?= html_escape($accion) ?> usuario</h1>

    <!-- validation_errors() imprime los errores de form_validation -->
    <div class="error"><?= validation_errors() ?></div>

    <?php
        // En edición rellenamos con los valores actuales; tras un POST fallido,
        // set_value() mantiene lo que el usuario había escrito.
        $nombre = isset($usuario) ? $usuario->nombre : '';
        $email  = isset($usuario) ? $usuario->email  : '';
    ?>

    <?= form_open() ?>
        <label>Nombre:
            <input type="text" name="nombre" value="<?= set_value('nombre', $nombre) ?>">
        </label>
        <label>Email:
            <input type="email" name="email" value="<?= set_value('email', $email) ?>">
        </label>
        <button type="submit"><?= html_escape($accion) ?></button>
        <a href="<?= site_url('usuarios') ?>">Cancelar</a>
    <?= form_close() ?>
</body>
</html>

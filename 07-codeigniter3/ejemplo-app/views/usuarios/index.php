<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Usuarios — CI3</title>
    <style>
        body { font-family: sans-serif; max-width: 700px; margin: 40px auto; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .ok { color: green; }
    </style>
</head>
<body>
    <h1>Usuarios</h1>

    <?php if ($this->session->flashdata('ok')): ?>
        <p class="ok">✅ <?= html_escape($this->session->flashdata('ok')) ?></p>
    <?php endif; ?>

    <p><a href="<?= site_url('usuarios/crear') ?>">+ Nuevo usuario</a></p>

    <table>
        <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Acciones</th></tr>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= (int) $u->id ?></td>
                <td><?= html_escape($u->nombre) ?></td>
                <td><?= html_escape($u->email) ?></td>
                <td>
                    <a href="<?= site_url('usuarios/editar/' . $u->id) ?>">Editar</a> |
                    <a href="<?= site_url('usuarios/eliminar/' . $u->id) ?>"
                       onclick="return confirm('¿Eliminar?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

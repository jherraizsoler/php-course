<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html_escape($accion) ?> usuario — CRUD CodeIgniter 3</title>
    <script>try{var t=localStorage.getItem('php-course-theme');if(t)document.documentElement.dataset.theme=t;}catch(e){}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Fira+Code&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('../../../assets/styles.css') ?>">
    <style>
        .crud { max-width: 560px; }
        .field { margin: 16px 0; }
        .field label { display: block; font: 600 .85rem 'Inter', sans-serif; color: var(--text-soft); margin-bottom: 7px; }
        .field input { width: 100%; padding: 13px 15px; font-size: 1rem; color: var(--text);
            border-radius: 12px; border: 1px solid var(--border); background: var(--surface);
            backdrop-filter: blur(10px); outline: none; transition: box-shadow .2s, border-color .2s; }
        .field input:focus { border-color: var(--accent); box-shadow: 0 0 0 4px rgba(99,102,241,.2); }
        .field input::placeholder { color: var(--text-soft); opacity: .7; }
        .form-actions { display: flex; gap: 12px; align-items: center; margin-top: 22px; flex-wrap: wrap; }
        .form-errors { color: var(--lvl-pro); font-size: .9rem; }
        .form-errors:not(:empty) { margin-bottom: 16px; padding: 12px 16px; border-radius: 12px;
            background: rgba(244,63,94,.1); border: 1px solid rgba(244,63,94,.4); }
        .form-errors p { margin: 0; }
    </style>
</head>
<body>
<div class="bg-aurora"><span></span><span></span><span></span><span></span></div>
<div class="bg-grid"></div>

<div class="wrap crud">
    <div class="topbar">
        <div class="brand"><span class="logo logo--php"><img src="<?= base_url('../../../PHP-logo.svg.png') ?>" alt="PHP"></span> CRUD · CodeIgniter 3</div>
        <button class="theme-toggle" id="themeBtn" title="Cambiar tema">🌙</button>
    </div>

    <nav style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:8px;">
        <a class="back" href="<?= base_url('../../../index.php') ?>">🏠 Inicio del curso</a>
        <a class="back" href="<?= site_url('usuarios') ?>">← Usuarios</a>
    </nav>

    <header class="hero" style="text-align:left; padding:6px 0 0">
        <span class="kicker"><span class="dot"></span> <?= html_escape($accion) ?> · usuarios</span>
        <h1 style="font-size:clamp(1.7rem,4.5vw,2.4rem)"><?= html_escape($accion) ?> usuario</h1>
    </header>

    <section class="panel" style="margin-top:18px">
        <!-- validation_errors() imprime los errores de form_validation -->
        <div class="form-errors"><?= validation_errors() ?></div>

        <?php
            // En edición rellenamos con los valores actuales; tras un POST fallido,
            // set_value() mantiene lo que el usuario había escrito.
            $nombre = isset($usuario) ? $usuario->nombre : '';
            $email  = isset($usuario) ? $usuario->email  : '';
        ?>

        <?= form_open() ?>
            <div class="field">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?= set_value('nombre', $nombre) ?>" placeholder="Ej. Jorge Herraiz">
            </div>
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" value="<?= set_value('email', $email) ?>" placeholder="jorge@example.com">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn run" style="border:none; cursor:pointer">💾 <?= html_escape($accion) ?></button>
                <a class="btn open" href="<?= site_url('usuarios') ?>" style="text-decoration:none">Cancelar</a>
            </div>
        <?= form_close() ?>
    </section>

    <footer class="footer">📁 proyectos/crud-codeigniter3/app · CodeIgniter <?= defined('CI_VERSION') ? CI_VERSION : '3' ?></footer>
</div>

<script>
    const html = document.documentElement;
    const themeBtn = document.getElementById('themeBtn');
    const refreshIcon = () => themeBtn.textContent = html.dataset.theme === 'dark' ? '☀️' : '🌙';
    refreshIcon();
    themeBtn.addEventListener('click', () => {
        html.classList.add('theme-transition');
        html.dataset.theme = html.dataset.theme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('php-course-theme', html.dataset.theme);
        refreshIcon();
        setTimeout(() => html.classList.remove('theme-transition'), 600);
    });
</script>
</body>
</html>

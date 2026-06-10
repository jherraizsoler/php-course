<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuarios — CRUD CodeIgniter 3</title>
    <script>try{var t=localStorage.getItem('php-course-theme');if(t)document.documentElement.dataset.theme=t;}catch(e){}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Fira+Code&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('../../../assets/styles.css') ?>">
    <style>
        .crud { max-width: 860px; }
        .crud-table { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 18px;
            background: var(--surface-2); border: 1px solid var(--border); border-radius: 16px;
            overflow: hidden; backdrop-filter: blur(12px); box-shadow: var(--glow); }
        .crud-table th, .crud-table td { padding: 13px 16px; text-align: left; border-bottom: 1px solid var(--border); }
        .crud-table th { font: 600 .78rem 'Space Grotesk', sans-serif; text-transform: uppercase;
            letter-spacing: .06em; color: var(--text-soft); background: rgba(124,142,255,.08); }
        .crud-table tbody tr:last-child td { border-bottom: none; }
        .crud-table tbody tr { transition: background .2s; }
        .crud-table tbody tr:hover { background: rgba(124,142,255,.06); }
        .crud-id { font-family: 'Fira Code', monospace; color: var(--text-soft); }
        .crud-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .a-link { text-decoration: none; font-weight: 600; font-size: .85rem; padding: 6px 12px;
            border-radius: 9px; border: 1px solid var(--border); color: var(--link); transition: all .2s; white-space: nowrap; }
        .a-link:hover { border-color: var(--border-strong); background: var(--surface); }
        .a-link.danger { color: var(--lvl-pro); }
        .a-link.danger:hover { background: rgba(244,63,94,.12); border-color: var(--lvl-pro); }
        .flash-ok { display: flex; align-items: center; gap: 9px; margin-top: 16px; padding: 12px 16px;
            border-radius: 12px; background: rgba(16,185,129,.12); border: 1px solid rgba(16,185,129,.4);
            color: var(--lvl-base); font-weight: 600; }
        .empty-row td { text-align: center; color: var(--text-soft); padding: 26px; }
        .export-bar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 20px; }
        .export-bar .label { font-size: .82rem; font-weight: 600; color: var(--text-soft); margin-right: 2px; }
        .export-bar .btn { padding: 8px 14px; font-size: .85rem; }
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

    <div class="topbar" style="padding-top:0">
        <nav style="display:flex; gap:10px; flex-wrap:wrap;">
            <a class="back" href="<?= base_url('../../../index.php') ?>">🏠 Inicio del curso</a>
            <a class="back" href="<?= site_url('dashboard') ?>">📊 Dashboard</a>
            <a class="back" href="<?= base_url('../index.php') ?>">📖 Guía</a>
        </nav>
        <a class="btn run" href="<?= site_url('usuarios/crear') ?>" style="text-decoration:none">+ Nuevo usuario</a>
    </div>

    <header class="hero" style="text-align:left; padding:8px 0 0">
        <h1 style="font-size:clamp(1.8rem,5vw,2.6rem)">Usuarios</h1>
        <p style="margin-top:6px">Gestión con Query Builder, validación y <em>flashdata</em> de CodeIgniter 3.</p>
    </header>

    <?php if ($this->session->flashdata('ok')): ?>
        <div class="flash-ok">✅ <?= html_escape($this->session->flashdata('ok')) ?></div>
    <?php endif; ?>

    <div class="export-bar">
        <span class="label">⬇️ Exportar listado:</span>
        <a class="btn open" href="<?= site_url('export/csv') ?>">📄 CSV</a>
        <a class="btn open" href="<?= site_url('export/xlsx') ?>">📊 Excel</a>
        <a class="btn open" href="<?= site_url('export/pdf') ?>">📕 PDF</a>
    </div>

    <table class="crud-table">
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Acciones</th></tr>
        </thead>
        <tbody>
        <?php if (empty($usuarios)): ?>
            <tr class="empty-row"><td colspan="4">No hay usuarios todavía. ¡Crea el primero! 👆</td></tr>
        <?php else: foreach ($usuarios as $u): ?>
            <tr>
                <td class="crud-id">#<?= (int) $u->id ?></td>
                <td><?= html_escape($u->nombre) ?></td>
                <td><?= html_escape($u->email) ?></td>
                <td>
                    <div class="crud-actions">
                        <a class="a-link" href="<?= site_url('usuarios/editar/' . $u->id) ?>">✏️ Editar</a>
                        <a class="a-link danger" href="<?= site_url('usuarios/eliminar/' . $u->id) ?>"
                           onclick="return confirm('¿Eliminar a <?= html_escape($u->nombre) ?>?')">🗑️ Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>

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

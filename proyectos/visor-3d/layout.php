<?php

declare(strict_types=1);

/**
 * Layout común del visor. Mismo "chrome" que el resto del curso:
 * tema claro/oscuro sin FOUC, topbar con marca y navegación a Proyectos.
 *
 * @param string $titulo     Título de la pestaña.
 * @param string $contenido  HTML ya renderizado del cuerpo.
 * @param string $extraHead  HTML extra para el <head> (importmap, estilos del visor…).
 */
function layout(string $titulo, string $contenido, string $extraHead = ''): void
{
    $APP = 'Visor 3D';
    ?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?> — <?= e($APP) ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🧊</text></svg>">
    <script>try{var t=localStorage.getItem('php-course-theme');if(t)document.documentElement.dataset.theme=t;}catch(e){}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Fira+Code&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/styles.css">
    <style>
        .v3d { max-width: 1120px; margin: 0 auto; }
        .field { margin: 14px 0; }
        .field label { display:block; font:600 .85rem 'Inter',sans-serif; color:var(--text-soft); margin-bottom:7px; }
        .field input, .field textarea, .field select { width:100%; padding:12px 14px; font-size:1rem; color:var(--text);
            border-radius:12px; border:1px solid var(--border); background:var(--surface); outline:none;
            transition:box-shadow .2s, border-color .2s; font-family:inherit; }
        .field input:focus, .field textarea:focus, .field select:focus { border-color:var(--accent); box-shadow:0 0 0 4px rgba(99,102,241,.2); }
        .alert { padding:12px 16px; border-radius:12px; font-size:.9rem; font-weight:600; margin-bottom:14px; }
        .alert.err { background:rgba(244,63,94,.12); border:1px solid rgba(244,63,94,.4); color:var(--lvl-pro); }
        .alert.ok  { background:rgba(16,185,129,.12); border:1px solid rgba(16,185,129,.4); color:var(--lvl-base); }
        .meta { color:var(--text-soft); font-size:.82rem; display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
        .fmt-chip { font-size:.7rem; font-weight:700; padding:3px 9px; border-radius:999px; text-transform:uppercase;
            letter-spacing:.06em; background:rgba(124,142,255,.14); border:1px solid var(--border); color:var(--accent-ink); }
        .fmt-chip.vol { background:rgba(244,63,94,.13); color:var(--lvl-pro); }
        .viewer { width:100%; height:min(70vh,620px); border-radius:16px; border:1px solid var(--border);
            background:radial-gradient(120% 120% at 50% 0%, rgba(124,142,255,.08), transparent 60%), var(--surface);
            overflow:hidden; position:relative; }
        .viewer canvas { display:block; width:100%; height:100%; outline:none; }
        .viewer .loading { position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
            color:var(--text-soft); font-weight:600; gap:10px; }
        .toolbar { display:flex; gap:10px; flex-wrap:wrap; margin:14px 0; align-items:center; }
        .bg-ctl { display:inline-flex; align-items:center; gap:8px; padding:4px 10px; border:1px solid var(--border);
            border-radius:11px; background:var(--surface); }
        .bg-ctl label { font-size:.82rem; color:var(--text-soft); font-weight:600; }
        .bg-ctl input[type=color] { width:36px; height:30px; padding:0; border:1px solid var(--border);
            border-radius:8px; background:none; cursor:pointer; }
        .bg-ctl input[type=color]::-webkit-color-swatch { border:none; border-radius:6px; }
        .bg-ctl input[type=color]::-webkit-color-swatch-wrapper { padding:2px; }
        .card .thumb { height:120px; border-radius:12px; margin-bottom:12px; display:flex; align-items:center;
            justify-content:center; font-size:2.6rem; background:linear-gradient(135deg, rgba(124,142,255,.12), rgba(99,102,241,.05));
            border:1px solid var(--border); }
        table.tbl { width:100%; border-collapse:collapse; }
        table.tbl td { padding:6px 0; border-bottom:1px solid var(--border); font-size:.9rem; }
        table.tbl td:first-child { color:var(--text-soft); width:130px; }

        /* --- Usos por sector --- */
        .col2 { display:grid; grid-template-columns:1fr 1fr; gap:22px; }
        @media (max-width:620px){ .col2 { grid-template-columns:1fr; } }
        .tipo-chip { display:inline-block; font-size:.68rem; font-weight:700; padding:3px 9px; border-radius:999px;
            background:rgba(124,142,255,.14); border:1px solid var(--border); color:var(--accent-ink);
            letter-spacing:.03em; vertical-align:middle; }
        .tipo-chip.vol { background:rgba(244,63,94,.12); color:var(--lvl-pro); }
        .sector-grid { display:grid; gap:20px; margin-top:24px; grid-template-columns:repeat(auto-fill,minmax(380px,1fr)); }
        @media (max-width:520px){ .sector-grid { grid-template-columns:1fr; } }
        .sector-card { border:1px solid var(--border); border-radius:18px; padding:22px; background:var(--surface);
            position:relative; overflow:hidden; transition:border-color .25s, box-shadow .25s, transform .25s; }
        .sector-card::before { content:""; position:absolute; inset:0 0 auto 0; height:3px;
            background:linear-gradient(90deg,var(--accent),var(--accent-2)); opacity:.8; }
        .sector-card:hover { border-color:var(--border-strong); box-shadow:var(--glow-hover); transform:translateY(-3px); }
        .sector-head { display:flex; align-items:center; gap:14px; margin-bottom:10px; }
        .sector-ico { font-size:2.2rem; line-height:1; flex:none; filter:drop-shadow(0 4px 12px rgba(124,142,255,.35)); }
        .sector-head h3 { font-family:'Space Grotesk',sans-serif; font-size:1.18rem; margin:0 0 4px; }
        .sector-resumen { color:var(--text-soft); font-size:.92rem; margin:0 0 16px; }
        .col-title { font-size:.82rem; font-weight:700; color:var(--text); margin:0 0 8px; letter-spacing:.02em; }
        .ulist { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:7px; }
        .ulist li { position:relative; padding-left:20px; font-size:.86rem; color:var(--text-soft); line-height:1.35; }
        .ulist li::before { content:"✓"; position:absolute; left:0; top:0; color:var(--lvl-base); font-weight:700; }
        .ulist.metric li::before { content:"▸"; color:var(--accent-ink); }
        .sector-foot { display:flex; align-items:center; gap:7px; flex-wrap:wrap; margin-top:18px;
            padding-top:14px; border-top:1px solid var(--border); }

        /* --- Métricas en vivo del visor --- */
        .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(120px,1fr)); gap:12px; margin:4px 0 2px; }
        .stat { border:1px solid var(--border); border-radius:14px; padding:12px 14px; background:var(--surface); }
        .stat .v { font-family:'Space Grotesk',sans-serif; font-size:1.25rem; font-weight:700; color:var(--text); }
        .stat .k { font-size:.72rem; color:var(--text-soft); margin-top:2px; letter-spacing:.03em; }
        .stat .v.dim { font-size:1rem; }
        .stat.pending .v { color:var(--text-soft); opacity:.5; }
    </style>
    <?= $extraHead ?>
</head>
<body>
<div class="bg-aurora"><span></span><span></span><span></span><span></span></div>
<div class="bg-grid"></div>

<div class="wrap v3d">
    <div class="topbar">
        <div class="brand"><span class="logo">🧊</span> <?= e($APP) ?></div>
        <button class="theme-toggle" id="themeBtn" title="Cambiar tema">🌙</button>
    </div>

    <nav style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:6px;">
        <a class="back" href="../../index.php">🏠 Inicio del curso</a>
        <a class="back" href="../../modulo.php?m=proyectos">← Proyectos</a>
        <a class="back" href="index.php">📚 Catálogo</a>
        <a class="back" href="index.php?action=usos">🎯 Usos por sector</a>
    </nav>

    <?= $contenido ?>

    <footer class="footer">🧊 proyectos/visor-3d · Three.js (mallas) + vtk.js (volumétrico) + PHP (catálogo)</footer>
</div>

<script>
    const html = document.documentElement, themeBtn = document.getElementById('themeBtn');
    const refreshIcon = () => themeBtn.textContent = html.dataset.theme === 'dark' ? '☀️' : '🌙';
    refreshIcon();
    themeBtn.addEventListener('click', () => {
        html.classList.add('theme-transition');
        html.dataset.theme = html.dataset.theme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('php-course-theme', html.dataset.theme); refreshIcon();
        setTimeout(() => html.classList.remove('theme-transition'), 600);
    });
</script>
</body>
</html>
    <?php
}

<?php
/**
 * Visor de la GUÍA de este proyecto.
 * crud-codeigniter3 no es una app pre-montada: es una guía paso a paso para
 * construir el CRUD con CodeIgniter 3. Este index.php renderiza el README.md
 * con el mismo estilo del curso (en vez del feo listado de directorio de Apache).
 */

declare(strict_types=1);

$readme = is_file(__DIR__ . '/README.md')
    ? file_get_contents(__DIR__ . '/README.md')
    : '# (Sin README)';
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>🏗️ CRUD en CodeIgniter 3 — PHP Course</title>
    <script>try{var t=localStorage.getItem('php-course-theme');if(t)document.documentElement.dataset.theme=t;}catch(e){}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Fira+Code&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
</head>
<body>
<div class="bg-aurora"><span></span><span></span><span></span><span></span></div>
<div class="bg-grid"></div>

<div class="wrap">
    <div class="topbar">
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a class="back" href="../../index.php">🏠 Inicio</a>
            <a class="back" href="../../modulo.php?m=proyectos">← Proyectos</a>
        </div>
        <button class="theme-toggle" id="themeBtn" title="Cambiar tema">🌙</button>
    </div>

    <header class="hero" style="padding:18px 0 6px">
        <span class="kicker"><span class="dot"></span> Proyecto · App CodeIgniter 3 montada</span>
        <h1 style="font-size:clamp(1.7rem,4.5vw,2.6rem)">🏗️ CRUD en CodeIgniter 3</h1>
        <p>La app <strong>ya está montada y funcionando</strong> en la carpeta <code>app/</code>.
           Ábrela en vivo o lee abajo la guía de cómo se construye paso a paso.</p>
        <div class="chips" style="margin-top:18px">
            <a class="btn run" href="app/index.php" style="text-decoration:none">▶ Abrir la app en vivo</a>
            <a class="btn open" href="#guia" style="text-decoration:none">📖 Ver la guía</a>
        </div>
    </header>

    <section class="panel" id="guia" style="margin-top:18px">
        <div class="lesson" id="lesson">Cargando guía…</div>
    </section>

    <footer class="footer">📁 proyectos/crud-codeigniter3 · <a href="../../modulo.php?m=proyectos">volver a proyectos</a></footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/12.0.0/marked.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
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
        window.setTimeout(() => html.classList.remove('theme-transition'), 600);
    });

    const md = <?= json_encode($readme, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const lessonEl = document.getElementById('lesson');
    if (window.marked) {
        lessonEl.innerHTML = marked.parse(md);
        if (window.hljs) document.querySelectorAll('#lesson pre code').forEach(b => hljs.highlightElement(b));
    } else {
        lessonEl.textContent = md;
    }
</script>
</body>
</html>

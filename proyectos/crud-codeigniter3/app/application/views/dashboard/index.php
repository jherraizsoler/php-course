<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — CRUD CodeIgniter 3</title>
    <script>try{var t=localStorage.getItem('php-course-theme');if(t)document.documentElement.dataset.theme=t;}catch(e){}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Fira+Code&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('../../../assets/styles.css') ?>">
    <style>
        .crud { max-width: 980px; }
        .metrics { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-top: 8px; }
        .metric { background: var(--surface-2); border: 1px solid var(--border); border-radius: 16px;
            padding: 20px; backdrop-filter: blur(12px); box-shadow: var(--glow); }
        .metric .n { font: 700 2.2rem 'Space Grotesk', sans-serif;
            background: linear-gradient(120deg, var(--accent), var(--accent-3)); -webkit-background-clip: text;
            background-clip: text; -webkit-text-fill-color: transparent; }
        .metric .l { color: var(--text-soft); font-size: .85rem; font-weight: 600; margin-top: 2px; }
        .charts { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 18px; margin-top: 20px; }
        .chart-card { background: var(--surface-2); border: 1px solid var(--border); border-radius: 18px;
            padding: 20px 22px; backdrop-filter: blur(12px); box-shadow: var(--glow); }
        .chart-card h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1.05rem; margin-bottom: 12px; }
        .chart-card .tag { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
            color: var(--accent-ink); }
        .gd-card img { width: 100%; height: auto; border-radius: 12px; border: 1px solid var(--border); display: block; }
        .gd-note { color: var(--text-soft); font-size: .82rem; margin-top: 10px; }
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
        <a class="back" href="<?= site_url('usuarios') ?>">👥 Usuarios</a>
        <a class="back" href="<?= base_url('../index.php') ?>">📖 Guía</a>
    </nav>

    <header class="hero" style="text-align:left; padding:8px 0 0">
        <span class="kicker"><span class="dot"></span> Métricas · GD + Chart.js</span>
        <h1 style="font-size:clamp(1.8rem,5vw,2.6rem)">Dashboard</h1>
        <p style="margin-top:6px">Datos de la tabla <code>usuarios</code>, servidos por PHP y dibujados de dos formas.</p>
    </header>

    <section class="metrics">
        <div class="metric"><div class="n"><?= (int) $total ?></div><div class="l">👥 Usuarios totales</div></div>
        <div class="metric"><div class="n"><?= count($porDominio) ?></div><div class="l">🌐 Dominios únicos</div></div>
        <div class="metric"><div class="n"><?= count($porMes) ?></div><div class="l">🗓️ Meses con altas</div></div>
    </section>

    <section class="charts">
        <div class="chart-card">
            <span class="tag">Cliente · Chart.js</span>
            <h3>Usuarios por dominio</h3>
            <canvas id="chartDominio" height="220"></canvas>
        </div>
        <div class="chart-card">
            <span class="tag">Cliente · Chart.js</span>
            <h3>Altas por mes</h3>
            <canvas id="chartMes" height="220"></canvas>
        </div>
    </section>

    <section class="charts">
        <div class="chart-card gd-card">
            <span class="tag">Servidor · GD (imagen PNG)</span>
            <h3>La misma gráfica, generada en el servidor</h3>
            <img src="<?= site_url('dashboard/png') ?>" alt="Gráfica generada con GD en el servidor">
            <p class="gd-note">Esta imagen la dibuja PHP con la extensión <strong>GD</strong> y se sirve como <code>image/png</code> — sin JavaScript. Útil para incrustar en PDF o emails.</p>
        </div>
    </section>

    <footer class="footer">📁 proyectos/crud-codeigniter3/app · Dashboard de ejemplo</footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
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

    // Datos servidos por PHP
    const POR_DOMINIO = <?= json_encode($porDominio, JSON_UNESCAPED_UNICODE) ?>;
    const POR_MES     = <?= json_encode($porMes, JSON_UNESCAPED_UNICODE) ?>;

    if (window.Chart) {
        Chart.defaults.color = '#95a0c8';
        Chart.defaults.font.family = "'Inter', sans-serif";
        const grid = 'rgba(124,142,255,.12)';

        new Chart(document.getElementById('chartDominio'), {
            type: 'bar',
            data: { labels: Object.keys(POR_DOMINIO), datasets: [{
                label: 'Usuarios', data: Object.values(POR_DOMINIO),
                backgroundColor: 'rgba(99,102,241,.75)', borderRadius: 8, borderSkipped: false }] },
            options: { plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: grid } }, x: { grid: { display: false } } } }
        });

        new Chart(document.getElementById('chartMes'), {
            type: 'line',
            data: { labels: Object.keys(POR_MES), datasets: [{
                label: 'Altas', data: Object.values(POR_MES),
                borderColor: '#22d3ee', backgroundColor: 'rgba(34,211,238,.18)',
                fill: true, tension: .35, pointBackgroundColor: '#22d3ee' }] },
            options: { plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: grid } }, x: { grid: { display: false } } } }
        });
    }
</script>
</body>
</html>

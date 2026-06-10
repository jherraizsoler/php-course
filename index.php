<?php
/**
 * Portada del curso — dashboard futurista.
 * Sírvelo con MAMP:  http://localhost:8888/php-course/
 */

declare(strict_types=1);

$modulos = require __DIR__ . '/_modulos.php';

function e(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>🐘 PHP Course — De 0 a 100</title>
    <script>try{var t=localStorage.getItem('php-course-theme');if(t)document.documentElement.dataset.theme=t;}catch(e){}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Fira+Code&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<!-- ============ INTRO DE BIENVENIDA (splash cinematográfico) ============ -->
<div class="intro" id="intro">
    <div class="intro-aurora"><span></span><span></span><span></span></div>
    <div class="intro-grid"></div>
    <button class="intro-skip" id="introSkip" type="button">Saltar intro ✕</button>

    <div class="intro-stage">
        <div class="intro-php">
            <img src="PHP-logo.svg.png" alt="PHP" class="intro-php-logo">
        </div>

        <div class="intro-card">
            <div class="intro-avatar">
                <span class="intro-ring"></span>
                <span class="intro-ring intro-ring--2"></span>
                <img src="logo_jorge.png" alt="Jorge Herraiz" class="intro-photo">
            </div>
            <div class="intro-text">
                <h1 class="intro-name" data-text="Jorge Herraiz Soler">Jorge Herraiz Soler</h1>
                <span class="intro-line"></span>
                <p class="intro-role">Desarrollador FullStack IA</p>
                <p class="intro-tag">PHP Course — De&nbsp;0&nbsp;a&nbsp;100</p>

                <div class="intro-social">
                    <a class="s-btn s-btn--linkedin" href="https://www.linkedin.com/in/jorgeherraizsoler/"
                       target="_blank" rel="noopener" aria-label="LinkedIn de Jorge Herraiz Soler">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.03-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.45v6.29zM5.34 7.43a2.07 2.07 0 1 1 0-4.14 2.07 2.07 0 0 1 0 4.14zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .78 0 1.73v20.54C0 23.22.79 24 1.77 24h20.45c.98 0 1.78-.78 1.78-1.73V1.73C24 .78 23.2 0 22.22 0z"/></svg>
                        <span>LinkedIn</span>
                    </a>
                    <a class="s-btn s-btn--portfolio" href="https://jherraizsoler.github.io/portfolio/"
                       target="_blank" rel="noopener" aria-label="Portfolio de Jorge Herraiz Soler">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18z"/></svg>
                        <span>Portfolio</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="intro-shine"></div>
</div>
<!-- ====================================================================== -->

<div class="bg-aurora"><span></span><span></span><span></span><span></span></div>
<div class="bg-grid"></div>

<div class="wrap">

    <div class="topbar">
        <div class="brand"><span class="logo logo--php"><img src="PHP-logo.svg.png" alt="PHP"></span> PHP&nbsp;Course</div>
        <div class="topbar-right">
            <a class="author-chip" href="https://jherraizsoler.github.io/portfolio/" target="_blank" rel="noopener"
               title="Portfolio de Jorge Herraiz Soler">
                <img class="author-logo" src="logo_jorge.png" alt="Jorge Herraiz Soler">
                <span class="author-meta">
                    <strong>Jorge Herraiz Soler</strong>
                    <small>Desarrollador FullStack IA</small>
                </span>
            </a>
            <button class="theme-toggle" id="themeBtn" title="Cambiar tema">🌙</button>
        </div>
    </div>

    <header class="hero">
        <span class="kicker"><span class="dot"></span> Entorno listo · PHP <?= e(PHP_VERSION) ?></span>
        <h1>Domina PHP<br>&amp; CodeIgniter</h1>
        <p>De 0 a 100, con buenas prácticas. Tu campo de pruebas personal.</p>
        <div class="chips">
            <span class="chip">⚡ CodeIgniter 3</span>
            <span class="chip">🗄️ MySQL · PDO</span>
            <span class="chip">📦 Composer</span>
            <span class="chip">🧪 Ejecuta y prueba en vivo</span>
        </div>

        <div class="hero-cta">
            <a class="btn run" href="modulo.php?m=proyectos">🏗️ Ir a Proyectos prácticos →</a>
            <a class="btn open" href="#grid">🗺️ Ver todos los módulos</a>
        </div>

        <div class="progress-wrap">
            <div class="progress-meta">
                <span>Tu progreso</span>
                <span id="pcount">0 / <?= count($modulos) ?></span>
            </div>
            <div class="progress"><div class="progress-bar" id="pbar"></div></div>
        </div>
    </header>

    <div class="search-box">
        <span class="icon">🔍</span>
        <input type="text" id="search" placeholder="Busca un módulo… (ej. arrays, POO, MySQL)">
    </div>

    <main class="grid" id="grid">
        <?php foreach ($modulos as $m): ?>
            <article class="card" data-slug="<?= e($m['slug']) ?>"
                     data-search="<?= e(mb_strtolower($m['title'] . ' ' . $m['desc'] . ' ' . $m['slug'])) ?>">
                <a class="card-link" href="modulo.php?m=<?= e(rawurlencode($m['slug'])) ?>"
                   aria-label="Abrir <?= e($m['title']) ?>"></a>
                <div class="row">
                    <span class="ico"><?= $m['icon'] ?></span>
                    <span class="badge <?= e($m['level']) ?>"><?= e($m['level']) ?></span>
                </div>
                <span class="num">MÓDULO <?= e($m['num']) ?></span>
                <h3><?= e($m['title']) ?></h3>
                <p><?= e($m['desc']) ?></p>
                <div class="card-foot">
                    <span class="go">Abrir módulo</span>
                    <button class="done-btn" data-slug="<?= e($m['slug']) ?>"
                            title="Marcar como completado">✓</button>
                </div>
            </article>
        <?php endforeach; ?>
    </main>

    <p class="empty" id="empty" style="display:none">Sin resultados para tu búsqueda 🤔</p>

    <footer class="footer">
        <div class="footer-social">
            <a class="s-btn s-btn--sm s-btn--linkedin" href="https://www.linkedin.com/in/jorgeherraizsoler/"
               target="_blank" rel="noopener" aria-label="LinkedIn de Jorge Herraiz Soler">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.03-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.45v6.29zM5.34 7.43a2.07 2.07 0 1 1 0-4.14 2.07 2.07 0 0 1 0 4.14zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .78 0 1.73v20.54C0 23.22.79 24 1.77 24h20.45c.98 0 1.78-.78 1.78-1.73V1.73C24 .78 23.2 0 22.22 0z"/></svg>
                <span>LinkedIn</span>
            </a>
            <a class="s-btn s-btn--sm s-btn--portfolio" href="https://jherraizsoler.github.io/portfolio/"
               target="_blank" rel="noopener" aria-label="Portfolio de Jorge Herraiz Soler">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18z"/></svg>
                <span>Portfolio</span>
            </a>
        </div>
        <p>Hecho por <strong>Jorge Herraiz Soler</strong> · repositorio personal de estudio · edita, rompe y experimenta sin miedo 🚀</p>
    </footer>
</div>

<script>
    const html = document.documentElement;

    /* --- Intro de bienvenida (splash) --- */
    (function () {
        const intro = document.getElementById('intro');
        if (!intro) return;
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const alreadySeen = sessionStorage.getItem('php-course-intro') === '1';

        // Cierra la intro: la desvanece y la quita del DOM.
        let closed = false;
        function closeIntro(instant) {
            if (closed) return;
            closed = true;
            sessionStorage.setItem('php-course-intro', '1');
            html.classList.remove('intro-lock');
            if (instant) { intro.remove(); return; }
            intro.classList.add('intro--out');
            setTimeout(() => intro.remove(), 800);
        }

        // Si ya se vio en esta sesión o el usuario prefiere menos movimiento → sin animación.
        if (reduceMotion || alreadySeen) {
            closeIntro(true);
        } else {
            html.classList.add('intro-lock');         // bloquea el scroll durante la intro
            const TIMELINE = 7600;                     // duración total antes del fundido
            const timer = setTimeout(() => closeIntro(false), TIMELINE);
            document.getElementById('introSkip')
                .addEventListener('click', () => { clearTimeout(timer); closeIntro(false); });
        }
    })();

    /* --- Tema claro/oscuro --- */
    const themeBtn = document.getElementById('themeBtn');
    const savedTheme = localStorage.getItem('php-course-theme');
    if (savedTheme) html.dataset.theme = savedTheme;
    const refreshIcon = () => themeBtn.textContent = html.dataset.theme === 'dark' ? '☀️' : '🌙';
    refreshIcon();
    themeBtn.addEventListener('click', () => {
        html.classList.add('theme-transition');               // cross-fade suave de colores
        html.dataset.theme = html.dataset.theme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('php-course-theme', html.dataset.theme);
        refreshIcon();
        window.setTimeout(() => html.classList.remove('theme-transition'), 600);
    });

    /* --- Progreso (módulos completados, guardado en el navegador) --- */
    const cards = [...document.querySelectorAll('.card')];
    const total = cards.length;
    const pbar = document.getElementById('pbar');
    const pcount = document.getElementById('pcount');
    let done = new Set(JSON.parse(localStorage.getItem('php-course-done') || '[]'));

    function renderProgress() {
        cards.forEach(c => c.classList.toggle('is-done', done.has(c.dataset.slug)));
        const n = done.size;
        pcount.textContent = n + ' / ' + total;
        pbar.style.width = (total ? (n / total * 100) : 0) + '%';
    }
    renderProgress();

    document.querySelectorAll('.done-btn').forEach(btn => {
        btn.addEventListener('click', (ev) => {
            ev.preventDefault(); ev.stopPropagation();
            const slug = btn.dataset.slug;
            done.has(slug) ? done.delete(slug) : done.add(slug);
            localStorage.setItem('php-course-done', JSON.stringify([...done]));
            renderProgress();
        });
    });

    /* --- Buscador en vivo --- */
    const search = document.getElementById('search');
    const empty = document.getElementById('empty');
    search.addEventListener('input', () => {
        const q = search.value.trim().toLowerCase();
        let vis = 0;
        cards.forEach(c => {
            const match = c.dataset.search.includes(q);
            c.style.display = match ? '' : 'none';
            if (match) vis++;
        });
        empty.style.display = vis === 0 ? 'block' : 'none';
    });

    /* --- Tilt 3D + brillo siguiendo al cursor --- */
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!reduce) {
        cards.forEach(card => {
            card.addEventListener('pointermove', (e) => {
                const r = card.getBoundingClientRect();
                const px = (e.clientX - r.left) / r.width;
                const py = (e.clientY - r.top) / r.height;
                card.style.transform =
                    `rotateY(${(px - .5) * 9}deg) rotateX(${(.5 - py) * 9}deg) translateY(-6px)`;
                card.style.setProperty('--mx', (px * 100) + '%');
                card.style.setProperty('--my', (py * 100) + '%');
            });
            card.addEventListener('pointerleave', () => { card.style.transform = ''; });
        });
    }
</script>
</body>
</html>

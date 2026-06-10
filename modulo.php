<?php
/**
 * Vista de un módulo: renderiza su README.md como lección bonita
 * y lista los ejemplos ejecutables con botones "▶ Ejecutar" / "↗ Abrir".
 */

declare(strict_types=1);

$modulos = require __DIR__ . '/_modulos.php';

function e(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

// --- Validar el módulo pedido contra la lista (evita acceso arbitrario) ---
$slug = $_GET['m'] ?? '';
$modulo = null;
foreach ($modulos as $m) {
    if ($m['slug'] === $slug) {
        $modulo = $m;
        break;
    }
}
if ($modulo === null) {
    http_response_code(404);
    exit('Módulo no encontrado. <a href="index.php">Volver</a>');
}

$dir = __DIR__ . DIRECTORY_SEPARATOR . $slug;

// --- Cargar el README.md ---
$readmePath = $dir . DIRECTORY_SEPARATOR . 'README.md';
$readme = is_file($readmePath) ? file_get_contents($readmePath) : '# (Sin README)';

// --- Buscar ejemplos ejecutables ---
// Los módulos "web" son páginas (forms, sesiones, PDO): hay que ABRIRLAS en el
// navegador, no ejecutarlas por CLI (en CLI no existe $_SERVER y dan warnings).
$esWeb = in_array($slug, ['05-php-web', 'proyectos'], true);

$ejemplos = [];
foreach (['ejemplos', 'ejercicios/soluciones'] as $sub) {
    $patron = $dir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $sub) . DIRECTORY_SEPARATOR . '*.php';
    foreach (glob($patron) ?: [] as $f) {
        $ejemplos[] = ['rel' => $slug . '/' . $sub . '/' . basename($f), 'name' => $sub . '/' . basename($f), 'web' => $esWeb];
    }
}
if ($slug === 'proyectos' && is_file($dir . '/crud-php-puro/index.php')) {
    $ejemplos[] = ['rel' => 'proyectos/crud-php-puro/index.php', 'name' => 'crud-php-puro/index.php (app web)', 'web' => true];
}
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($modulo['icon'] . ' ' . $modulo['title']) ?> — PHP Course</title>
    <script>try{var t=localStorage.getItem('php-course-theme');if(t)document.documentElement.dataset.theme=t;}catch(e){}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Fira+Code&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
</head>
<body>
<div class="bg-aurora"><span></span><span></span><span></span><span></span></div>
<div class="bg-grid"></div>

<div class="wrap">

    <div class="topbar">
        <a class="back" href="index.php">← Todos los módulos</a>
        <button class="theme-toggle" id="themeBtn" title="Cambiar tema">🌙</button>
    </div>

    <header class="hero" style="padding:18px 0 8px">
        <span class="kicker"><span class="dot"></span> Módulo <?= e($modulo['num']) ?> · <?= e($modulo['level']) ?></span>
        <h1 style="font-size:clamp(1.7rem,4.5vw,2.8rem)"><?= $modulo['icon'] ?> <?= e($modulo['title']) ?></h1>
        <div class="module-head" style="margin-top:14px">
            <button class="btn-complete" id="completeBtn" data-slug="<?= e($slug) ?>">✓ Marcar como completado</button>
        </div>
    </header>

    <?php if ($ejemplos): ?>
    <section class="runner">
        <h2>⚡ Ejemplos ejecutables</h2>
        <p class="hint">
            <?php if ($esWeb): ?>
                Estos ejemplos son <strong>páginas web</strong>: usa <strong>↗ Abrir</strong> para verlos en el navegador.
            <?php else: ?>
                Pulsa <strong>▶ Ejecutar</strong> y verás la salida real del script aquí mismo.
            <?php endif; ?>
        </p>

        <?php foreach ($ejemplos as $i => $ej): ?>
            <div class="example">
                <div class="head">
                    <span class="fname"><?= e($ej['name']) ?></span>
                    <?php if (empty($ej['web'])): ?>
                        <button class="btn run" data-file="<?= e($ej['rel']) ?>" data-target="out<?= $i ?>">▶ Ejecutar</button>
                    <?php endif; ?>
                    <a class="btn open" href="<?= e($ej['rel']) ?>" target="_blank" rel="noopener">↗ Abrir</a>
                </div>
                <div class="output" id="out<?= $i ?>"></div>
            </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>

    <section class="panel" style="margin-top:28px">
        <div class="lesson" id="lesson">Cargando lección…</div>
    </section>

    <footer class="footer">📁 <?= e($slug) ?> · <a href="index.php">volver al índice</a></footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/12.0.0/marked.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script>
    const html = document.documentElement;

    /* --- Tema --- */
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

    /* --- Marcar módulo como completado (mismo almacén que la portada) --- */
    const completeBtn = document.getElementById('completeBtn');
    const slug = completeBtn.dataset.slug;
    let done = new Set(JSON.parse(localStorage.getItem('php-course-done') || '[]'));
    const refreshComplete = () => {
        const ok = done.has(slug);
        completeBtn.classList.toggle('done', ok);
        completeBtn.textContent = ok ? '✓ Completado' : '✓ Marcar como completado';
    };
    refreshComplete();
    completeBtn.addEventListener('click', () => {
        done.has(slug) ? done.delete(slug) : done.add(slug);
        localStorage.setItem('php-course-done', JSON.stringify([...done]));
        refreshComplete();
    });

    /* --- Renderizar el README (markdown) --- */
    const md = <?= json_encode($readme, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const lessonEl = document.getElementById('lesson');

    /* Reescribe los enlaces relativos del README para que funcionen en la web.
       El README vive en <?= e($slug) ?>/ pero se renderiza desde modulo.php (en la raíz),
       así que hay que resolver las rutas relativas respecto a esa carpeta. */
    const MODS = <?= json_encode(array_column($modulos, 'slug'), JSON_UNESCAPED_SLASHES) ?>;
    function resolveRel(base, rel) {
        const out = [];
        for (const p of (base + '/' + rel).split('/')) {
            if (p === '' || p === '.') continue;
            if (p === '..') out.pop(); else out.push(p);
        }
        return out.join('/');
    }
    function rewriteLessonLinks() {
        lessonEl.querySelectorAll('a[href]').forEach(a => {
            const raw = a.getAttribute('href');
            if (!raw || /^(?:[a-z][a-z0-9+.-]*:|\/\/|\/|#)/i.test(raw)) return; // externos, absolutos, anclas
            const resolved = resolveRel(slug, raw);                             // ruta relativa a la raíz del repo
            if (/(^|\/)README\.md$/i.test(resolved)) {
                const target = resolved.replace(/\/?README\.md$/i, '');
                if (target === '') a.setAttribute('href', 'index.php');                 // ../README.md → portada
                else if (MODS.includes(target)) a.setAttribute('href', 'modulo.php?m=' + encodeURIComponent(target));
                else a.setAttribute('href', resolved);
            } else if (MODS.includes(resolved)) {
                a.setAttribute('href', 'modulo.php?m=' + encodeURIComponent(resolved)); // enlace a carpeta de otro módulo
            } else {
                a.setAttribute('href', resolved);                                       // archivo/carpeta dentro del curso
            }
        });
    }
    if (window.marked) {
        lessonEl.innerHTML = marked.parse(md);
        rewriteLessonLinks();
        if (window.hljs) document.querySelectorAll('#lesson pre code').forEach(b => hljs.highlightElement(b));
    } else {
        lessonEl.innerHTML = '<pre>' + md.replace(/[&<>]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[c])) + '</pre>';
    }

    /* --- Ejecutar ejemplos (AJAX a run.php) --- */
    document.querySelectorAll('.btn.run').forEach(btn => {
        btn.addEventListener('click', async () => {
            const out = document.getElementById(btn.dataset.target);
            out.classList.add('show');
            out.innerHTML = '<span class="spinner"></span> Ejecutando…';
            const original = btn.innerHTML;
            btn.disabled = true; btn.innerHTML = '<span class="spinner"></span> …';
            try {
                const res = await fetch('run.php?file=' + encodeURIComponent(btn.dataset.file));
                const text = await res.text();
                const cls = res.ok ? 'ok' : 'err';
                const head = res.ok ? '✅ Salida:' : '⚠️ Error:';
                out.innerHTML = '<span class="' + cls + '">' + head + '</span>\n\n' +
                    text.replace(/[&<>]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[c]));
            } catch (err) {
                out.innerHTML = '<span class="err">⚠️ No se pudo ejecutar: ' + err + '</span>';
            } finally {
                btn.disabled = false; btn.innerHTML = original;
            }
        });
    });
</script>
</body>
</html>

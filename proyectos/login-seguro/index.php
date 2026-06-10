<?php
/**
 * Demo de LOGIN SEGURO — CSRF + 2FA (TOTP) + .env + rate limiting.
 *
 * Flujo:  login (usuario+contraseña, con token CSRF y límite de intentos)
 *         → 2FA (código TOTP de Google/Microsoft Authenticator)
 *         → panel protegido.
 *
 * Es una demo educativa: el usuario y el secreto 2FA viven en .env.
 * En un proyecto real irían por usuario en la base de datos.
 */

declare(strict_types=1);
session_start();

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PragmaRX\Google2FA\Google2FA;

// --- Cargar configuración desde .env (secretos fuera del código) ---
Dotenv::createImmutable(__DIR__)->safeLoad();
$env = fn(string $k, $d = null) => $_ENV[$k] ?? $d;

$APP_NAME  = (string) $env('APP_NAME', 'Login Seguro');
$DEMO_USER = (string) $env('DEMO_USER', 'jorge');
$DEMO_HASH = (string) $env('DEMO_PASS_HASH', '');
$SECRET    = (string) $env('TOTP_SECRET', '');
$ISSUER    = (string) $env('TOTP_ISSUER', 'PHP Course');
$MAX       = (int) $env('RATE_LIMIT_MAX', 5);
$LOCK      = (int) $env('RATE_LIMIT_LOCK', 60);

$g2fa = new Google2FA();

function e($v): string { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }

// --- CSRF: un token por sesión ---
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf_ok = fn($t) => is_string($t) && hash_equals($_SESSION['csrf'], $t);

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$error  = '';

// --- Logout ---
if ($action === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

// --- Rate limiting: cuenta de fallos recientes en sesión ---
$now = time();
$_SESSION['fails'] = array_values(array_filter(
    $_SESSION['fails'] ?? [],
    fn($t) => $t > $now - $LOCK
));
$locked    = count($_SESSION['fails']) >= $MAX;
$lock_left = $locked ? ($LOCK - ($now - (int) min($_SESSION['fails']))) : 0;

// --- POST: paso 1 (usuario + contraseña) ---
if ($method === 'POST' && ($_POST['paso'] ?? '') === 'login') {
    if (! $csrf_ok($_POST['csrf'] ?? '')) {
        $error = 'Token CSRF inválido. Recarga la página.';
    } elseif ($locked) {
        $error = "Demasiados intentos fallidos. Espera {$lock_left}s.";
    } else {
        $u = trim((string) ($_POST['usuario'] ?? ''));
        $p = (string) ($_POST['password'] ?? '');
        if ($u === $DEMO_USER && $DEMO_HASH !== '' && password_verify($p, $DEMO_HASH)) {
            $_SESSION['pre_2fa'] = true;       // contraseña OK → falta el 2FA
            $_SESSION['fails']   = [];
            header('Location: index.php?action=2fa');
            exit;
        }
        $_SESSION['fails'][] = $now;
        $left  = max(0, $MAX - count($_SESSION['fails']));
        $error = "Credenciales incorrectas. Intentos restantes: {$left}.";
    }
}

// --- POST: paso 2 (código 2FA / TOTP) ---
if ($method === 'POST' && ($_POST['paso'] ?? '') === '2fa') {
    if (! $csrf_ok($_POST['csrf'] ?? '')) {
        $error = 'Token CSRF inválido.';
    } elseif (empty($_SESSION['pre_2fa'])) {
        header('Location: index.php');
        exit;
    } else {
        $code = preg_replace('/\D/', '', (string) ($_POST['codigo'] ?? ''));
        if ($SECRET !== '' && $g2fa->verifyKey($SECRET, $code)) {
            $_SESSION['auth'] = true;
            unset($_SESSION['pre_2fa']);
            session_regenerate_id(true);       // evita fijación de sesión
            $_SESSION['auth'] = true;
            header('Location: index.php?action=panel');
            exit;
        }
        $error = 'Código 2FA incorrecto o caducado. Prueba con el código actual de tu app.';
    }
}

// --- Estado actual ---
$logged   = ! empty($_SESSION['auth']);
$en_2fa   = ! empty($_SESSION['pre_2fa']) && ! $logged;
$otpauth  = $g2fa->getQRCodeUrl($ISSUER, $DEMO_USER, $SECRET);

/* ---------------------------------------------------------------- LAYOUT */
function layout(string $titulo, string $contenido): void
{
    global $APP_NAME;
    ?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?> — <?= e($APP_NAME) ?></title>
    <script>try{var t=localStorage.getItem('php-course-theme');if(t)document.documentElement.dataset.theme=t;}catch(e){}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Fira+Code&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/styles.css">
    <style>
        .auth { max-width: 460px; margin: 0 auto; }
        .auth .panel { margin-top: 18px; }
        .field { margin: 14px 0; }
        .field label { display:block; font:600 .85rem 'Inter',sans-serif; color:var(--text-soft); margin-bottom:7px; }
        .field input { width:100%; padding:13px 15px; font-size:1rem; color:var(--text); border-radius:12px;
            border:1px solid var(--border); background:var(--surface); backdrop-filter:blur(10px); outline:none;
            transition:box-shadow .2s, border-color .2s; }
        .field input:focus { border-color:var(--accent); box-shadow:0 0 0 4px rgba(99,102,241,.2); }
        .code-input { letter-spacing:.5em; font-family:'Fira Code',monospace; text-align:center; font-size:1.4rem !important; }
        .alert { padding:12px 16px; border-radius:12px; font-size:.9rem; font-weight:600; margin-bottom:4px; }
        .alert.err { background:rgba(244,63,94,.12); border:1px solid rgba(244,63,94,.4); color:var(--lvl-pro); }
        .alert.ok  { background:rgba(16,185,129,.12); border:1px solid rgba(16,185,129,.4); color:var(--lvl-base); }
        .hint { color:var(--text-soft); font-size:.82rem; margin-top:8px; }
        .qr-box { display:flex; gap:18px; align-items:center; flex-wrap:wrap; margin:10px 0 4px;
            padding:14px; border:1px solid var(--border); border-radius:14px; background:var(--surface); }
        .qr-box canvas { background:#fff; border-radius:10px; padding:8px; }
        .secret { font-family:'Fira Code',monospace; color:var(--accent-ink); font-size:.85rem; word-break:break-all; }
        .pills { display:flex; gap:8px; flex-wrap:wrap; margin-top:14px; }
        .pill { font-size:.72rem; font-weight:700; padding:5px 11px; border-radius:999px;
            background:rgba(124,142,255,.12); border:1px solid var(--border); color:var(--accent-ink); }
    </style>
</head>
<body>
<div class="bg-aurora"><span></span><span></span><span></span><span></span></div>
<div class="bg-grid"></div>

<div class="wrap auth">
    <div class="topbar">
        <div class="brand"><span class="logo">🔐</span> <?= e($APP_NAME) ?></div>
        <button class="theme-toggle" id="themeBtn" title="Cambiar tema">🌙</button>
    </div>

    <nav style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:6px;">
        <a class="back" href="../../index.php">🏠 Inicio del curso</a>
        <a class="back" href="../../modulo.php?m=proyectos">← Proyectos</a>
    </nav>

    <?= $contenido ?>

    <footer class="footer">🔐 proyectos/login-seguro · demo educativa de seguridad</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
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
    const qrEl = document.getElementById('qr');
    if (qrEl && window.QRCode) QRCode.toCanvas(qrEl, qrEl.dataset.url, { width: 168, margin: 1 });
</script>
</body>
</html>
    <?php
}

/* ---------------------------------------------------------------- PANTALLAS */

// Panel protegido
if ($logged) {
    ob_start(); ?>
    <header class="hero" style="text-align:left; padding:8px 0 0">
        <span class="kicker"><span class="dot"></span> Sesión verificada con 2FA</span>
        <h1 style="font-size:clamp(1.7rem,4.5vw,2.4rem)">✅ Acceso concedido</h1>
        <p style="margin-top:6px">Has superado contraseña <strong>y</strong> segundo factor. Bienvenido, <strong><?= e($DEMO_USER) ?></strong>.</p>
    </header>
    <section class="panel">
        <p>Esta página solo es accesible tras pasar las dos verificaciones. En un proyecto real aquí
           estaría el área privada (CRM, panel de gestión…).</p>
        <div class="pills">
            <span class="pill">CSRF ✓</span>
            <span class="pill">Contraseña (bcrypt) ✓</span>
            <span class="pill">2FA TOTP ✓</span>
            <span class="pill">Rate limiting ✓</span>
            <span class="pill">.env ✓</span>
        </div>
        <p style="margin-top:18px"><a class="btn run" href="index.php?action=logout" style="text-decoration:none">Cerrar sesión</a></p>
    </section>
    <?php
    layout('Panel', ob_get_clean());
    exit;
}

// Paso 2FA
if ($en_2fa) {
    ob_start(); ?>
    <header class="hero" style="text-align:left; padding:8px 0 0">
        <span class="kicker"><span class="dot"></span> Paso 2 de 2 · Doble factor</span>
        <h1 style="font-size:clamp(1.6rem,4.5vw,2.2rem)">Verificación 2FA</h1>
    </header>
    <section class="panel">
        <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>

        <p class="hint">1) Escanea este QR con <strong>Google Authenticator</strong> / <strong>Microsoft Authenticator</strong> (solo la primera vez):</p>
        <div class="qr-box">
            <canvas id="qr" data-url="<?= e($otpauth) ?>"></canvas>
            <div>
                <p class="hint" style="margin:0 0 6px">¿No puedes escanear? Introduce este secreto a mano:</p>
                <div class="secret"><?= e($SECRET) ?></div>
            </div>
        </div>

        <p class="hint">2) Escribe el <strong>código de 6 dígitos</strong> que muestra la app:</p>
        <form method="post" action="index.php">
            <input type="hidden" name="paso" value="2fa">
            <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
            <div class="field">
                <input class="code-input" type="text" name="codigo" inputmode="numeric"
                       maxlength="6" pattern="\d{6}" placeholder="000000" autofocus autocomplete="one-time-code">
            </div>
            <button type="submit" class="btn run" style="border:none;cursor:pointer;width:100%">Verificar código</button>
        </form>
        <p class="hint" style="margin-top:12px"><a href="index.php?action=logout">Cancelar y volver al login</a></p>
    </section>
    <?php
    layout('2FA', ob_get_clean());
    exit;
}

// Login
ob_start(); ?>
    <header class="hero" style="text-align:left; padding:8px 0 0">
        <span class="kicker"><span class="dot"></span> Paso 1 de 2 · Acceso</span>
        <h1 style="font-size:clamp(1.7rem,4.5vw,2.3rem)">Iniciar sesión</h1>
        <p style="margin-top:6px">Demo: usuario <code><?= e($DEMO_USER) ?></code> · contraseña <code>demo1234</code></p>
    </header>
    <section class="panel">
        <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
        <?php if ($locked): ?><div class="alert err">🔒 Cuenta bloqueada temporalmente. Espera <?= e($lock_left) ?>s.</div><?php endif; ?>

        <form method="post" action="index.php">
            <input type="hidden" name="paso" value="login">
            <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
            <div class="field">
                <label>Usuario</label>
                <input type="text" name="usuario" value="<?= e($DEMO_USER) ?>" autocomplete="username">
            </div>
            <div class="field">
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="••••••••" autocomplete="current-password">
            </div>
            <button type="submit" class="btn run" style="border:none;cursor:pointer;width:100%" <?= $locked ? 'disabled' : '' ?>>Entrar</button>
        </form>

        <div class="pills">
            <span class="pill">🛡️ CSRF</span>
            <span class="pill">🔑 2FA TOTP</span>
            <span class="pill">⏱️ Rate limiting (<?= e($MAX) ?>/<?= e($LOCK) ?>s)</span>
            <span class="pill">📦 .env</span>
        </div>
    </section>
<?php
layout('Login', ob_get_clean());

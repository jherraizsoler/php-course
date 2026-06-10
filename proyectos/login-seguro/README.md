# 🔐 Login seguro — CSRF + 2FA + .env + rate limiting

Demo educativa de un login **profesional**, con las defensas que usa un proyecto real (tipo CRM).

## Qué demuestra
- **CSRF**: token único por sesión en cada formulario (anti falsificación de peticiones).
- **Contraseñas**: `password_hash` / `password_verify` (bcrypt) — nunca en texto plano.
- **2FA (TOTP)**: segundo factor con Google/Microsoft Authenticator (`pragmarx/google2fa`), con código QR.
- **.env**: secretos fuera del código (`vlucas/phpdotenv`).
- **Rate limiting**: bloqueo temporal tras N intentos fallidos (anti fuerza bruta).

## Probarlo
1. `composer install` dentro de esta carpeta (instala phpdotenv + google2fa; el `vendor/` **no** se versiona).
2. Copia `.env.example` → `.env` y rellena tus valores:
   - hash: `php -r "echo password_hash('demo1234', PASSWORD_DEFAULT);"`
   - secreto 2FA: con `google2fa->generateSecretKey()`
   *(en este repo ya hay un `.env` local listo para usar).*
3. Abre `http://localhost:8888/repoPersonales/php-course/proyectos/login-seguro/`
4. Usuario **`jorge`**, contraseña **`demo1234`** → escanea el QR con tu app de autenticación → introduce el código de 6 dígitos.

## Fase del curso y librerías
- **Módulos**: `04` (.env / Composer) · `05` (sesiones / seguridad web) · `06` (buenas prácticas).
- **Librerías**: `vlucas/phpdotenv` · `pragmarx/google2fa` · QRCode.js (CDN, render del QR en el navegador).

> ⚠️ **Es una demo.** El usuario y el secreto 2FA viven en `.env`. En producción: cada usuario con su
> propio hash y secreto en la **BBDD**, **HTTPS** obligatorio, y el rate limiting en almacenamiento
> compartido (Redis/BBDD), no en sesión.

---

🏠 [Inicio del curso](../../index.php) · ← [Proyectos](../../modulo.php?m=proyectos)

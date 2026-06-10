# 08 · Librerías clave 🟠

Estas son las librerías que **realmente** verás y usarás en un proyecto como enfoca-nexo (las saqué
de su `application/composer.json`). Aprende a usarlas y entenderás la mitad del código de Perfex.

```bash
cd 08-librerias-clave
composer install      # instala las librerías de los ejemplos
php ejemplos/01-carbon.php
```

> El [`composer.json`](composer.json) de esta carpeta incluye Carbon, Faker, Guzzle y Collections
> para que pruebes los ejemplos. PHPMailer y TCPDF se explican con código pero no se instalan aquí
> (requieren servidor SMTP / generan archivos).

---

## 1. Carbon — fechas y horas (`nesbot/carbon`)

La mejor librería para manejar fechas. Perfex la usa para vencimientos de facturas, recordatorios…

```php
use Carbon\Carbon;

$ahora = Carbon::now();
$ahora->addDays(7)->format('d/m/Y');           // dentro de una semana
$ahora->diffForHumans();                        // "hace 2 horas"
Carbon::parse('2026-01-01')->isPast();          // true/false
$vencimiento = Carbon::parse('2026-06-30');
$vencimiento->diffInDays(Carbon::now());        // días hasta el vencimiento
Carbon::now()->locale('es')->isoFormat('LLLL'); // fecha en español
```

➡️ [`ejemplos/01-carbon.php`](ejemplos/01-carbon.php)

---

## 2. Faker — datos falsos (`fakerphp/faker`)

Genera datos realistas para poblar la BBDD en desarrollo y tests. Perfex lo usa como dependencia de
desarrollo.

```php
$faker = Faker\Factory::create('es_ES');

$faker->name();          // "Ana López Martín"
$faker->email();         // "ana.lopez@example.org"
$faker->phoneNumber();
$faker->company();
$faker->address();
$faker->dateTimeThisYear();
```

➡️ [`ejemplos/02-faker.php`](ejemplos/02-faker.php) — genera 10 usuarios falsos.

---

## 3. Guzzle — cliente HTTP (`guzzlehttp/guzzle`)

Para consumir APIs externas (pasarelas de pago, servicios). Perfex lo usa por debajo de Stripe,
PayPal, Twilio, etc.

```php
use GuzzleHttp\Client;

$client = new Client();
$res = $client->get('https://api.github.com/repos/codeigniter4/CodeIgniter4');
$datos = json_decode($res->getBody()->getContents(), true);
echo $datos['stargazers_count'];

// POST con JSON:
$res = $client->post('https://httpbin.org/post', [
    'json' => ['nombre' => 'Jorge'],
    'headers' => ['Authorization' => 'Bearer xxx'],
]);
```

➡️ [`ejemplos/03-guzzle.php`](ejemplos/03-guzzle.php) — consulta una API pública.

---

## 4. Collections — colecciones fluidas (`illuminate/collections`)

Es el componente de colecciones de Laravel, **usado por Perfex**. Convierte arrays en objetos con
una API encadenable preciosa (mejor que `array_map`/`filter` sueltos).

```php
use Illuminate\Support\Collection;

$total = collect([1, 2, 3, 4, 5])
    ->filter(fn($n) => $n % 2 === 0)   // [2, 4]
    ->map(fn($n) => $n * 10)           // [20, 40]
    ->sum();                            // 60

collect($usuarios)
    ->groupBy('rol')
    ->map(fn($grupo) => $grupo->count());
```

➡️ [`ejemplos/04-collections.php`](ejemplos/04-collections.php)

---

## 5. PHPMailer — envío de correo (`phpmailer/phpmailer`)

El estándar para enviar emails por SMTP. Perfex lo usa para todas sus notificaciones.

```php
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'tu@gmail.com';
$mail->Password   = 'app-password';   // ← desde .env, nunca hardcoded
$mail->Port       = 587;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

$mail->setFrom('tu@gmail.com', 'Mi App');
$mail->addAddress('cliente@example.com');
$mail->Subject = 'Tu factura';
$mail->isHTML(true);
$mail->Body    = '<h1>Gracias por tu compra</h1>';
$mail->send();
```

> 📄 Código completo comentado en [`ejemplos/05-phpmailer.php.txt`](ejemplos/05-phpmailer.php.txt)
> (lo dejo como `.txt` para que no intentes ejecutarlo sin un SMTP configurado).

---

## 6. TCPDF — generar PDFs (`tecnickcom/tcpdf`)

Para facturas, informes… Perfex genera todos sus PDFs con TCPDF.

```php
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML('<h1>Factura #001</h1><p>Total: 99,00 €</p>');
$pdf->Output('factura.pdf', 'I');   // I = mostrar en navegador, D = descargar, F = guardar
```

➡️ Ejemplo en [`ejemplos/06-tcpdf.php.txt`](ejemplos/06-tcpdf.php.txt).

---

## 🗂️ Otras librerías de enfoca-nexo (para que las reconozcas)

| Librería | Para qué |
|---|---|
| `cocur/slugify` | Generar slugs/URLs limpias (lo hiciste a mano en el Módulo 02) |
| `erusev/parsedown` | Convertir Markdown a HTML |
| `league/omnipay` + `omnipay/*` | Abstracción de pasarelas de pago (Stripe, PayPal, Mollie…) |
| `stripe/stripe-php` | SDK oficial de Stripe |
| `twilio/sdk` | Enviar SMS |
| `pusher/pusher-php-server` | Notificaciones en tiempo real (websockets) |
| `pragmarx/google2fa` + `bacon/bacon-qr-code` | Autenticación en dos pasos (2FA) + QR |
| `studio-42/elfinder` | Gestor de archivos web |
| `xemlock/htmlpurifier-html5` | Limpiar HTML peligroso (anti-XSS en contenido del usuario) |
| `symfony/var-dumper` | `dump()` y `dd()` para depurar bonito |

---

## 📚 Resumen

- **Carbon** = fechas. **Faker** = datos de prueba. **Guzzle** = llamar APIs.
- **Collections** = manipular datos con una API fluida (muy usada en Perfex).
- **PHPMailer** = correo. **TCPDF** = PDFs.
- Todas se instalan con `composer require` y se cargan solas por el autoload (Módulo 04).

➡️ Siguiente: **[09 · Intro a CodeIgniter 4](../09-codeigniter4-intro/)**
